<?php
declare(strict_types = 1);

use function DI\get;
use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Stratify\ErrorHandlerModule\ErrorHandlerMiddleware;
use Stratify\ErrorHandlerModule\ErrorResponder\ErrorResponder;
use Stratify\ErrorHandlerModule\ErrorResponder\SimpleProductionResponder;
use Stratify\ErrorHandlerModule\ErrorResponder\WhoopsResponder;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

return [

    ErrorHandlerMiddleware::class => function (ContainerInterface $c) {
        $logger = $c->has(LoggerInterface::class) ? $c->get(LoggerInterface::class) : new NullLogger;

        return new ErrorHandlerMiddleware($c->get(ErrorResponder::class), $logger);
    },

    ErrorResponder::class => get(SimpleProductionResponder::class),

    WhoopsResponder::class => DI\object()
        ->constructor(get('error_handler.whoops')),
    'error_handler.whoops' => function () {
        $whoops = new Run();
        $whoops->writeToOutput(false);
        $whoops->allowQuit(false);
        $handler = new PrettyPageHandler;
        $handler->handleUnconditionally(true);
        $whoops->pushHandler($handler);
        return $whoops;
    }

];
