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
   * Get the value side of the DOM attribute assignment with testing selector.
   *
   * @param string $current_value
   *   For most attributes, you will ignore this value.  However with the
   *   "class" attribute you will not; in that case you must 1) remove an
   *   existing testing selector class and 2) append the new testing selector
   *   classname, preserving the other classes as well.  Pay attention to how an
   *   existing attribute value should interact with the new attribute value
   *   when you are creating a new selector class.  You can look to
   *   \AKlump\DomTestingSelectors\Selector\ClassSelector as an example of how
   *   this was implemented for the "class" attribute with filtering and
   *   appending taking place.
   *
   * @return string
   *   The attribute value to be used in the DOM.
   */
  public function getAttributeValue(string $current_value = ''): string {

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
   * @param string $target_element_name
   * @param string $current_attribute_value
   *
   * @return string
   */
  public function __invoke(string $target_element_name, string $current_attribute_value = ''): string {
    $this->setName($target_element_name);

    return sprintf('%s="%s"', $this->getAttributeName(), $this->getAttributeValue($current_attribute_value));
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
