Usage
=====

You want to add the violation logger to your entity `Pizza`, you want to log violations on this model.

Add an interface to your Pizzza model.


```php
<?php

namespace Acme\DemoBundle\Entity;

use Rezzza\ModelViolationLoggerBundle\Model\ViolationLoggerInterface;
use Acme\DemoBundle\Entity\Violation\PizzaLogger as ViolationLogger;

class Pizza implements ViolationLoggerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getViolationLogger()
    {
        // Acme\DemoBundle\Entity\Violation is the path to the entity overrided (on step 1: installation)
        // In this case, it's useful, because we will use a Generic logger, but you are not forced to define it.
        return new ViolationLogger('Acme\DemoBundle\Entity\Violation');
    }
}
```

So now, define your violation logger !

```php
<?php

namespace Acme\DemoBundle\Entity\Violation;

use Rezzza\ModelViolationLoggerBundle\Violation\Validator\SymfonyValidatorLogger;
use Rezzza\ModelViolationLoggerBundle\Model\ViolationLoggerInterface as ModelViolationLoggerInterface;
use Rezzza\ModelViolationLoggerBundle\Violation;

class PizzaLogger extends SymfonyValidatorLogger
{
    /**
     * @{inheritdoc}
     */
    public function validate(ModelViolationLoggerInterface $object, Violation\ViolationList $violationList)
    {
        // becase we inherits from SymfonyValidatorLogger, it'll validate your object with symfony validator
        // and log violations or fix them.
        $violationList = parent::validate($object, $violationList);

        // here you can add custom code ...
        $violation = count($violationList) > 0;
        if ($violation !== $object->getIsViolation()) {
            $object->setIsViolation($violation);
            // persist, flush
        }
    }


}
```

If you want to add yor custom Logger, it looks like:
```php
<?php

namespace Acme\DemoBundle\Entity\Violation;

use Rezzza\ModelViolationLoggerBundle\Violation\AbstractViolationLogger;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationLoggerInterface;
use Rezzza\ModelViolationLoggerBundle\Model\ViolationLoggerInterface as ModelViolationLoggerInterface;
use Rezzza\ModelViolationLoggerBundle\Violation;

class PizzaLogger extends AbstractViolationLogger implements ViolationLoggerInterface
{
    /**
     * @{inheritdoc}
     */
    public function validate(ModelViolationLoggerInterface $object, Violation\ViolationList $violationList)
    {
        $violation = new \Path\To\Violation();
        $violation->setCode('...');
        $violation->setMessage('...');
        $violation->setMessageParameters('...');
        $violation->setCreatedAt(new \DateTime());

        $violationList->add($violation);
        //etc...
    }
}
