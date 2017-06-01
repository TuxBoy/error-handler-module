<?php

namespace Stratify\ErrorHandlerModule;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Stratify\ErrorHandlerModule\ErrorResponder\ErrorResponder;
use Stratify\Http\Middleware\Middleware;

/**
 * Middleware that catches errors in the next middlewares to display them with a nice error page.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ErrorHandlerMiddleware implements Middleware
{
    /**
     * @var ErrorResponder
     */
    private $responder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ErrorResponder $responder, LoggerInterface $logger)
    {
        $this->responder = $responder;
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, callable $next) : ResponseInterface
    {
        try {
            return $next($request);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
            ]);

            return $this->responder->handle($e, $request);
        }
    }
}
