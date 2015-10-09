<?php

namespace Bukatov\ApiTokenBundle\RequestParamFetcher;

use Symfony\Component\HttpFoundation\Request;

class QueryStringParamFetcher extends AbstractRequestParamFetcher
{
    public function fetch(Request $request, $name)
    {
        return $request->query->get($name, null, true);
    }
}