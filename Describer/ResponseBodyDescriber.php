<?php

declare(strict_types=1);

namespace Vodevel\ApiDocBundleTypeDescriber\Describer;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberInterface;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberTrait;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use ReflectionMethod;
use Symfony\Component\Routing\Route;

final class ResponseBodyDescriber implements RouteDescriberInterface
{
    use RouteDescriberTrait;
    use FindClassTrait;

    public function describe(OpenApi $api, Route $route, ReflectionMethod $reflectionMethod)
    {
        if (!$info = $this->findClassFromReturn($reflectionMethod, Response::class)) {
            return;
        }

        foreach ($this->getOperations($api, $route) as $operation) {
            $this->setResponseBody($operation, $info);
        }
    }

    private function setResponseBody(Operation $operation, ClassInfo $info): void
    {
        if (is_object($operation->responses)) {
            # TODO: merge
            return;
        }

        $responseBody = Util::getIndexedCollectionItem($operation, Response::class, 'default');
        $props = $info->props + ['description' => '', 'response' => 200];
        foreach ($props as $prop => $value) {
            $responseBody->{$prop} = $value;
        }
        $responseBody->content = [];
        $responseBody->content['application/json'] = new MediaType(['mediaType' => 'application/json']);
        $schema = Util::getChild(
            $responseBody->content['application/json'],
            Schema::class
        );
        $schema->type = 'object';
        $schema->ref = new Model(type: $info->class);
    }
}
