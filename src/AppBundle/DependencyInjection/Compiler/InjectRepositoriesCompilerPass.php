<?php

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class InjectRepositoriesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // find tagged services
        $services = $container->findTaggedServiceIds('entity.repository');

        // for each service tagged
        foreach ($services as $key => $tags) {

            $definition = $container->getDefinition($key);

            foreach ($tags as $tag) {
                $entityClass = $definition->getArgument($tag['argument']);

                $dmDefinition = (new Definition(null, [$entityClass]))
                    ->setFactory([
                        new Reference('doctrine.orm.default_entity_manager'),
                        'getRepository'
                    ]);

                $definition->replaceArgument($tag['argument'], $dmDefinition);
            }
        }
    }
}