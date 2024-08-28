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
   * @return string
   *   The DOM element value to use for the test selector.
   */
  public function getAttributeValue(): string;

  public function __toString(): string;

}
