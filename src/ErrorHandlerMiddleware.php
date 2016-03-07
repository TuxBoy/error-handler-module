<?php

namespace Stratify\ErrorHandlerModule;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Whoops\Run;

/**
 * Middleware that catches errors in the next middlewares to display them with a nice error page.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ErrorHandlerMiddleware
{
    /**
     * @var Run
     */
    private $whoops;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Run $whoops, LoggerInterface $logger)
    {
        $this->whoops = $whoops;
        $this->logger = $logger;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) : ResponseInterface
    {
        try {
            return $next($request, $response);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'exception' => $e,
            ]);

            $output = $this->whoops->handleException($e);
            $response->getBody()->write($output);

            return $response->withStatus(500);
        }
    }
}
