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

/**
 * @template T of object
 * @template S of object
 */
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

    /**
     * @param class-string<AbstractFixtures> $fixture
     * @return array<string>
     */
    protected function getKeys(string $fixture): array
    {
        if (is_array($fixture::$keys)) {
            return static::$keys;
        }

        return range(1, $fixture::$num);
    }

    /**
     * @param class-string<AbstractFixtures> $fixture
     */
    protected function getRefId(string $fixture, string|int|null $key): string
    {
        return $fixture::$ref . ':' . $key;
    }

    /**
     * @param class-string<AbstractFixtures> $fixture
     * @param string|int $key
     * @param class-string<S> $class
     * @return object<T>>
     */
    public function getRef(string $fixture, string|int|null $key, string $class): object
    {
        return $this->getReference($this->getRefId($fixture, $key), $class);
    }

    /* CREATE AND LOAD */

    /**
     * @return object<T>
     */
    abstract protected function createObject(?ObjectManager $manager = null, string|int|null $key = null): object;

    public function load(ObjectManager $manager): void
    {
        $keys = $this->getKeys(static::class);

        foreach ($keys as $key) {
            $object = $this->createObject($manager, $key);

            $manager->persist($object);

            $this->addReference($this->getRefId(static::class, $key), $object);
        }

        $manager->flush();
    }

    /**
     * @return object<T>
     */
    public function single(ObjectManager $manager, string|int|null $key = null, bool $flush = true): object
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
     * @param class-string<AbstractFixtures> $fixture
     * @param array<string> $keys
     * @param class-string<S> $class
     * @return array<T>
     */
    protected function getRefs(string $fixture, array $keys, string $class): array
    {
        $refs = [];
        foreach ($keys as $key) {
            $refs[] = $this->getRef($fixture, $key, $class);
        }

        return $refs;
    }

    /**
     * Get random number of references of a certain type of fixture.
     *
     * @param class-string<AbstractFixtures> $fixture
     * @param class-string<S> $class
     * @return array<T>
     */
    protected function getRandomRefs(string $fixture, string $class, int $min = 1, ?int $max = null, bool $unique = true): array
    {
        return $this->getRefs($fixture, $this->getRandomRefKeys($fixture, $min, $max, $unique), $class);
    }

    /**
     * Get a random reference of a certain type of fixture.
     *
     * @param class-string<AbstractFixtures> $fixture
     * @param class-string<S> $class
     * @return object<T>
     */
    protected function getRandomRef(string $fixture, string $class): object
    {
        return $this->getRef($fixture, $this->getRandomRefKey($fixture), $class);
    }

    /**
     * Get a random reference key of a certain type of fixture.
     *
     * @param class-string<AbstractFixtures> $fixture
     */
    protected function getRandomRefKey(string $fixture): string
    {
        return $this->faker->randomElement($this->getKeys($fixture));
    }

    /**
     * Get random number of reference keys of a certain type of fixture.
     *
     * @param class-string<AbstractFixtures> $fixture
     * @return array<string>
     */
    protected function getRandomRefKeys(string $fixture, int $min = 1, ?int $max = null, bool $unique = true): array
    {
        if ($max === null) {
            $max = $min;
        }

        $number = $this->faker->numberBetween($min, $max);

        return $this->faker->randomElements($this->getKeys($fixture), $number, !$unique);
    }

    /* UNIQUE */

    protected function unique(string $name, callable $value, ?callable $key = null, int $maxRetries = 100)
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
