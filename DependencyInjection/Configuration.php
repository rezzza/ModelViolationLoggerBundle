<?php

namespace Rezzza\ModelViolationLoggerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 *
 * @uses ConfigurationInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $storagesSupported = array('orm');

        $tb = new TreeBuilder();
        $tb->root('rezzza_model_violation_logger')
            ->validate()
                ->ifTrue(function($v){return 'orm' === $v['storage'] && empty($v['violation_class']);})
                ->thenInvalid('The doctrine model class must be defined by using the "violation_class" key.')
            ->end()
            ->children()
                ->scalarNode('storage')
                    ->isRequired()
                    ->validate()
                        ->ifNotInArray($storagesSupported)
                        ->thenInvalid('The %s storage is not supported, supports for ('.implode(', ', $storagesSupported).')')
                    ->end()
                ->end()
                ->scalarNode('violation_class')->end()
                ->scalarNode('violation_manager')->defaultValue('rezzza.violation_manager.default')->end()
            ->end()
        ->end();

        return $tb;
    }

}
