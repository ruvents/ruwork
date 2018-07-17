<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class RedirectTargetExpressionLanguage extends ExpressionLanguage
{
    /**
     * {@inheritdoc}
     */
    protected function registerFunctions()
    {
        parent::registerFunctions();

        $this->register('path', function (string $route, array $parameters = []): string {
            return \sprintf('$url_generator->generate(%s, %s)', $route, $parameters);
        }, function (array $variables, string $route, array $parameters = []): string {
            return $variables['url_generator']->generate($route, $parameters);
        });
    }
}
