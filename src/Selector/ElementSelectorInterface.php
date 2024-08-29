<?php

namespace AKlump\DomTestingSelectors\Selector;

interface ElementSelectorInterface {

  /**
   * @param string $target_element_name
   *
   * @return string
   *   The stringified version of the selector using $target_element_name.
   */
  public function __invoke(string $target_element_name): string;

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
   * should replace it, e.g., `data-test`.  By passing $current_value to this
   * method, the method can handle the merge/replace decision.  It is then the
   * responsibility of the caller, for accurate preservation of data, to always
   * pass any current value based on the context of the call.
   *
   * @return string
   *   The DOM element value to use for the test selector.
   */
  public function getAttributeValue(string $current_value = ''): string;

}
