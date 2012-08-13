# Installation

# Step 1, Download it

## Via composer

Add this to your composer.json

```
"rezzza/model-violaton-logger-bundle": "dev-master"
```

Then

```
php composer.phar update "rezzza/model-violation-logger-bundle" # or install
```

# Step 2: Enable the bundle

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Rezzza\ModelViolationLoggerBundle\RezzzaModelViolationLoggerBundle(),
    );
}
```

# Step 3: (If you use Doctrine db_driver) Define your Violation class

```php
<?php
//Acme/YourBundle/Entity/Violation
use Rezzza\ModelViolationLoggerBundle\Entity\Violation as BaseViolation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="violation")
 */
class Violation extends BaseViolation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
```

Don't forget to define it on `config.yml`

```
rezzza_model_violation_logger:
    storage:              orm
    violation_class:      Acme\DemoBundle\Entity\Violation
```

Then, look at full configuration on [index](https://github.com/rezzza/ModelViolationLoggerBundle/blob/master/README.md)
