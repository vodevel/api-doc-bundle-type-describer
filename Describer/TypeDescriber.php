<?php

declare(strict_types=1);

namespace Vodevel\ApiDocBundleTypeDescriber\Describer;

use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberInterface;
use OpenApi\Annotations\OpenApi;
use ReflectionMethod;
use Symfony\Component\Routing\Route;

final class TypeDescriber implements RouteDescriberInterface
{
    public function __construct(
        private RequestBodyDescriber  $requestDescriber,
        private ResponseBodyDescriber $responseDescriber,
    ) {
    }

    public function describe(OpenApi $api, Route $route, ReflectionMethod $reflectionMethod)
    {
        $this->requestDescriber->describe($api, $route, $reflectionMethod);
        $this->responseDescriber->describe($api, $route, $reflectionMethod);
    }
}
