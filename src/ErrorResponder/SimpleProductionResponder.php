<?php
declare(strict_types = 1);

namespace Stratify\ErrorHandlerModule\ErrorResponder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\TextResponse;

/**
 * A very simple responder that can be used in production: it doesn't show any sensitive information.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class SimpleProductionResponder implements ErrorResponder
{
    public function handle(\Throwable $error, ServerRequestInterface $request) : ResponseInterface
    {
        return new TextResponse('Server error', 500);
    }
}
