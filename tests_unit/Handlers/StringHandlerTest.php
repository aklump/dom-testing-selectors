<?php

namespace AKlump\DomTestingSelectors\Tests\Unit\Handlers;

use AKlump\DomTestingSelectors\Exception\MismatchedHandlerException;
use AKlump\DomTestingSelectors\Handler\StringHandler;
use AKlump\DomTestingSelectors\Selector\ClassSelector;
use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Handler\StringHandler
 * @uses   \AKlump\DomTestingSelectors\Selector\AbstractSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\DataTestSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\ClassSelector
 */
class StringHandlerTest extends TestCase {


  public function dataFortestAppliesProvider() {
    $tests = [];
    $tests[] = [
      '<?xml version=\"1.0\" encoding=\"UTF-8\"?><root><?pi target?></root>',
      FALSE,
    ];
    $tests[] = ['<!-- This is a comment --> Some text', FALSE];
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
    $handler = new StringHandler();
    if (TRUE === $expected) {
      $this->assertTrue(TRUE);

      return;
    }

    $this->expectException(MismatchedHandlerException::class);
    $handler->addTestingSelectorToElement($expected, new DataTestSelector());
  }

  public function dataForTestHandleProvider() {
    $tests = [];
    $tests[] = [
      '<div></div>',
      'lorem',
      '<div data-test="lorem"></div>',
      '<div class="t-lorem"></div>',
    ];
    $tests[] = [
      '<div class="foo">lorem ipsum</div>',
      'carrot_greens',
      '<div class="foo" data-test="carrot_greens">lorem ipsum</div>',
      '<div class="foo t-carrot-greens">lorem ipsum</div>',
    ];
    $tests[] = [
      '<p><span>gloria patri</span></p>',
      'et_filii',
      '<p data-test="et_filii"><span>gloria patri</span></p>',
      '<p class="t-et-filii"><span>gloria patri</span></p>',
    ];

    return $tests;
  }

  /**
   * @dataProvider dataForTestHandleProvider
   */
  public function testAddTestingSelectorToElement(string $xml, string $name, string $expected_data_test, string $expected_class) {
    //    (new StringHandler())->addTestingSelectorToElement($xml, (new DataTestSelector())->setName($name));
    //    $this->assertSame($expected_data_test, $xml);

    (new StringHandler())->addTestingSelectorToElement($xml, (new ClassSelector())->setName($name));
    $this->assertSame($expected_class, $xml);
  }

}
