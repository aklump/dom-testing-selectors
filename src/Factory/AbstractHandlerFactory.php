<?php
// SPDX-License-Identifier: BSD-3-Clause

declare(strict_types=1);

namespace AKlump\DomTestingSelectors\Factory;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\NoHandlerFoundException;

abstract class AbstractHandlerFactory implements HandlerFactoryInterface {

  private $handlers = [];

  public function getHandler($element): HandlerInterface {
    foreach ($this->handlers as $handler) {
      if ($handler->canHandle($element)) {
        break;
      }
      unset($handler);
    }
    if (empty($handler)) {
      throw new NoHandlerFoundException();
    }

    return $handler;
  }

  protected function addHandler(HandlerInterface $handler): self {
    $this->handlers[get_class($handler)] = $handler;

    return $this;
  }

  protected function removeHandler(HandlerInterface $handler): self {
    unset($this->handlers[get_class($handler)]);

    return $this;
  }
}
