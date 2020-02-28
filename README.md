# Doctrine Yield

A trait to have methods allowed you to yield Doctrine result.  

Note: Tested on Doctrine `2.6` only.  
But if semver is respected, it should be work on all 2.x version.

# Install it

`composer require bulton-fr/doctrine_yield`

# How use it

In your Repository class, add the trait like this :

```php
<?php

namespace MyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use BultonFr\DoctrineYield\RepositoryYieldTrait;

class MyEntityRepository extends EntityRepository
{
    use RepositoryYieldTrait;
}
```

And where you want obtain datas (a controller for this example) :

```php
<?php

namespace MyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ExampleController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Generator $allData */
        $allData = $em
            ->getRepository('MyBundle:MyEntity')
            ->yieldAll()
        ;

        //[...]
    }
}
```

# How it's work

In the trait, you have the method `yieldAll`, it call `yieldQuery` with the `QueryBuilder` in argument.  
We obtain the `Query` instance from the `QueryBuilder` and call `Query::iterate` to loop on each row, and yield each row.  
`iterate` do a `fetchRow` to obtain the next line. So we NOT get all rows to loop and yield on it.  
To avoid an override memory from Doctrine, we "detach" each yielded row from the EntityManager.
If you don't want detach the row, you can define the argument `$detachRows` to `false`.
On `yieldAll` method, it's the first method's argument.

# Yield with custom queries

You can use the same way of `yieldAll` method to yield with a custom query.
At the end of your custom query, instead of return something,
you need to do `yield from $this->yieldQuery($qb);`.  
Also don't forget to include the trait in your repository.
