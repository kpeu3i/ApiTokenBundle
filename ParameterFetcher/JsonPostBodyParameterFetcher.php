<?php

namespace Bukatov\ApiTokenBundle\ParameterFetcher;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class JsonPostBodyParameterFetcher extends AbstractParameterFetcher
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