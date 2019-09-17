<?php

namespace HBM\BasicsBundle\Test;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;
use HBM\BasicsBundle\Entity\Repository\AbstractEntityRepo;
use HBM\BasicsBundle\Service\AbstractDoctrineHelper;
use HBM\BasicsBundle\Service\AbstractServiceHelper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractWebTestCase extends WebTestCase {

  /**
   * @var AbstractServiceHelper
   */
  protected $sh;

  /**
   * @var AbstractDoctrineHelper
   */
  protected $dh;

  /**
   * @var ClassMetadata[]
   */
  protected $schemaMetadatas;

  /**
   * @var object
   */
  protected $user;

  /**
   * @var string
   */
  public const REDIRECT_LOGIN = 'http://localhost/login';

  /**
   * @return AbstractEntityRepo
   */
  abstract protected function getUserRepository() : AbstractEntityRepo;

  /**
   * {@inheritDoc}
   *
   * @throws \Exception
   */
  protected function tearDown() : void {
    parent::tearDown();

    // Only supported for lazy services.
    // $this->dh->resetOM();

    // Avoid memory leaks.
    $this->dh = null;
  }

  /**
   * @param array $fixtures
   *
   * @throws \Doctrine\ORM\Tools\ToolsException
   */
  public function loadFixtures(array $fixtures = []) {
    $loader = new Loader();
    foreach ($fixtures as $fixture) {
      $loader->addFixture(new $fixture());
    }

    /* TODO: Check if necessary! */
    /** @var EntityManager $em */
    $em = $this->dh->getOM();

    if ($this->schemaMetadatas === NULL) {
      $schemaTool = new SchemaTool($em);
      $schemaTool->dropDatabase();
      $this->schemaMetadatas = $em->getMetadataFactory()->getAllMetadata();
      if (count($this->schemaMetadatas) > 0) {
        $schemaTool->createSchema($this->schemaMetadatas);
      }
    }

    $purger = new ORMPurger($em);
    $executor = new ORMExecutor($em, $purger);
    $executor->execute($loader->getFixtures());
  }

  /****************************************************************************/

  /**
   * Log in.
   *
   * @param KernelBrowser $client
   * @param string|int|bool|object $user
   * @param array $roles
   *
   * @return object
   */
  protected function logIn(KernelBrowser $client, $user = TRUE, array $roles = ['ROLE_USER']) {
    $session = $client->getContainer()->get('session');

    if (is_bool($user)) {
      $user = $this->randomUser();
    } elseif (is_int($user)) {
      $user = $this->getUserRepository()->find($user);
    }

    // The firewall context defaults to the firewall name
    $firewallContext = 'main';

    $token = new UsernamePasswordToken($user, null, $firewallContext, $roles);
    $session->set('_security_'.$firewallContext, serialize($token));
    $session->save();

    $cookie = new Cookie($session->getName(), $session->getId());
    $client->getCookieJar()->set($cookie);

    return $user;
  }

  /**
   * @return object
   */
  protected function randomUser() {
    if ($this->user === null) {
      $users = $this->getUserRepository()->findRandomBy([], 1);
      $this->user = reset($users);
    }

    return $this->user;
  }

  /****************************************************************************/

  /**
   * @param string|null $url
   * @param array $roles
   * @param string|null $redirect
   * @param bool $redirection
   *
   * @return KernelBrowser
   */
  protected function assertRoute(string $url = null, array $roles = [], string $redirect = null, bool $redirection = FALSE) : KernelBrowser {
    $client = parent::createClient();

    // Role needed?
    if (count($roles) > 0) {
      $this->assertRedirect($client, $url, static::REDIRECT_LOGIN);

      $this->logIn($client, TRUE, $roles);
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
   * @param array $roles
   * @param string|null $redirect
   * @param bool $redirection
   *
   * @return KernelBrowser
   */
  protected function assertRouteJson(string $url = null, array $roles = [], string $redirect = null, bool $redirection = FALSE) : KernelBrowser {
    $client = $this->assertRoute($url, $roles, $redirect, $redirection);
    $resp = $client->getResponse();
    $this->assertJson($resp->getContent(), '"'.$url.'" should return JSON.'.$this->getResponseMessageHint($resp));

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
      $this->assertTrue($resp->isRedirection(), '"'.$url.'" should redirect to "'.$redirect.'".'.$this->getResponseMessageHint($resp));
    } else {
      $this->assertTrue($resp->isRedirect($redirect), '"'.$url.'" should redirect to "'.$redirect.'".'.$this->getResponseMessageHint($resp));
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
    $this->assertTrue($resp->isSuccessful(), '"'.$url.'" should be successful.'.$this->getResponseMessageHint($resp));
  }

  /**
   * @param Response $response
   * @param string $prefix
   *
   * @return string
   */
  private function getResponseMessageHint(Response $response, string $prefix = ' ') : string {
    $data = [
      'Code' => $response->getStatusCode(),
      'Location' => $response->headers->get('Location', 'n/a'),
      'Content-Type' => $response->headers->get('Content-Type', 'n/a'),
    ];

    array_walk($data, function(&$value, $key) {
      return $value = $key.': "'.$value.'"';
    });

    return $prefix.'['.implode(', ', $data).']';
  }

}
