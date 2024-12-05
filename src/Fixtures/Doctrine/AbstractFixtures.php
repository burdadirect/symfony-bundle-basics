<?php

namespace HBM\BasicsBundle\Fixtures\Doctrine;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use HBM\BasicsBundle\Fixtures\Faker\Generator\CustomGenerator;
use HBM\BasicsBundle\Fixtures\Faker\Provider\EmailsProvider;
use HBM\BasicsBundle\Fixtures\Faker\Provider\RandomArrayProvider;
use HBM\BasicsBundle\Fixtures\Faker\Provider\UrlsProvider;

abstract class AbstractFixtures extends Fixture
{
    protected Generator|CustomGenerator $faker;

    public static string $ref;
    public static int $num;
    public static ?array $keys = null;

    protected array $combinations = [];

    /**
     * AbstractFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create('de_DE');
        $this->faker->addProvider(new RandomArrayProvider($this->faker));
        $this->faker->addProvider(new UrlsProvider($this->faker));
        $this->faker->addProvider(new EmailsProvider($this->faker));
    }

    protected function getKeys($fixture): array
    {
        if (is_array($fixture::$keys)) {
            return static::$keys;
        }

        return range(1, $fixture::$num);
    }

    protected function getRefId($fixture, $key): string
    {
        return $fixture::$ref.':'.$key;
    }

    public function getRef($fixture, $key, string $class): object
    {
        return $this->getReference($this->getRefId($fixture, $key), $class);
    }

    /* CREATE AND LOAD */

    abstract protected function createObject(ObjectManager $manager = null, string $key = null);

    public function load(ObjectManager $manager): void
    {
        $keys = $this->getKeys(static::class);

        foreach ($keys as $key) {
            $object = $this->createObject($manager, (string) $key);

            $manager->persist($object);

            $this->addReference($this->getRefId(static::class, $key), $object);
        }

        $manager->flush();
    }

    public function single(ObjectManager $manager, string $key = null, bool $flush = true)
    {
        $object = $this->createObject($manager, $key);
        $manager->persist($object);

        if ($flush) {
            $manager->flush();
        }

        return $object;
    }

    /* REFERENCES */

    /**
     * Get references for keys.
     */
    protected function getRefs($fixture, array $keys, string $class): array
    {
        $refs = [];
        foreach ($keys as $key) {
            $refs[] = $this->getRef($fixture, $key, $class);
        }

        return $refs;
    }

    /**
     * Get random number of references of a certain type of fixture.
     */
    protected function getRandomRefs($fixture, string $class, int $min = 1, int $max = null, bool $unique = true): array
    {
        return $this->getRefs($fixture, $this->getRandomRefKeys($fixture, $min, $max, $unique), $class);
    }

    /**
     * Get a random reference of a certain type of fixture.
     */
    protected function getRandomRef($fixture, string $class): object
    {
        return $this->getRef($fixture, $this->getRandomRefKey($fixture), $class);
    }

    /**
     * Get a random reference key of a certain type of fixture.
     */
    protected function getRandomRefKey($fixture): string
    {
        return $this->faker->randomElement($this->getKeys($fixture));
    }

    /**
     * Get random number of reference keys of a certain type of fixture.
     */
    protected function getRandomRefKeys($fixture, int $min = 1, int $max = null, bool $unique = true): array
    {
        if ($max === null) {
            $max = $min;
        }

        $number = $this->faker->numberBetween($min, $max);

        return $this->faker->randomElements($this->getKeys($fixture), $number, !$unique);
    }

    /* UNIQUE */

    protected function unique(string $name, callable $value, callable $key = null, int $maxRetries = 100)
    {
        $key = $key ?: static function ($result) {
            return serialize($result);
        };

        $this->combinations[$name] = [];

        $i = 0;

        do {
            $valueResolved = $value();
            $keyResolved   = $key($valueResolved);

            if ($i++ > $maxRetries) {
                throw new \OverflowException(sprintf('Maximum retries of %d reached without finding a unique combination', $maxRetries));
            }
        } while (array_key_exists($keyResolved, $this->combinations[$name]));
        $this->combinations[$name][$keyResolved] = $valueResolved;

        return $valueResolved;
    }
}
