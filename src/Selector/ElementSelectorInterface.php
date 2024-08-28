<?php

namespace AKlump\DomTestingSelectors\Selector;

interface ElementSelectorInterface {

  /**
   * @param string $target_element_name
   *
   * @return string
   *   The stringified version of the selector using $target_element_name.
   *
   * @code
   *
   * @endcode
   */
  public function __invoke(string $target_element_name): string;

  public function setGroup(string $group): ElementSelectorInterface;

  public function setName(string $name): ElementSelectorInterface;

  public function getAttributeName(): string;

  public function getAttributeValue(): string;

  public function __toString(): string;

}
