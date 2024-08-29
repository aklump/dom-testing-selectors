<?php

namespace AKlump\DomTestingSelectors\Handler;

use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;

/**
 * Use this as the last handler in a factory to prevent
 * \AKlump\DomTestingSelectors\NoHandlerFoundException from being thrown.
 */
final class PassThroughHandler implements HandlerInterface {

  /**
   * @inheritDoc
   */
  public function canHandle($element): bool {
    return TRUE;
  }

  /**
   * @inheritDoc
   */
  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    // This class is meant as a fallback and does not modify $element.
  }
}
