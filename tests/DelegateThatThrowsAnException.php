<?php
declare(strict_types=1);

namespace Stratify\ErrorHandlerModule\Test;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;

class DelegateThatThrowsAnException implements DelegateInterface
{
    public function process(ServerRequestInterface $request) {
        throw new \Exception('Hello world');
    }
}
