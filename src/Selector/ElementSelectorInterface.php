<?php

namespace AKlump\DomTestingSelectors\Selector;

interface ElementSelectorInterface {

  /**
   * @param string $attribute_name
   *
   * @return string
   *   The stringified version of the selector using $attribute_name.
   */
  public function __invoke(string $attribute_name, string $current_value): string;

  /**
   * Set the selector group.
   *
   * @param string $group
   *
   * @return \AKlump\DomTestingSelectors\Selector\ElementSelectorInterface
   */
  public function setGroup(string $group): ElementSelectorInterface;

  /**
   * Set the selector name.
   *
   * @param string $name
   *
   * @return \AKlump\DomTestingSelectors\Selector\ElementSelectorInterface
   */
  public function setName(string $name): ElementSelectorInterface;

  /**
   * @return string
   *   The DOM element attribute to use for the test selector.
   */
  public function getAttributeName(): string;

  /**
   * @param string $current_value For some attributes this method will want to
   * merge with an existing value, e.g. `class`.  For other attributes the value
   * should replace it, e.g., `data-test.  The current value must be passed (or
   * '') so this method can decide to merge or replace.
   *
   * @return string
   *   The DOM element value to use for the test selector.
   *
   * @throws \AKlump\DomTestingSelectors\Exception\UnnamedSelectorException If
   * the name of the selector is empty.
   *
   * @see \AKlump\DomTestingSelectors\Selector\ElementSelectorInterface::setName()
   */
  public function getAttributeValue(string $current_value): string;
}
