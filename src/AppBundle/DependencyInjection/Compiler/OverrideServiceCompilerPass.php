<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('core.crypto');

        // change class
        $definition->setClass('My\DemoBundle\NewCryptoClass');

        // replace arguments
        $definition->replaceArgument(0, 'Real_Random_token');

        // add method calls
        $definition->addMethodCall('init', [$container->getParameter('secret')]);
    }
}