<?php

namespace HBM\BasicsBundle\Fixtures\Doctrine;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use HBM\BasicsBundle\Fixtures\Faker\Generator\CustomGenerator;
use HBM\BasicsBundle\Fixtures\Faker\Provider\EmailsProvider;
use HBM\BasicsBundle\Fixtures\Faker\Provider\RandomArrayProvider;
use HBM\BasicsBundle\Fixtures\Faker\Provider\UrlsProvider;

abstract class AbstractFixtures extends Fixture {

  /**
   * @var CustomGenerator
   */
  protected $faker;

  /**
   * @var string
   */
  public static $ref;

  /**
   * @var int
   */
  public static $num;

  /**
   * @var array
   */
  public static $keys;

  /**
   * AbstractFixtures constructor.
   */
  public function __construct() {
    $this->faker = Factory::create('de_DE');
    $this->faker->addProvider(new RandomArrayProvider($this->faker));
    $this->faker->addProvider(new UrlsProvider($this->faker));
    $this->faker->addProvider(new EmailsProvider($this->faker));
  }

  /**
   * Get keys for this fixtures.
   *
   * @param $fixture
   *
   * @return array
   */
  protected function getKeys($fixture) : array {
    if (is_array($fixture::$keys)) {
      return static::$keys;
    }

    return range(1, $fixture::$num);
  }

  /**
   * @param $fixture
   * @param $key
   *
   * @return string
   */
  protected function getRefId($fixture, $key) : string {
    return $fixture::$ref.':'.$key;
  }

  /**
   * @param $fixture
   * @param $key
   *
   * @return object
   */
  public function getRef($fixture, $key) {
    return $this->getReference($this->getRefId($fixture, $key));
  }

  /****************************************************************************/
  /* CREATE AND LOAD                                                          */
  /****************************************************************************/

  /**
   * @param ObjectManager|null $manager
   * @param string|null $key
   *
   * @return mixed
   */
  abstract protected function createObject(ObjectManager $manager = NULL, string $key = NULL);

  /**
   * @param ObjectManager $manager
   */
  public function load(ObjectManager $manager) : void {
    $keys = $this->getKeys(static::class);

    foreach ($keys as $key) {
      $object = $this->createObject($manager, (string) $key);

      $manager->persist($object);

      $this->addReference($this->getRefId(static::class, $key), $object);
    }

    $manager->flush();
  }

  /**
   * @param ObjectManager $manager
   * @param string|null $key
   *
   * @return mixed
   */
  public function single(ObjectManager $manager, string $key = NULL, bool $flush = true) {
    $object = $this->createObject($manager, $key);
    $manager->persist($object);

    if ($flush) {
      $manager->flush();
    }

    return $object;
  }

  /****************************************************************************/
  /* REFERENCES                                                               */
  /****************************************************************************/

  /**
   * Get random number of references of a certain type of fixture.
   *
   * @param $fixture
   * @param int $min
   * @param int|NULL $max
   * @param bool $unique
   *
   * @return array
   */
  protected function getRandomRefs($fixture, int $min = 1, int $max = NULL, bool $unique = TRUE) : array {
    if ($max === NULL) {
      $max = $min;
    }

    $number = $this->faker->numberBetween($min, $max);

    $keys = $this->faker->randomElements($this->getKeys($fixture), $number, !$unique);

    $refs = [];
    foreach ($keys as $key) {
      $refs[] = $this->getRef($fixture, $key);
    }

    return $refs;
  }

  /**
   * Get a random reference of a certain type of fixture.
   *
   * @param $fixture
   *
   * @return object
   */
  protected function getRandomRef($fixture) {
    return $this->getRef($fixture, $this->faker->randomElement($this->getKeys($fixture)));
  }

  /**
   * Get references with a given name pattern.
   *
   * @param string $pattern
   *
   * @return array
   */
  protected function getMatchingRefs(string $pattern) : array {
    $refs = [];
    foreach ($this->referenceRepository->getReferences() as $name => $reference) {
      if (preg_match($pattern, $name)) {
        $refs[$name] = $reference;
      }
    }
    return $refs;
  }

}

