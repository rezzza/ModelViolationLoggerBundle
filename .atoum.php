<?php

use \mageekguy\atoum;

$cliReport = $script->addDefaultReport();
$cliReport->addField(new atoum\report\fields\runner\result\logo());
$runner->addReport($cliReport);

$script->bootstrapFile(__DIR__ . DIRECTORY_SEPARATOR . '.atoum.bootstrap.php');

$runner->addTestsFromDirectory(__DIR__.'/Tests/Units');
