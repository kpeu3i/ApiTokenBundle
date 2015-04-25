<?php

namespace Bukatov\ApiTokenBundle\ParameterFetcher;

use Symfony\Component\HttpFoundation\Request;

interface ParameterFetcherInterface
{
    public function fetch(Request $request, $name);
}