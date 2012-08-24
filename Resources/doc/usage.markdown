Usage
=====

You want to add the violation logger to your entity `Pizza`, you want to log violations on this model.

Create a service with tag like that:
```xml
<service id="acme.pizza.violation.handler" class="\path\to\PizzaLogger">
    <argument type="service" id="service_container" />
    <tag name="vlr.model.violation.handler" />
</service>
```

Then create the handler

So now, define your violation logger !

```php
<?php

namespace Acme\DemoBundle\Entity\Violation;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Rezzza\ModelViolationLoggerBundle\Handler\ViolationHandlerInterface;
use Rezzza\ModelViolationLoggerBundle\Violation\ViolationList;

class PizzaLogger implements ViolationHandlerInterface
{
    /*---- define container is optional, you can inject service manually ----*/
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @{inheritdoc}
     */
    public function validate($object, ViolationList $violationList)
    {
        // With theses 2 lines bellow, we'll log violations from symfony validation.
        $violationList = $this->container->get('rezzza.violation.symfony_validation.handler')
            ->validate($object, $violationList);

        // here you can add custom code ...
        $violation = count($violationList) > 0;
        if ($violation !== $object->getIsViolation()) {
            $object->setIsViolation($violation);
            // persist, flush
        }

        // here we'll add custom violations
        $violation = new \Path\To\Entity\Violation();
        $violation->setCode('...');
        $violation->setMessage('...');
        $violation->setMessageParameters('...');
        $violation->setCreatedAt(new \DateTime());

        $violationList->add($violation);

        return $violationList;
    }
}
```
