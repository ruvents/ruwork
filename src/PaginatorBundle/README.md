# Ruwork Paginator Bundle

Bundle to use [RUWORK Paginator](https://github.com/ruwork/paginator) with Symfony.

Replaces the `Ruwork\Paginator\Exception\PageOutOfRangeException` with a `Symfony\Component\HttpKernel\Exception\NotFoundHttpException`.

Registers a twig path alias for the library twig templates.

```twig
{% embed '@RuworkPaginator/bootstrap_4.html.twig' with {paginator: paginator} %}
{% endembed %}
```
