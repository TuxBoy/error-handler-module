<?php

namespace Stratify\ErrorHandlerModule;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ErrorHandlerMiddleware
{
    private $whoops;

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) : ResponseInterface
    {
        try {
            return $next($request, $response);
        } catch (\Exception $e) {
            if (! $this->whoops) {
                $this->whoops = new Run();
                $this->whoops->writeToOutput(false);
                $this->whoops->allowQuit(false);
                $this->whoops->pushHandler(new PrettyPageHandler);
            }

            $output = $this->whoops->handleException($e);

            $response->getBody()->write($output);

            return $response->withStatus(500);
        }
    }
}
