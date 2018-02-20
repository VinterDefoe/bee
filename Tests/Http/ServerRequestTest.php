<?php


namespace Tests\Http;



use Core\Http\ServerRequest;
use PHPUnit\Framework\TestCase;

class ServerRequestTest extends TestCase
{
    public function testEmpty()
    {
        $request = new ServerRequest();
        self::assertEquals([],$request->getQueryParams());
    }
}