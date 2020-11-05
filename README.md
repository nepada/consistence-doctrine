Consistence Enum Doctrine types
===============================

[![Build Status](https://github.com/nepada/consistence-doctrine/workflows/CI/badge.svg)](https://github.com/nepada/consistence-doctrine/actions?query=workflow%3ACI+branch%3Amaster)
[![Coverage Status](https://coveralls.io/repos/github/nepada/consistence-doctrine/badge.svg?branch=master)](https://coveralls.io/github/nepada/consistence-doctrine?branch=master)
[![Downloads this Month](https://img.shields.io/packagist/dm/nepada/consistence-doctrine.svg)](https://packagist.org/packages/nepada/consistence-doctrine)
[![Latest stable](https://img.shields.io/packagist/v/nepada/consistence-doctrine.svg)](https://packagist.org/packages/nepada/consistence-doctrine)


Installation
------------

Via Composer:

```sh
$ composer require nepada/consistence-doctrine
```
  

Usage
-----

### Define Doctrine type for the enum
 
```php
/**
 * @phpstan-extends \Nepada\ConsistenceDoctrine\StringEnumType<\FooEnum>
 */
class FooEnumType extends \Nepada\ConsistenceDoctrine\StringEnumType
{

    protected function getEnumClassName(): string
    {
        return \FooEnum::class;
    }

}
```

You can choose to inherit from `StringEnumType`, `IntegerEnumType`, `FloatEnumType` or `BooleanEnumType` depending on the enum values.

### Register the created type in Doctrine

``` php
\Doctrine\DBAL\Types\Type::addType(\FooEnum::class, \FooEnumType::class);
```

In Nette with [nettrine/dbal](https://github.com/nettrine/dbal) integration, you can register the types in your configuration:
```yaml
dbal:
    connection:
        types:
            FooEnum: FooEnumType
```

### Use the type in entity

``` php
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class SomeEntity
{

    /** @ORM\Column(type=\FooEnum::class, nullable=false) */
    private \FooEnum $foo;

    // ...

}
```

### Use enum in query builder

```php
$result = $repository->createQueryBuilder('bar')
    ->select('bar.foo') // FooEnum instances are created during hydratation
    ->where('bar.foo = :fooEnum')
    ->setParameter('fooEnum', \FooEnum::get(\FooEnum::VALUE), \FooEnum::class) // enum instance gets serialized
    ->getQuery()
    ->setMaxResults(1)
    ->getSingleResult();
```

### PHPStan support (via `phpstan/phpstan-doctrine`)

The abstract enum type classes are anotated as PHPStan generics and define proper typehints for their `convert*` methods. This means you can teach PHPStan your custom enum types via `ReflectionDescriptor`:

```yaml
services:
    -
        factory: PHPStan\Type\Doctrine\Descriptors\ReflectionDescriptor(FooEnumType)
        tags: [phpstan.doctrine.typeDescriptor]
```  


Differences from the official `consistence/consistence-doctrine`
----------------------------------------------------------------

The official integration `consistence/consistence-doctrine` uses postload entity event to convert data stored in database to enum instances.

The main advantage of that approach is that you don't need to create and register new doctrine type for every enum.
  
The disadvantages are:
- You can't use native property typehints for entity enum attributes.
- When you're not hydrating entites, you will get enum values instead of enum instances.
- There is no easy way how to make PHPStan understand and check doctrine and PHP types of your enum fields. 
