<?php
declare(strict_types = 1);

namespace Stratify\ErrorHandlerModule\ErrorResponder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Whoops\Run;
use Zend\Diactoros\Response\HtmlResponse;

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

    public function handle(\Throwable $error, ServerRequestInterface $request) : ResponseInterface
    {
        $output = $this->whoops->handleException($error);

        return new HtmlResponse($output, 500);
    }
}
