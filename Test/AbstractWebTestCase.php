<?php

namespace HBM\BasicsBundle\Test;

use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use HBM\BasicsBundle\Entity\Interfaces\ExtendedEntityRepo;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractWebTestCase extends WebTestCase {

  /**
   * @var string
   */
  public const REDIRECT_LOGIN = 'http://localhost/login';

  /**
   * @return ExtendedEntityRepo
   */
  abstract protected function getUserRepository() : ExtendedEntityRepo;

  /**
   * @return KernelBrowser
   */
  abstract protected function getKernelBrowser() : KernelBrowser;

  protected ?KernelBrowser $kernelBrowser = null;

  protected ?AbstractDatabaseTool $databaseTool = null;

  protected function setUp(): void {
    parent::setUp();

    $this->initClient();

    $this->databaseTool = $this->kernelBrowser->getContainer()->get(DatabaseToolCollection::class)->get();
  }

  protected function tearDown(): void {
    parent::tearDown();

    $this->databaseTool = NULL;
  }

  protected function initClient(): void {
    $this->kernelBrowser = self::createClient();
  }

  /**
   * @param array $classNames
   * @param bool $append
   *
   * @return AbstractExecutor
   */
  protected function loadFixtures(array $classNames = [], bool $append = false): AbstractExecutor {
    return $this->databaseTool->loadFixtures($classNames, $append);
  }

  /****************************************************************************/

  /**
   * Log in.
   *
   * @param KernelBrowser $client
   * @param string|int|bool|object $user
   * @param array $roles
   *
   * @return bool|object|null
   *
   * @throws \Exception
   */
  protected function logIn(KernelBrowser $client, $user = TRUE, array $roles = ['ROLE_USER']) {
    $session = $client->getContainer()->get('session');

    if (is_bool($user)) {
      $user = $this->randomUserWithRoles($roles);
    } elseif (is_int($user)) {
      $user = $this->getUserRepository()->find($user);
    }
    if ($user === NULL) {
      throw new \Exception('No user found!');
    }
    foreach ($roles as $role) {
      if (!in_array($role, $user->getRoles(), TRUE)) {
        throw new \Exception('User is missing the following role: '.$role);
      }
    }

    // The firewall context defaults to the firewall name
    $firewallContext = 'main';

    $token = new UsernamePasswordToken($user, null, $firewallContext, $user->getRoles());

    $session->set('_security_'.$firewallContext, serialize($token));
    $session->save();

    $cookie = new Cookie($session->getName(), $session->getId());
    $client->getCookieJar()->set($cookie);

    return $user;
  }

  /**
   * @return object|null
   */
  protected function randomUser() {
    $users = $this->getUserRepository()->findRandomBy([], 1);

    return reset($users) ?: null;
  }

  /**
   * @param array $roles
   *
   * @return object|null
   */
  protected function randomUserWithRoles(array $roles = []) {
    $qb = $this->getUserRepository()->createQueryBuilder('u');
    foreach ($roles as $roleIndex => $roleName) {
      $qb->andWhere($qb->expr()->like('u.roles', ':role' . $roleIndex))->setParameter('role' . $roleIndex, '%"' . $roleName . '"%');
    }
    $numOfUsers = 0;
    try {
      $numOfUsers = $qb->select('COUNT(u.id)')->getQuery()->getSingleScalarResult();
    } catch (NoResultException | NonUniqueResultException $e) {
    }

    $randomOffset = 0;
    try {
      $randomOffset = random_int(0, max(0, $numOfUsers - 1));
    } catch (\Exception $e) {
    }

    try {
      return $qb->select('u')->setFirstResult($randomOffset)->setMaxResults(1)->getQuery()->getSingleResult();
    } catch (\Exception $e) {
      return NULL;
    }
  }

  /****************************************************************************/

  /**
   * @param string|null $url
   * @param string|int|bool|object $user
   * @param string|null $redirect
   * @param bool $redirection
   *
   * @return KernelBrowser
   *
   * @throws \Exception
   */
  protected function assertRoute(string $url, $user = null, string $redirect = null, bool $redirection = FALSE) : KernelBrowser {
    $client = $this->getKernelBrowser();

    // User needed?
    if ($user !== NULL) {
      $client->restart();

      $this->assertRedirect($client, $url, static::REDIRECT_LOGIN);

      $this->logIn($client, $user);
    }

    if ($redirect || $redirection) {
      // Should redirect to specific page
      $this->assertRedirect($client, $url, $redirect, $redirection);
    } else {
      // Should show page
      $this->assertSuccessfulResponse($client, $url);
    }

    return $client;
  }

  /**
   * @param string|null $url
   * @param string|int|bool|object $user
   * @param string|null $redirect
   * @param bool $redirection
   *
   * @return KernelBrowser
   *
   * @throws \Exception
   */
  protected function assertRouteJson(string $url = null, $user = null, string $redirect = null, bool $redirection = FALSE) : KernelBrowser {
    $client = $this->assertRoute($url, $user, $redirect, $redirection);
    $resp = $client->getResponse();
    self::assertJson($resp->getContent(), '"'.$url.'" should return JSON.'.$this->getResponseMessageHint($resp));

    return $client;
  }

  /**
   * @param KernelBrowser $client
   * @param string $url
   * @param string|null $redirect
   * @param bool $redirection
   */
  protected function assertRedirect(KernelBrowser $client, string $url, string $redirect = NULL, bool $redirection = FALSE) : void {
    if ($redirect === NULL) {
      $redirect = static::REDIRECT_LOGIN;
    }

    $client->request('GET', $url);
    $resp = $client->getResponse();
    if ($redirection) {
      self::assertTrue($resp->isRedirection(), '"'.$url.'" should redirect to "'.$redirect.'".'.$this->getResponseMessageHint($resp));
    } else {
      self::assertTrue($resp->isRedirect($redirect), '"'.$url.'" should redirect to "'.$redirect.'".'.$this->getResponseMessageHint($resp));
    }
  }

  /**
   * @param KernelBrowser $client
   * @param string $url
   * @param array $parameters
   */
  protected function assertSuccessfulResponse(KernelBrowser $client, string $url, array $parameters = []) : void {
    $client->request('GET', $url, $parameters);
    $resp = $client->getResponse();
    self::assertTrue($resp->isSuccessful(), '"'.$url.'" should be successful.'.$this->getResponseMessageHint($resp));
  }

  /**
   * @param Crawler $crawler
   * @param string $redirectUrl
   * @param string $message
   */
  protected function assertHtmlContainsRedirect(Crawler $crawler, string $redirectUrl, string $message) : void {
    $redirectUrlEscaped = preg_quote($redirectUrl, '/');
    $redirectHostEscaped = preg_quote('http://localhost', '/');
    $pattern = '/(.*)<meta http-equiv="refresh" content="0;url=\'?('.$redirectHostEscaped.')?'.$redirectUrlEscaped.'\'?">(.*)/';
    self::assertMatchesRegularExpression($pattern, $crawler->html(), $message);
  }

  /**
   * @param Response $response
   * @param string $prefix
   *
   * @return string
   */
  protected function getResponseMessageHint(Response $response, string $prefix = ' ') : string {
    $data = [
      'Code' => $response->getStatusCode(),
      'Location' => $response->headers->get('Location', 'n/a'),
      'Content-Type' => $response->headers->get('Content-Type', 'n/a'),
    ];

    array_walk($data, static function(&$value, $key) {
      return $value = $key.': "'.$value.'"';
    });

    return $prefix.'['.implode(', ', $data).']';
  }

}
