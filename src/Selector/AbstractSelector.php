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

  /**
   * {@inheritdoc}
   * @see \AKlump\DomTestingSelectors\Selector\AbstractSelector::applyNamingConventions()
   */
  public function setGroup(string $group): ElementSelectorInterface {
    $this->applyNamingConventions($group);
    $this->targetElementGroup = $group;

    return $this;
  }

  /**
   * {@inheritdoc}
   * @see \AKlump\DomTestingSelectors\Selector\AbstractSelector::applyNamingConventions()
   */
  public function setName(string $name): ElementSelectorInterface {
    $this->applyNamingConventions($name);
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

  /**
   * Change $value to adhere to naming conventions.
   *
   * @param string &$value
   *   The value to apply naming conventions to.
   *
   * @return void
   */
  protected function applyNamingConventions(string &$value): void {
    $value = preg_replace('/([a-z])([A-Z])/', '$1_$2', $value);
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9_]/', '_', $value);
    $value = preg_replace('#_{2,}#', '_', $value);
  }
}
