<?php

namespace Bukatov\ApiTokenBundle\RequestParamFetcher;

use Symfony\Component\HttpFoundation\Request;

class HeadersRequestParamFetcher extends AbstractRequestParamFetcher
{
    public function fetch(Request $request, $name)
    {
        return $request->headers->get($name);
    }
}