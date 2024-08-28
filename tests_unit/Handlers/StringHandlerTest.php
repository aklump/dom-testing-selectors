<?php

namespace AKlump\DomTestingSelectors\Tests\Unit\Handlers;

use AKlump\DomTestingSelectors\Handler\StringHandler;
use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use AKlump\DomTestingSelectors\Tests\Unit\TestingTraits\HandlersTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Handler\StringHandler
 * @uses   \AKlump\DomTestingSelectors\Selector\AbstractSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\DataTestSelector
 */
class StringHandlerTest extends TestCase {


  public function dataFortestAppliesProvider() {
    $tests = [];
    $tests[] = [[], FALSE];
    $tests[] = [NULL, FALSE];
    $tests[] = [1, FALSE];
    $tests[] = [3.4, FALSE];
    $tests[] = ['lorem', FALSE];
    $tests[] = ['<div>alpha</div><div>bravo</div>', FALSE];
    $tests[] = ['<div>foobar</div>', TRUE];
    $tests[] = ['<p><span>lorem</span></p>', TRUE];

    return $tests;
  }

  /**
   * @dataProvider dataFortestAppliesProvider
   */
  public function testApplies($subject, bool $expected) {
    $foo = new StringHandler();
    $this->assertSame($expected, $foo->canHandle($subject));
  }

  /**
   * @dataProvider dataFortestAppliesProvider
   */
  public function testAppliesThrowsWhenBadSubject($subject, bool $expected) {
    $foo = new StringHandler();
    if (TRUE === $expected) {
      $this->assertTrue(TRUE);

      return;
    }

    $this->expectException(\InvalidArgumentException::class);
    $foo->handle($expected, new DataTestSelector());
  }

  public function dataFortestHandleProvider() {
    $tests = [];
    $tests[] = [
      '<div></div>',
      'lorem',
      '<div data-test="lorem"></div>',
    ];
    $tests[] = [
      '<div class="foo">lorem ipsum</div>',
      'carrot_greens',
      '<div class="foo" data-test="carrot_greens">lorem ipsum</div>',
    ];
    $tests[] = [
      '<p><span>gloria patri</span></p>',
      'et_filii',
      '<p data-test="et_filii"><span>gloria patri</span></p>',
    ];

    return $tests;
  }

  /**
   * @dataProvider dataFortestHandleProvider
   */
  public function testHandle(string $xml, string $name, string $expected) {
    $marker = new DataTestSelector();
    $marker->setName($name);
    (new StringHandler())->handle($xml, $marker);
    $this->assertSame($expected, $xml);
  }

}
