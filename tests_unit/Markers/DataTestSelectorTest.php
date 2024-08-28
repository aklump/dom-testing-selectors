<?php

namespace AKlump\DomTestingSelectors\Tests\Unit\Markers;

use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Selector\DataTestSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\AbstractSelector
 */
class DataTestSelectorTest extends TestCase {

  public function testGetAttributeMethods() {
    $marker = new DataTestSelector();
    $marker
      ->setGroup('fruits')
      ->setName('kiwi');
    $this->assertSame('data-test', $marker->getAttributeName());
    $this->assertSame('fruits__kiwi', $marker->getAttributeValue());
  }
}
