<?php
// SPDX-License-Identifier: BSD-3-Clause

declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Handler;

use AKlump\DomTestingSelectors\Exception\MismatchedHandlerException;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use DOMDocument;
use DOMElement;
use DOMXPath;

/**
 * Handles XML strings with a single root element. The root element will receive
 * the selector.
 */
class StringHandler implements HandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function canHandle($element): bool {
    if (!is_string($element)) {
      return FALSE;
    }
    if (!preg_match('#<.+>#', $element)) {
      return FALSE;
    }

    return (bool) $this->getDOMElement($element);
  }

  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    if (!$this->canHandle($element)) {
      throw new MismatchedHandlerException();
    }
    $dom = NULL;
    $item = $this->getDOMElement($element, $dom);
    $attr_name = $selector->getAttributeName();
    $current_value = $item->getAttribute($attr_name);
    $item->setAttribute($attr_name, $selector->getAttributeValue($current_value));
    $element = $dom->saveHTML($item);
  }

  private function getDOMElement($element, &$dom = NULL): ?DOMElement {
    $dom = new DOMDocument();
    @$dom->loadXML($element);
    $xpath = new DOMXPath($dom);
    $nodeList = $xpath->query('/*');
    if ($nodeList->length !== 1) {
      return NULL;
    }
    $item = $nodeList->item(0);
    if (!$item instanceof DOMElement) {
      // I have not found a test case to cover this, but I'm keeping it so that
      // this cannot possibly fail in the scenario I'm not thinking of.
      return NULL;
    }

    return $item;
  }
}
