<?php

namespace Bukatov\ApiTokenBundle\ParameterFetcher;

use Symfony\Component\HttpFoundation\Request;

class QueryStringParameterFetcher extends AbstractParameterFetcher
{
    public function fetch(Request $request, $name)
    {
        return $request->query->get($name, null, true);
    }
}