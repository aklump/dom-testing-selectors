<?php
// SPDX-License-Identifier: BSD-3-Clause

declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Selector;

use AKlump\DomTestingSelectors\Exception\UnamedElementException;

abstract class AbstractSelector implements ElementSelectorInterface {

  /**
   * @var string
   */
  private $targetElementGroup;

  public function setGroup(string $group): ElementSelectorInterface {
    $this->targetElementGroup = $group;

    return $this;
  }

  public function setName(string $name): ElementSelectorInterface {
    $this->targetElementName = $name;

    return $this;
  }

  /**
   * @var string
   */
  private $targetElementName;

  public function getAttributeValue(): string {
    $value = $this->targetElementName;
    if (empty($value)) {
      throw new UnamedElementException();
    }
    if (!empty($this->targetElementGroup)) {
      $value = $this->targetElementGroup . "__$value";
    }

    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString(): string {
    return sprintf('"%s"="%s"', $this->getAttributeName(), $this->getAttributeValue());
  }

  public function __invoke(string $target_element_name): string {
    return (string) $this->setName($target_element_name);
  }
}
