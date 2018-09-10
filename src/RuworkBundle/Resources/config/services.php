<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ruwork\RuworkBundle\Asset\VersionStrategy\FilemtimeStrategy;
use Ruwork\RuworkBundle\Doctrine\NamingStrategy\RuworkNamingStrategy;
use Ruwork\RuworkBundle\EventListener\RedirectAnnotationListener;
use Ruwork\RuworkBundle\ExpressionLanguage\RedirectTargetExpressionLanguage;
use Ruwork\RuworkBundle\Mailer\Mailer;
use Ruwork\RuworkBundle\Serializer\Encoder\ExcelCsvEncoder;
use Ruwork\RuworkBundle\Validator\Constraints\AliasValidator;
use Ruwork\RuworkBundle\Validator\Constraints\ConditionValidator;

return function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->defaults()
        ->private();

    // Asset

    $services->set(FilemtimeStrategy::class);

    // Doctrine

    $services->set(RuworkNamingStrategy::class);

    // EventListener

    $services
        ->set(RedirectAnnotationListener::class)
        ->args([
            '$conditionLanguage' => ref('sensio_framework_extra.security.expression_language.default'),
            '$targetLanguage' => ref(RedirectTargetExpressionLanguage::class),
            '$authChecker' => ref('security.authorization_checker'),
            '$tokenStorage' => ref('security.token_storage'),
            '$urlGenerator' => ref('router'),
        ])
        ->tag('kernel.event_subscriber');

    // ExpressionLanguage

    $services->set(RedirectTargetExpressionLanguage::class);

    // Mailer

    $services
        ->set(Mailer::class)
        ->args([
            '$twig' => ref('twig'),
            '$swift' => ref('mailer'),
        ])
        ->deprecate('The "%service_id%" service is deprecated since RuworkBundle 0.11.1 and will be removed in 0.12.0.');

    // Serializer

    $services
        ->set(ExcelCsvEncoder::class)
        ->args([
            '$csvEncoder' => ref('serializer.encoder.csv'),
        ])
        ->tag('serializer.encoder');

    // Validator

    $services
        ->set(AliasValidator::class)
        ->args([
            '$managerRegistry' => ref('doctrine'),
        ])
        ->tag('validator.constraint_validator');

    $services
        ->set(ConditionValidator::class)
        ->tag('validator.constraint_validator');
};
