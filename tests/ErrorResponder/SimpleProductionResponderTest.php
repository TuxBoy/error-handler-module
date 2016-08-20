<?php
declare(strict_types = 1);

namespace Stratify\ErrorHandlerModule\Test\ErrorResponder;

use Stratify\ErrorHandlerModule\ErrorResponder\SimpleProductionResponder;
use Zend\Diactoros\ServerRequest;

class SimpleProductionResponderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sets_http_status_to_500()
    {
        $responder = new SimpleProductionResponder;
        $response = $responder->handle(new \Exception, new ServerRequest);

        $this->assertEquals(500, $response->getStatusCode());
    }
}
