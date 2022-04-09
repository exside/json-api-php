<?php

/*
 * This file is part of JSON-API.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tobscure\Tests\JsonApi\Exception\Handler;

use Exception;
use PHPUnit\Framework\TestCase;
use Tobscure\JsonApi\Exception\Handler\FallbackExceptionHandler;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class FallbackExceptionHandlerTest extends TestCase
{
    public function testHandlerCanManageExceptions()
    {
        $handler = new FallbackExceptionHandler(false);

        $this->assertTrue($handler->manages(new Exception));
    }

    public function testErrorHandlingWithoutDebugMode()
    {
        $handler = new FallbackExceptionHandler(false);
        $response = $handler->handle(new Exception);

        $this->assertInstanceOf(ResponseBag::class, $response);
        $this->assertEquals(500, $response->getStatus());
        $this->assertEquals([['code' => 500, 'title' => 'Internal server error']], $response->getErrors());
    }

    public function testErrorHandlingWithDebugMode()
    {
        $handler = new FallbackExceptionHandler(true);
        $response = $handler->handle(new Exception);

        $this->assertInstanceOf(ResponseBag::class, $response);
        $this->assertEquals(500, $response->getStatus());
        $this->assertArrayHasKey('detail', $response->getErrors()[0]);
    }
}
