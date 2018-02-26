<?php

namespace Tests\Pipeline;

use Core\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;


class PipelineTest extends TestCase
{
    public function testPipe()
    {
        $pipeline = new Pipeline();

        $pipeline->pipe(new MiddlewareOne());
        $pipeline->pipe(new MiddlewareTwo());
        $pipeline->setDefaultPipe(function (ServerRequestInterface $request){
            return new JsonResponse($request->getAttributes());
        });
        /**
         * @var ResponseInterface $response
         */
        $response = $pipeline(new ServerRequest());
        $res = $response->getBody()->getContents();

        $this->assertJsonStringEqualsJsonString(
            json_encode(['one' => 1, 'two' => 2]),
            $res
        );
    }
}


class MiddlewareOne
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('one', 1));
    }
}

class MiddlewareTwo
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('two', 2));
    }
}