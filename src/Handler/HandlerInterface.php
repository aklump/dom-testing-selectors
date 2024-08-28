<?php

namespace AKlump\DomTestingSelectors\Handler;

use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;

interface HandlerInterface {

  /**
   * @param $element
   *
   * @return bool
   *   True if this handler should be used on $element.
   */
  public function canHandle($element): bool;

  /**
   * Add the provided selector to the element.
   *
   * @param &$element
   *   If the element cannot be modified and exception is thrown so be sure to
   *   check first using ::canHandle().
   * @param \AKlump\DomTestingSelectors\Selector\ElementSelectorInterface $selector
   *
   * @return void
   *
   * @throws \AKlump\DomTestingSelectors\Exception\MismatchedHandlerException If the class cannot handle the provided $element.
   */
  public function handle(&$element, ElementSelectorInterface $selector): void;
}
