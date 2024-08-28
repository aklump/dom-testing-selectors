<?php

namespace AKlump\DomTestingSelectors\Tests\Unit;

use AKlump\DomTestingSelectors\Factory\AbstractHandlerFactory;
use AKlump\DomTestingSelectors\Handler\StringHandler;
use AKlump\DomTestingSelectors\NoHandlerFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Factory\AbstractHandlerFactory
 * @uses   \AKlump\DomTestingSelectors\Handler\StringHandler
 */
class AbstractHandlerFactoryTest extends TestCase {

  public function testRemoveHandler() {
    $this->expectException(NoHandlerFoundException::class);
    (new TestableFactory())->_remove()->getHandler('<div/>');
  }

  public function testNoHandlerFoundExceptionIsThrown() {
    $this->expectException(NoHandlerFoundException::class);
    (new TestableFactory())->getHandler([]);
  }

  public function testDivStringReturnsStringHandler() {
    $handler = (new TestableFactory())->getHandler('<div/>');
    $this->assertSame(StringHandler::class, get_class($handler));
  }

}

final class TestableFactory extends AbstractHandlerFactory {

  public function __construct() {
    $this->addHandler(new StringHandler());
  }

  public function _remove(): self {
    $this->removeHandler(new StringHandler());

    return $this;
  }
}
