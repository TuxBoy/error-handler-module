<?php

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Stratify\ErrorHandlerModule\ErrorHandlerMiddleware;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

return [

    ErrorHandlerMiddleware::class => function (ContainerInterface $c) {
        if ($c->has(LoggerInterface::class)) {
            $logger = $c->get(LoggerInterface::class);
        } else {
            $logger = new NullLogger;
        }

        return new ErrorHandlerMiddleware($c->get('error_handler.whoops'), $logger);
    },

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
