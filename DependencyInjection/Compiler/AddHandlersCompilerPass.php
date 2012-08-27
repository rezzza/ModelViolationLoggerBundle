<?php

namespace Rezzza\ModelViolationLoggerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * AddHandlersCompilerPass
 *
 * @uses CompilerPassInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class AddHandlersCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('vlr.model.violation.handler') as $id => $tags) {
            $priority = isset($tags[0]) && isset($tags[0]['priority']) ? $tags[0]['priority'] : null;

            $container
                ->getDefinition('rezzza.violation.handler.manager')
                ->addMethodCall('add', array($container->getDefinition($id), $priority));
        }
    }
}
