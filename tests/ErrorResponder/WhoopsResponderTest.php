<?php
declare(strict_types = 1);

namespace Stratify\ErrorHandlerModule\Test\ErrorResponder;

use Stratify\ErrorHandlerModule\ErrorResponder\WhoopsResponder;
use Whoops\Run;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class WhoopsResponderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function sets_http_status_to_500()
    {
        $responder = new WhoopsResponder(new Run);
        $response = $responder->handle(new \Exception, new ServerRequest, new Response);

        $this->assertEquals(500, $response->getStatusCode());
    }
}
