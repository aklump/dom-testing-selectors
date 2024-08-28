<?php
// SPDX-License-Identifier: BSD-3-Clause

declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Exception;

use InvalidArgumentException;

/**
 * Indicates there is no handler for a given $element.
 */
class NoHandlerFoundException extends InvalidArgumentException {

}
