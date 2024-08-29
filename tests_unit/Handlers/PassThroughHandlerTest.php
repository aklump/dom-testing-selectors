<?php

namespace AKlump\DomTestingSelectors\Tests\Unit\Handlers;

use AKlump\DomTestingSelectors\Handler\PassThroughHandler;
use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Handler\PassThroughHandler
 * @uses   \AKlump\DomTestingSelectors\Selector\DataTestSelector
 */
class PassThroughHandlerTest extends TestCase {

  public function dataForTestCanHandleProvider(): array {
    $tests = [];
    $tests[] = [NULL];
    $tests[] = [TRUE];
    $tests[] = [FALSE];
    $tests[] = [123];
    $tests[] = [3.21];
    $tests[] = ['lorem'];
    $tests[] = ['<div/>'];
    $tests[] = [[]];
    $tests[] = [(object) []];
    $tests[] = [['data' => ['foo' => 123]]];

    return $tests;
  }

  /**
   * @dataProvider dataForTestCanHandleProvider
   */
  public function testCanHandle($element) {
    $handler = new PassThroughHandler();
    $this->assertTrue($handler->canHandle($element));
  }

  /**
   * @dataProvider dataForTestCanHandleProvider
   */
  public function testAddTestingSelectorToElement($element) {
    $handler = new PassThroughHandler();
    $selector = new DataTestSelector();
    $original = $element;
    $handler->addTestingSelectorToElement($element, $selector);
    $this->assertSame($original, $element);
  }
}
