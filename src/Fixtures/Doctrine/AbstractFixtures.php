<?php

namespace HBM\BasicsBundle\Fixtures\Doctrine;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use HBM\BasicsBundle\Fixtures\Faker\Generator\CustomGenerator;
use HBM\BasicsBundle\Fixtures\Faker\Provider\EmailsProvider;
use HBM\BasicsBundle\Fixtures\Faker\Provider\RandomArrayProvider;
use HBM\BasicsBundle\Fixtures\Faker\Provider\UrlsProvider;

abstract class AbstractFixtures extends Fixture
{
    /** @var CustomGenerator */
    protected $faker;

    /** @var string */
    public static $ref;

    /** @var int */
    public static $num;

    /** @var array */
    public static $keys;

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
     * Get keys for this fixtures.
     */
    protected function getKeys($fixture): array
    {
        if (is_array($fixture::$keys)) {
            return static::$keys;
        }

        return range(1, $fixture::$num);
    }

    protected function getRefId($fixture, $key): string
    {
        return $fixture::$ref . ':' . $key;
    }

    /**
     * @return object
     */
    public function getRef($fixture, $key)
    {
        return $this->getReference($this->getRefId($fixture, $key));
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
    protected function getRefs($fixture, array $keys): array
    {
        $refs = [];
        foreach ($keys as $key) {
            $refs[] = $this->getRef($fixture, $key);
        }

        return $refs;
    }

    /**
     * Get random number of references of a certain type of fixture.
     */
    protected function getRandomRefs($fixture, int $min = 1, int $max = null, bool $unique = true): array
    {
        return $this->getRefs($fixture, $this->getRandomRefKeys($fixture, $min, $max, $unique));
    }

    /**
     * Get a random reference of a certain type of fixture.
     *
     * @return object
     */
    protected function getRandomRef($fixture)
    {
        return $this->getRef($fixture, $this->getRandomRefKey($fixture));
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

    /**
     * Get references with a given name pattern.
     */
    protected function getMatchingRefs(string $pattern): array
    {
        $refs = [];
        foreach ($this->referenceRepository->getReferences() as $name => $reference) {
            if (preg_match($pattern, $name)) {
                $refs[$name] = $reference;
            }
        }

        return $refs;
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
