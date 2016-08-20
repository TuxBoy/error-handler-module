<?php
declare(strict_types = 1);

namespace Stratify\ErrorHandlerModule\ErrorResponder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Generates a response for when an error happens.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface ErrorResponder
{
    public function handle(\Throwable $error, ServerRequestInterface $request) : ResponseInterface;
}
