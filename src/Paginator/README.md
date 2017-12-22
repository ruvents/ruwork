# Ruwork Paginator

## Description

This library provides a convenient way to paginate any data structure.

Some definitions:
- A `section` is a set of pages, rendered together.
- `Proximity` is a number of pages, displayed before and after the current page.

For example, for 10 pages with proximity 2 and current page 5 paginator will have 3 sections with the following page numbers: `[1], [3, 4, 5, 6, 7], [10]`.

When the current page is close to one of the edges sections get merged.


## Controller code example

```php
<?php

use Ruwork\Paginator\PaginatorBuilder;
use Ruwork\Paginator\Provider\IterableProvider;

$data = range(1, 100);

$paginator = PaginatorBuilder::create()
    // required
    ->setProvider(new IterableProvider($data))
    // defaults to 1
    // when out of range of estimated pages, PageOutOfRangeException is thrown
    ->setCurrent(2)
    // defaults to 2
    ->setProximity(1)
    // defaults to 10
    ->setPerPage(3)
    ->getPaginator();

// template logic

foreach ($paginator->getItems() as $item) {
    // render the item row
}

if (null !== $previous = $paginator->getPrevious()) {
    echo sprintf('<a href="?page=%d">Previous</a>', $previous->getNumber());
}

foreach ($paginator as $section) {
    foreach ($section as $page) {
        echo sprintf('<a href="?page=%1$d" class="%2$s">%1$d</a>', $page->getNumber(), $page->isCurrent() ? 'active' : '');
    }
}

if (null !== $next = $paginator->getNext()) {
    echo sprintf('<a href="?page=%d">Next</a>', $next->getNumber());
}
```

## Data providers

### IterableProvider

Can be used with an `array` or an object implementing `\Traversable`.

### DoctrineOrmProvider

Can be used to paginate over Doctrine entities. Internally uses the native `Doctrine\ORM\Tools\Pagination\Paginator` helper.

```php
<?php

use Ruwork\Paginator\PaginatorBuilder;
use Ruwork\Paginator\Provider\DoctrineOrmProvider;
use Doctrine\ORM\EntityRepository;

/** @var EntityRepository $repository */

$qb = $repository->createQueryBuilder('entity')
    ->andWhere('entity.id = :id')
    ->setParameters([
        'id' => 1
    ]);

$paginator = PaginatorBuilder::create()
    ->setProvider(new DoctrineOrmProvider($qb))
    ->getPaginator();
```

### Custom

Create your own provider by implementing the `Ruwork\Paginator\Provider\ProviderInterface`.

### Templates

## bootstrap_4.html.twig

```twig
{% set route = app.request.attributes.get('_route') %}
{% set route_params = app.request.attributes.get('_route_params', []) %}

{% embed 'twig/bootstrap_4.html.twig' with {paginator: paginator, show_previous_next: false} %}
    {% block href path(route, route_params|merge({page: page.first ? null : page.number})) %}

    {% block previous_label 'Предыдущая' %}

    {% block next_label 'Следующая' %}
{% endembed %}
```
