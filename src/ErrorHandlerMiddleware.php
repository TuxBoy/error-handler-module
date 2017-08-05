<?php

namespace Stratify\ErrorHandlerModule;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Stratify\ErrorHandlerModule\ErrorResponder\ErrorResponder;

/**
 * Middleware that catches errors in the next middlewares to display them with a nice error page.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ErrorHandlerMiddleware implements MiddlewareInterface
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

    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        try {
            return $delegate->process($request);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
            ]);

            return $this->responder->handle($e, $request);
        }
    }
}
