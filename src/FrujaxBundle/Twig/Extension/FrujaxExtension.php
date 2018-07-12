<?php

declare(strict_types=1);

namespace Ruwork\FrujaxBundle\Twig\Extension;

use Ruwork\FrujaxBundle\Twig\TokenParser\FrujaxTokenParser;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FrujaxExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [
            new FrujaxTokenParser(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('data_frujax', function (Environment $env, array $config = []): string {
                $attr = 'data-frujax';

                if ([] !== $config) {
                    $attr .= '="'.twig_escape_filter($env, \json_encode($config), 'html_attr').'"';
                }

                return $attr;
            }, ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }
}
