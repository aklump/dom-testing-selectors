<?php

namespace AKlump\DomTestingSelectors\Exception;

use InvalidArgumentException;

/**
 * Thrown when a handler attempts to process an input it cannot addTestingSelectorToElement.
 */
final class MismatchedHandlerException extends InvalidArgumentException {

}
