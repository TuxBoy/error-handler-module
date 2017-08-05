<?php

namespace Stratify\ErrorHandlerModule\Test;

use DI\ContainerBuilder;
use Psr\Log\AbstractLogger;
use Psr\Log\NullLogger;
use Stratify\ErrorHandlerModule\ErrorHandlerMiddleware;
use Stratify\ErrorHandlerModule\ErrorResponder\SimpleProductionResponder;
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
        $middleware = new ErrorHandlerMiddleware(new SimpleProductionResponder, new NullLogger);
        $response = $middleware->process(new ServerRequest, new DelegateThatThrowsAnException);

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
        $response = $middleware->process(new ServerRequest, new DelegateThatThrowsAnException);

        $this->assertEquals('Server error', (string) $response->getBody());
    }

    /**
     * @test
     */
    public function shows_error_page_with_dev_environment()
    {
        $containerBuilder = new ContainerBuilder;
        $containerBuilder->addDefinitions(__DIR__ . '/../res/config/config.php');
        $containerBuilder->addDefinitions(__DIR__ . '/../res/config/env/dev.php');
        $container = $containerBuilder->build();

        /** @var ErrorHandlerMiddleware $middleware */
        $middleware = $container->get(ErrorHandlerMiddleware::class);
        $response = $middleware->process(new ServerRequest, new DelegateThatThrowsAnException);

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

        $middleware = new ErrorHandlerMiddleware(new SimpleProductionResponder, $logger);
        $middleware->process(new ServerRequest, new DelegateThatThrowsAnException);

        $this->assertEquals(['Hello world'], $logger->messages);
    }
}
