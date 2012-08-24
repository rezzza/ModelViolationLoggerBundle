<?php

namespace Rezzza\ModelViolationLoggerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * AddLoggersCompilerPass
 *
 * @uses CompilerPassInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class AddLoggersCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('vlr.model.violation.handler') as $id => $tags) {
            $container
                ->getDefinition('rezzza.violation.handler.manager')
                ->addMethodCall('add', array($container->getDefinition($id)));
        }
    }
}
