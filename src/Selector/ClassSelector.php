<?php
// SPDX-License-Identifier: BSD-3-Clause

declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Selector;

/**
 * Provide selector class using `class` attribute for testing selectors.
 */
final class ClassSelector extends AbstractSelector {

  const PREFIX = 't-';

  public function getAttributeName(): string {
    return 'class';
  }

  public function getAttributeValue(string $current_value = ''): string {
    $css_class = parent::getAttributeValue();
    $css_class = str_replace('_', '-', $css_class);
    $css_class = self::PREFIX . $css_class;

    return ltrim("$current_value $css_class");
  }


}
