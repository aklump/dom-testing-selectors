<?php

namespace AKlump\DomTestingSelectors\Tests\Unit\Markers;

use AKlump\DomTestingSelectors\Exception\UnnamedSelectorException;
use AKlump\DomTestingSelectors\Selector\AbstractSelector;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Selector\AbstractSelector
 */
class AbstractSelectorTest extends TestCase {

  public function testInvoke() {
    $selector = (new TestableSelector())('login');
    $this->assertIsString($selector);
    $this->assertSame('data-testable-selector="login"', $selector);
  }

  public function testGetAttributeValueBeforeSetThrows() {
    $this->expectException(UnnamedSelectorException::class);
    (new TestableSelector())->getAttributeValue();
  }

  public function testGetAttributeMethods() {
    $marker = new TestableSelector();
    $marker
      ->setGroup('fruits')
      ->setName('banana');
    $this->assertSame('data-testable-selector', $marker->getAttributeName());
    $this->assertSame('fruits__banana', $marker->getAttributeValue());
  }
}

class TestableSelector extends AbstractSelector {

  public function getAttributeName(): string {
    return 'data-testable-selector';
  }
}
