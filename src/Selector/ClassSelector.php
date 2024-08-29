<?php
// SPDX-License-Identifier: BSD-3-Clause

declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Selector;

/**
 * Provide selector class using `class` attribute for testing selectors.
 */
final class ClassSelector extends AbstractSelector {

  const VALUE_PREFIX = 't-';

  const GROUP_NAME_SEPARATOR = '--';

  public function getAttributeName(): string {
    return 'class';
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeValue(string $current_value = ''): string {
    $current_value = $this->filterExistingTestingSelectors($current_value);
    $value = parent::getAttributeValue($current_value);

    return trim(sprintf('%s %s', $current_value, $value));
  }

  /**
   * Remove existing testing selectors from an attribute value.
   *
   * @param string $current_value
   *
   * @return string The filtered value.
   */
  private function filterExistingTestingSelectors(string $current_value): string {
    $pattern = self::VALUE_PREFIX;
    $group = $this->getGroup();
    if ($group) {
      $pattern .= $group . self::GROUP_NAME_SEPARATOR;
    }
    $pattern = preg_quote($pattern);
    $pattern = "(^|\s)$pattern.+?(\s|$)";

    return trim(preg_replace("#$pattern#", '', $current_value));
  }

  protected function applyNamingConventions(string &$value): void {
    parent::applyNamingConventions($value);
    $value = str_replace('_', '-', $value);
  }
}
