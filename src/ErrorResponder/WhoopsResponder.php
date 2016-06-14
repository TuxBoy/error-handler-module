<?php
declare(strict_types = 1);

namespace Stratify\ErrorHandlerModule\ErrorResponder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Whoops\Run;

/**
 * Renders a nice error page for developers using Whoops.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class WhoopsResponder implements ErrorResponder
{
    /**
     * @var Run
     */
    private $whoops;

    public function __construct(Run $whoops)
    {
        $this->whoops = $whoops;
    }

    public function handle(
        \Throwable $error,
        ServerRequestInterface $request,
        ResponseInterface $response
    ) : ResponseInterface
    {
        $output = $this->whoops->handleException($error);

        $response->getBody()->write($output);

        return $response->withStatus(500);
    }
}
