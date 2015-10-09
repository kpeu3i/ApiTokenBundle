<?php

namespace Bukatov\ApiTokenBundle\RequestParamFetcher;

use Symfony\Component\HttpFoundation\Request;

class PostBodyRequestParamFetcher extends AbstractRequestParamFetcher
{
    public function fetch(Request $request, $name)
    {
        return $request->request->get($name, null, true);
    }
}