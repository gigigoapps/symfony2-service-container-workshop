<?php

namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\InjectRepositoriesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // add queue compiler pass
        $container->addCompilerPass(new InjectRepositoriesCompilerPass());
    }
}
