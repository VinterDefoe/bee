<?php

namespace Core\Http;

class Request
{
    public function withQueryParams(): array
    {
        return $_GET;
    }

    public function withParsedBody()
    {
        return $_POST;
    }
    public function test(): int
    {
        return 1;
    }
}
