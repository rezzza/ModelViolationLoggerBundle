<?php

namespace Rezzza\ModelViolationLoggerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rezzza\ModelViolationLoggerBundle\DependencyInjection\Compiler\AddLoggersCompilerPass;

/**
 * RezzzaModelViolationLoggerBundle
 *
 * @uses Bundle
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class RezzzaModelViolationLoggerBundle extends Bundle
{
    /**
     * @{inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddLoggersCompilerPass());
    }

}
