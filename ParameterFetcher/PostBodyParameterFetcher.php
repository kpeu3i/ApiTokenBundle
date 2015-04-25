<?php

namespace Bukatov\ApiTokenBundle\ParameterFetcher;

use Symfony\Component\HttpFoundation\Request;

class PostBodyParameterFetcher extends AbstractParameterFetcher
{
    public function fetch(Request $request, $name)
    {
        return $request->request->get($name, null, true);
    }
}