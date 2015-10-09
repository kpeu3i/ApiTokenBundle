<?php

namespace Bukatov\ApiTokenBundle\RequestParamFetcher;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class JsonPostBodyRequestParamFetcher extends AbstractRequestParamFetcher
{
    public function fetch(Request $request, $name)
    {
        $data = json_decode($request->getContent(), true);

        if (is_array($data)) {
            $parameterBag = new ParameterBag($data);

            return $parameterBag->get($name, null, true);
        }

        return null;
    }
}