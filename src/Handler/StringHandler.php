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
    $dom = new DOMDocument();
    @$dom->loadXML($element);
    $xpath = new DOMXPath($dom);
    $nodeList = $xpath->query('/*');
    if ($nodeList->length !== 1) {
      return FALSE;
    }
    $item = $nodeList->item(0);
    if (!$item instanceof DOMElement) {
      return FALSE;
    }

    return TRUE;
  }

  public function handle(&$element, ElementSelectorInterface $selector): void {
    if (!$this->canHandle($element)) {
      throw new MismatchedHandlerException();
    }
    $dom = new DOMDocument();
    $dom->loadXML($element);
    $xpath = new DOMXPath($dom);
    $nodeList = $xpath->query('//*');
    $attribute = $selector->getAttributeName();
    $attribute_value = $selector->getAttributeValue();
    /** @var DOMElement $item */
    $item = $nodeList->item(0);
    $item->setAttribute($attribute, $attribute_value);
    $element = $dom->saveHTML($nodeList->item(0));
  }
}
