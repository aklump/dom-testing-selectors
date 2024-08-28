<?php
// SPDX-License-Identifier: BSD-3-Clause

declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class ReadMeTest extends TestCase {

  public function testReadMeSafeFactory() {
    $factory = new MySafeFactory();
    $selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();
    $selector->setName('foobar');

    $element1 = '<div></div>';
    $element2 = 'lorem ipsum dolar';

    $factory->getHandler($element1)->handle($element1, $selector);
    // $element1 === '<div data-test="foobar"></div>'

    $factory->getHandler($element2)->handle($element2, $selector);
    // $element2 === 'lorem ipsum dolar'

    $this->assertSame('<div data-test="foobar"></div>', $element1);
    $this->assertSame('lorem ipsum dolar', $element2);
  }

  public function testReadMeFactory() {
    $factory = new MyFactory();
    $selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();
    $selector->setName('foobar');

    $element1 = '<div></div>';
    $element2 = ['tag' => 'div'];

    try {
      $factory->getHandler($element1)->handle($element1, $selector);
      // $element1 === '<div data-test="foobar"></div>'

      $factory->getHandler($element2)->handle($element2, $selector);
      // $element2 === ['tag'=>'div','attributes'=>['data-test'=>'foobar']]
    }
    catch (\AKlump\DomTestingSelectors\Exception\NoHandlerFoundException $exception) {
      // No handler found
    }

    $this->assertSame('<div data-test="foobar"></div>', $element1);
    $this->assertSame([
      'tag' => 'div',
      'attributes' => [
        'data-test' => 'foobar',
      ],
    ], $element2);
  }

  public function testReadMeStringHandler() {
    $element = '<div></div>';
    $handler = new \AKlump\DomTestingSelectors\Handler\StringHandler();
    $selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();
    if ($handler->canHandle($element)) {
      $handler->handle($element, $selector->setName('foobar'));
    }
    // $element === '<div data-test="foobar"></div>'
    $this->assertSame('<div data-test="foobar"></div>', $element);
  }

  public function testReadMeExampleSelector() {
    $test_selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();
    $username_selector = $test_selector('username');
    $password_selector = $test_selector('password');

    // $username_selector === '"data-test"="username"'
    // $password_selector === '"data-test"="password"'

    $this->assertSame('"data-test"="username"', $username_selector);
    $this->assertSame('"data-test"="password"', $password_selector);
  }

  public function testReadMeExampleSelectorWithGroup() {
    $test_selector = new \AKlump\DomTestingSelectors\Selector\DataTestSelector();
    $test_selector->setGroup('login');
    $username_selector = $test_selector('username');
    $password_selector = $test_selector('password');

    // $username_selector === '"data-test"="login__username"'
    // $password_selector === '"data-test"="login__password"'

    $this->assertSame('"data-test"="login__username"', $username_selector);
    $this->assertSame('"data-test"="login__password"', $password_selector);
  }
}

class MyArrayHandler implements \AKlump\DomTestingSelectors\Handler\HandlerInterface {

  public function canHandle($element): bool {
    return is_array($element);
  }

  public function handle(&$element, \AKlump\DomTestingSelectors\Selector\ElementSelectorInterface $selector): void {
    $element['attributes'][$selector->getAttributeName()] = $selector->getAttributeValue();
  }
}

class MyFactory extends \AKlump\DomTestingSelectors\Factory\AbstractHandlerFactory {

  public function __construct() {
    $this->addHandler(new \AKlump\DomTestingSelectors\Handler\StringHandler());
    $this->addHandler(new MyArrayHandler());
  }
}

class MySafeFactory extends \AKlump\DomTestingSelectors\Factory\AbstractHandlerFactory {

  public function __construct() {
    $this->addHandler(new \AKlump\DomTestingSelectors\Handler\StringHandler());
    $this->addHandler(new \AKlump\DomTestingSelectors\Handler\PassThroughHandler());
  }
}
