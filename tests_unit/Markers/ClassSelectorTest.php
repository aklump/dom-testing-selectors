<?php

namespace AKlump\DomTestingSelectors\Tests\Unit\Markers;

use AKlump\DomTestingSelectors\Selector\ClassSelector;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Selector\ClassSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\AbstractSelector
 */
class ClassSelectorTest extends TestCase {

  public function dataFortestReplacementWorksAsExpectedProvider() {
    $tests = [];
    $tests[] = ['t-bar', '', 'foo', 't-foo'];
    $tests[] = ['t-bar foot-bar', '', 'foo', 'foot-bar t-foo'];
    $tests[] = ['foot-bar t-bar ', '', 'foo', 'foot-bar t-foo'];
    $tests[] = ['foot-barrio', '', 'foo', 'foot-barrio t-foo'];
    $tests[] = ['t-foods--bread', 'foods', 'lettuce', 't-foods--lettuce'];

    return $tests;
  }

  /**
   * @dataProvider dataFortestReplacementWorksAsExpectedProvider
   */
  public function testReplacementWorksAsExpected($current_value, $group, $name, $expected) {
    $selector = new ClassSelector();
    $selector->setGroup($group);
    $selector->setName($name);
    $result = $selector->getAttributeValue($current_value);
    $this->assertSame($expected, $result);
  }

  public function dataForTestGroupManipulationProvider(): array {
    $tests = [];
    $tests[] = ['FOO', 'foo'];
    $tests[] = ['AppleBanana', 'apple-banana'];
    $tests[] = ['appleBanana', 'apple-banana'];
    $tests[] = ['foo-bar', 'foo-bar'];
    $tests[] = ['foo___bar', 'foo-bar'];
    $tests[] = ['one more time', 'one-more-time'];
    $tests[] = ['CAN.you.Feel   the -- #Love', 'can-you-feel-the-love'];

    return $tests;
  }

  /**
   * @dataProvider dataForTestGroupManipulationProvider
   */
  public function testGroupNamingConventions(string $subject, string $expected) {
    $result = (new ClassSelector())->setGroup($subject)
      ->setName('foo')
      ->getAttributeValue();
    $this->assertSame(ClassSelector::VALUE_PREFIX . "$expected--foo", $result);
  }

  /**
   * @dataProvider dataForTestGroupManipulationProvider
   */
  public function testNameNamingConventions(string $subject, string $expected) {
    $result = (new ClassSelector())
      ->setName($subject)
      ->getAttributeValue();
    $this->assertSame(ClassSelector::VALUE_PREFIX . "$expected", $result);
  }

  public function testGetAttributeMethods() {
    $marker = new ClassSelector();
    $marker
      ->setGroup('fruits')
      ->setName('kiwi');
    $this->assertSame('class', $marker->getAttributeName());
    $this->assertSame(ClassSelector::VALUE_PREFIX . 'fruits--kiwi', $marker->getAttributeValue());
  }

}
