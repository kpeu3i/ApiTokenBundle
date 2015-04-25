<?php

namespace Bukatov\ApiTokenBundle\ParameterFetcher;

use Symfony\Component\HttpFoundation\Request;

class HeadersParameterFetcher extends AbstractParameterFetcher
{
    public function fetch(Request $request, $name)
    {
        return $request->headers->get($name);
    }
}