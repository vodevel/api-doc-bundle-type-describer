<?php

declare(strict_types=1);

namespace Vodevel\ApiDocBundleTypeDescriber;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vodevel\ApiDocBundleTypeDescriber\DependencyInjection\VodevelApiDocTypeDescriberExtension;

class VodevelApiDocBundleTypeDescriberBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new VodevelApiDocTypeDescriberExtension();
    }
}
