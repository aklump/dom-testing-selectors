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
   * @param &$element
   *   If the element cannot be modified and exception is thrown so be sure to
   *   check first using ::canHandle().
   *
   * @return void
   *
   * @throws \InvalidArgumentException If the class couldn't alter the $element.
   */
  public function handle(&$element, ElementSelectorInterface $selector): void;
}
