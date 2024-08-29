<?php

namespace AKlump\DomTestingSelectors\Factory;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;

interface HandlerFactoryInterface {

  /**
   * Get a handler instance for adding test selectors to $element.
   *
   * @param mixed $element The element to which you will be adding testing selectors.
   *
   * @return \AKlump\DomTestingSelectors\Handler\HandlerInterface
   *   The first handler to return true for `::canHandle` will be used.
   *
   * @throws \AKlump\DomTestingSelectors\Exception\NoHandlerFoundException If there is no known-handler to addTestingSelectorToElement $element.
   */
  public function getHandler($element): HandlerInterface;
}
