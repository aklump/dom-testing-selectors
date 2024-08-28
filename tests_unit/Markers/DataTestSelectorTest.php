<?php

namespace AKlump\DomTestingSelectors\Tests\Unit\Markers;

use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Selector\DataTestSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\AbstractSelector
 */
class DataTestSelectorTest extends TestCase {

  public function dataForTestGroupManipulationProvider(): array {
    $tests = [];
    $tests[] = ['FOO', 'foo'];
    $tests[] = ['AppleBanana', 'apple_banana'];
    $tests[] = ['appleBanana', 'apple_banana'];
    $tests[] = ['foo-bar', 'foo_bar'];
    $tests[] = ['foo___bar', 'foo_bar'];
    $tests[] = ['one more time', 'one_more_time'];
    $tests[] = ['CAN.you.Feel   the -- #Love', 'can_you_feel_the_love'];

    return $tests;
  }

  /**
   * @dataProvider dataForTestGroupManipulationProvider
   */
  public function testGroupNamingConventions(string $subject, string $expected) {
    $result = (new DataTestSelector())->setGroup($subject)
      ->setName('foo')
      ->getAttributeValue();
    $this->assertSame("{$expected}__foo", $result);
  }

  /**
   * @dataProvider dataForTestGroupManipulationProvider
   */
  public function testNameNamingConventions(string $subject, string $expected) {
    $result = (new DataTestSelector())
      ->setName($subject)
      ->getAttributeValue();
    $this->assertSame($expected, $result);
  }

  public function testGetAttributeMethods() {
    $marker = new DataTestSelector();
    $marker
      ->setGroup('fruits')
      ->setName('kiwi');
    $this->assertSame('data-test', $marker->getAttributeName());
    $this->assertSame('fruits__kiwi', $marker->getAttributeValue());
  }
}
