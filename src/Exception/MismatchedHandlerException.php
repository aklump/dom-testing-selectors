<?php

namespace AKlump\DomTestingSelectors\Exception;

use InvalidArgumentException;

/**
 * Thrown when a handler attempts to process an input it cannot handle.
 */
class MismatchedHandlerException extends InvalidArgumentException {

}
