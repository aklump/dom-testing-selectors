<?php
// SPDX-License-Identifier: BSD-3-Clause

declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Selector;

use AKlump\DomTestingSelectors\Exception\UnnamedSelectorException;

abstract class AbstractSelector implements ElementSelectorInterface {

  /**
   * Appears at the front of the DOM attribute value.
   */
  const VALUE_PREFIX = '';

  /**
   * Appears at the end of the DOM attribute value.
   */
  const VALUE_SUFFIX = '';

  /**
   * Appears only if there is a group to separate it from the name.
   */
  const GROUP_NAME_SEPARATOR = '__';

  /**
   * @var string
   */
  private $group = '';

  /**
   * @var string
   */
  private $name = '';

  /**
   * {@inheritdoc}
   * @see \AKlump\DomTestingSelectors\Selector\AbstractSelector::applyNamingConventions()
   */
  public function setGroup(string $group): ElementSelectorInterface {
    $this->applyNamingConventions($group);
    $this->group = $group;

    return $this;
  }

  public function getGroup(): string {
    return $this->group;
  }

  public function getName(): string {
    return $this->name;
  }


  /**
   * {@inheritdoc}
   * @see \AKlump\DomTestingSelectors\Selector\AbstractSelector::applyNamingConventions()
   */
  public function setName(string $name): ElementSelectorInterface {
    $this->applyNamingConventions($name);
    $this->name = $name;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeValue(string $current_value): string {

    // The name MUST be present at this point.
    $name = $this->getName();
    if (empty($name)) {
      throw new UnnamedSelectorException();
    }

    $value = static::VALUE_PREFIX;
    $group = $this->getGroup();
    if ($group) {
      $value .= $group . static::GROUP_NAME_SEPARATOR;
    }
    $value .= $name;

    return $value . static::VALUE_SUFFIX;
  }

  /**
   * Return the string testing selector ready for HTML.
   *
   * @param string $attribute_name
   * @param string $current_value
   *
   * @return string
   */
  public function __invoke(string $attribute_name, string $current_value): string {
    $this->setName($attribute_name);

    return sprintf('%s="%s"', $this->getAttributeName(), $this->getAttributeValue($current_value));
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
