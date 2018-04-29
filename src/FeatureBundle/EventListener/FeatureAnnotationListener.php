<?php

declare(strict_types=1);

namespace Ruwork\FeatureBundle\EventListener;

use Ruwork\FeatureBundle\Annotation\Feature;
use Ruwork\FeatureBundle\FeatureCheckerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class FeatureAnnotationListener implements EventSubscriberInterface
{
    private $checker;

    public function __construct(FeatureCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => ['onKernelControllerArguments', -4],
        ];
    }

    public function onKernelControllerArguments(FilterControllerArgumentsEvent $event)
    {
        /** @var $features Feature[] */
        if (!$features = $event->getRequest()->attributes->get('_ruwork_feature')) {
            return;
        }

        foreach ($features as $feature) {
            $name = $feature->getName();

            if (!$this->checker->isAvailable($name)) {
                $message = $feature->getMessage() ?? sprintf('Feature "%s" must be available to access this action.', $name);

                throw new HttpException($feature->getStatusCode(), $message);
            }
        }
    }
}
