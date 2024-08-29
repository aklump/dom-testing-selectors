<?php

namespace AKlump\DomTestingSelectors\Tests\Unit\Markers;

use AKlump\DomTestingSelectors\Selector\ClassSelector;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Selector\ClassSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\AbstractSelector
 */
class ClassSelectorTest extends TestCase {

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
    $this->assertSame(ClassSelector::PREFIX . "$expected--foo", $result);
  }

  /**
   * @dataProvider dataForTestGroupManipulationProvider
   */
  public function testNameNamingConventions(string $subject, string $expected) {
    $result = (new ClassSelector())
      ->setName($subject)
      ->getAttributeValue();
    $this->assertSame(ClassSelector::PREFIX . "$expected", $result);
  }

  public function testGetAttributeMethods() {
    $marker = new ClassSelector();
    $marker
      ->setGroup('fruits')
      ->setName('kiwi');
    $this->assertSame('class', $marker->getAttributeName());
    $this->assertSame(ClassSelector::PREFIX . 'fruits--kiwi', $marker->getAttributeValue());
  }

}
