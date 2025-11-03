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
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractWebTestCase extends WebTestCase
{
    /** @var string */
    public const REDIRECT_LOGIN = 'http://localhost/login';

    abstract protected function getUserRepository(): ExtendedEntityRepo;

    abstract protected function getKernelBrowser(): KernelBrowser;

    protected ?KernelBrowser $kernelBrowser = null;

    protected ?AbstractDatabaseTool $databaseTool = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->initClient();

        $this->databaseTool = $this->kernelBrowser->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->databaseTool  = null;
        $this->kernelBrowser = null;
    }

    protected function initClient(): void
    {
        $this->kernelBrowser = self::createClient();
    }

    protected function loadFixtures(array $classNames = [], bool $append = false): AbstractExecutor
    {
        return $this->databaseTool->loadFixtures($classNames, $append);
    }

    protected function logIn(KernelBrowser $client, object|bool|int|string|null $user = true, array $roles = ['ROLE_USER']): ?object
    {
        if (is_bool($user)) {
            $user = $this->randomUserWithRoles($roles);
        } elseif (is_int($user)) {
            $user = $this->getUserRepository()->find($user);
        }

        if ($user === null) {
            throw new \RuntimeException('No user found!');
        }
        foreach ($roles as $role) {
            if (!in_array($role, $user->getRoles(), true)) {
                throw new \RuntimeException('User is missing the following role: ' . $role);
            }
        }

        $client->loginUser($user);

        return $user;
    }

    protected function randomUser(): ?object
    {
        $users = $this->getUserRepository()->findRandomBy([], 1);

        return reset($users) ?: null;
    }

    protected function randomUserWithRoles(array $roles = []): ?object
    {
        $qb = $this->getUserRepository()->createQueryBuilderForAlias('u');
        foreach ($roles as $roleIndex => $roleName) {
            $qb->andWhere($qb->expr()->like('u.roles', ':role' . $roleIndex))->setParameter('role' . $roleIndex, '%"' . $roleName . '"%');
        }
        $numOfUsers = 0;

        try {
            $numOfUsers = $qb->select('COUNT(u.id)')->getQuery()->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException) {
        }

        $randomOffset = 0;

        try {
            $randomOffset = random_int(0, max(0, $numOfUsers - 1));
        } catch (\Exception) {
        }

        try {
            return $qb->select('u')->setFirstResult($randomOffset)->setMaxResults(1)->getQuery()->getSingleResult();
        } catch (\Exception) {
            return null;
        }
    }

    protected function assertRoute(string $url, object|bool|int|string|null $user = null, ?string $redirect = null, bool $redirection = false): KernelBrowser
    {
        $client = $this->getKernelBrowser();

        // User needed?
        if ($user !== null) {
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

    protected function assertRouteJson(?string $url = null, object|bool|int|string|null $user = null, ?string $redirect = null, bool $redirection = false): KernelBrowser
    {
        $client = $this->assertRoute($url, $user, $redirect, $redirection);
        $resp   = $client->getResponse();
        self::assertJson($resp->getContent(), '"' . $url . '" should return JSON.' . $this->getResponseMessageHint($resp));

        return $client;
    }

    protected function assertRedirect(KernelBrowser $client, string $url, ?string $redirect = null, bool $redirection = false): void
    {
        if ($redirect === null) {
            $redirect = static::REDIRECT_LOGIN;
        }

        $client->request(Request::METHOD_GET, $url);
        $resp = $client->getResponse();

        if ($redirection) {
            self::assertTrue($resp->isRedirection(), '"' . $url . '" should redirect to "' . $redirect . '".' . $this->getResponseMessageHint($resp));
        } else {
            self::assertTrue($resp->isRedirect($redirect), '"' . $url . '" should redirect to "' . $redirect . '".' . $this->getResponseMessageHint($resp));
        }
    }

    protected function assertSuccessfulResponse(KernelBrowser $client, string $url, array $parameters = []): void
    {
        $client->request(Request::METHOD_GET, $url, $parameters);
        $resp = $client->getResponse();
        self::assertTrue($resp->isSuccessful(), '"' . $url . '" should be successful.' . $this->getResponseMessageHint($resp));
    }

    protected function assertHtmlContainsRedirect(Crawler $crawler, string $redirectUrl, string $message): void
    {
        $redirectUrlEscaped  = preg_quote($redirectUrl, '/');
        $redirectHostEscaped = preg_quote('http://localhost', '/');
        $pattern             = '/(.*)<meta http-equiv="refresh" content="0;url=\'?(' . $redirectHostEscaped . ')?' . $redirectUrlEscaped . '\'?">(.*)/';
        self::assertMatchesRegularExpression($pattern, $crawler->html(), $message);
    }

    protected function getResponseMessageHint(Response $response, string $prefix = ' '): string
    {
        $data = [
            'Code'         => $response->getStatusCode(),
            'Location'     => $response->headers->get('Location', 'n/a'),
            'Content-Type' => $response->headers->get('Content-Type', 'n/a'),
        ];

        array_walk($data, static function (&$value, $key) {
            return $value = $key . ': "' . $value . '"';
        });

        return $prefix . '[' . implode(', ', $data) . ']';
    }
}
