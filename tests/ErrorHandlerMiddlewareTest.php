<?php

namespace Stratify\ErrorHandlerModule\Test;

use DI\ContainerBuilder;
use Psr\Log\AbstractLogger;
use Psr\Log\NullLogger;
use Stratify\ErrorHandlerModule\ErrorHandlerMiddleware;
use Whoops\Run;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class ErrorHandlerMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function middleware_is_configured()
    {
        $containerBuilder = new ContainerBuilder;
        $containerBuilder->addDefinitions(__DIR__ . '/../res/config/config.php');
        $container = $containerBuilder->build();

        $middleware = $container->get(ErrorHandlerMiddleware::class);

        $this->assertInstanceOf(ErrorHandlerMiddleware::class, $middleware);
    }

    /**
     * @test
     */
    public function sets_http_status_to_500()
    {
        $middleware = new ErrorHandlerMiddleware(new Run, new NullLogger);
        $response = $middleware->__invoke(new ServerRequest, new Response, function () {
            throw new \Exception('Hello world');
        });

        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function shows_error_page()
    {
        $containerBuilder = new ContainerBuilder;
        $containerBuilder->addDefinitions(__DIR__ . '/../res/config/config.php');
        $container = $containerBuilder->build();

        /** @var ErrorHandlerMiddleware $middleware */
        $middleware = $container->get(ErrorHandlerMiddleware::class);
        $response = $middleware->__invoke(new ServerRequest, new Response, function () {
            throw new \Exception('Hello world');
        });

        $this->assertContains('Error', (string) $response->getBody());
        $this->assertContains('Hello world', (string) $response->getBody());
    }

    /**
     * @test
     */
    public function logs_error()
    {
        $logger = new class() extends AbstractLogger
        {
            public $messages;
            public function log($level, $message, array $context = [])
            {
                $this->messages[] = $message;
            }
        };

        $middleware = new ErrorHandlerMiddleware(new Run, $logger);
        $middleware->__invoke(new ServerRequest, new Response, function () {
            throw new \Exception('Hello world');
        });

        $this->assertEquals(['Hello world'], $logger->messages);
    }
}
