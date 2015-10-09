<?php

namespace Bukatov\ApiTokenBundle\RequestParamFetcher;

use Symfony\Component\HttpFoundation\Request;

interface RequestParamFetcherInterface
{
    public function fetch(Request $request, $name);
}