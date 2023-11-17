<?php

declare(strict_types=1);

namespace Vodevel\ApiDocBundleTypeDescriber\Describer;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberInterface;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberTrait;
use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\RequestBody as AnnotationRequestBody;
use OpenApi\Attributes\RequestBody as AttributeRequestBody;
use ReflectionMethod;
use Symfony\Component\Routing\Route;

final class RequestBodyDescriber implements RouteDescriberInterface
{
    use RouteDescriberTrait;
    use FindClassTrait;

    public function describe(OpenApi $api, Route $route, ReflectionMethod $reflectionMethod)
    {
        if (!$info = $this->findClassFromParams($reflectionMethod, AnnotationRequestBody::class)) {
            return;
        }

        foreach ($this->getOperations($api, $route) as $operation) {
            $this->setRequestBody($operation, $info);
        }
    }

    private function setRequestBody(Operation $operation, ClassInfo $info): void
    {
        if (is_object($operation->requestBody)) {
            # TODO: merge
            return;
        }

        $requestBodyAnnotation = new AttributeRequestBody(
            request: $operation->path, # TODO: what should be here?
            content: new Model(type: $info->class)
        );

        foreach ($info->props as $prop => $value) {
            $requestBodyAnnotation->{$prop} = $value;
        }

        $operation->requestBody = $requestBodyAnnotation;
    }
}
