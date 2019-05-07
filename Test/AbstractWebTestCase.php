<?php

namespace HBM\BasicsBundle\Tests;

use HBM\BasicsBundle\Entity\Repository\AbstractEntityRepo;
use HBM\BasicsBundle\Service\AbstractDoctrineHelper;
use HBM\BasicsBundle\Service\AbstractServiceHelper;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
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

    $this->dh->resetOM();
    $this->dh = null; // avoid memory leaks
  }

  /**
   * @param bool $authentication
   * @param array $params
   *
   * @return Client
   */
  protected function makeClient($authentication = false, array $params = []): Client {
    return parent::makeClient($authentication, $params);
  }

  /****************************************************************************/

  /**
   * Log in.
   *
   * @param Client $client
   * @param string|int|bool|object $user
   * @param array $roles
   *
   * @return object
   */
  protected function logIn(Client $client, $user = TRUE, array $roles = ['ROLE_USER']) {
    $session = $client->getContainer()->get('session');

    if (\is_bool($user)) {
      $user = $this->randomUser();
    } elseif (\is_int($user)) {
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
   * @return Client
   */
  protected function assertRoute(string $url = null, array $roles = [], string $redirect = null, bool $redirection = FALSE) : Client {
    $client = $this->makeClient();

    // Role needed?
    if (\count($roles) > 0) {
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
   * @return Client
   */
  protected function assertRouteJson(string $url = null, array $roles = [], string $redirect = null, bool $redirection = FALSE) : Client {
    $client = $this->assertRoute($url, $roles, $redirect, $redirection);
    $resp = $client->getResponse();
    $this->assertJson($resp->getContent(), '"'.$url.'" should return JSON.'.$this->getResponseMessageHint($resp));

    return $client;
  }

  /**
   * @param Client $client
   * @param string $url
   * @param string|null $redirect
   * @param bool $redirection
   */
  protected function assertRedirect(Client $client, string $url, string $redirect = NULL, bool $redirection = FALSE) : void {
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
   * @param Client $client
   * @param string $url
   * @param array $parameters
   */
  protected function assertSuccessfulResponse(Client $client, string $url, array $parameters = []) : void {
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
