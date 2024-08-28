<?php
// SPDX-License-Identifier: BSD-3-Clause

declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Selector;

final class DataTestSelector extends AbstractSelector {

  public function getAttributeName(): string {
    return 'data-test';
  }

}
