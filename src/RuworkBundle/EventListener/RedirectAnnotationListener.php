<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\EventListener;

use Ruwork\RuworkBundle\ControllerAnnotations\Redirect;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class RedirectAnnotationListener implements EventSubscriberInterface
{
    private $conditionLanguage;
    private $targetLanguage;
    private $authChecker;
    private $tokenStorage;
    private $urlGenerator;

    public function __construct(
        ExpressionLanguage $conditionLanguage,
        ExpressionLanguage $targetLanguage,
        AuthorizationCheckerInterface $authChecker,
        TokenStorageInterface $tokenStorage,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->conditionLanguage = $conditionLanguage;
        $this->targetLanguage = $targetLanguage;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            // right after the FrameworkExtraBundle listener prepares the annotations
            KernelEvents::CONTROLLER => ['onKernelController', -1],
        ];
    }

    public function onKernelController(FilterControllerEvent $event): void
    {
        // redirects don't make sense in sub-requests
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        $redirects = $request->attributes->get('_'.Redirect::NAME, []);

        foreach ($redirects as $redirect) {
            if (!$redirect instanceof Redirect) {
                continue;
            }

            $condition = $this->conditionLanguage->evaluate(
                $redirect->getCondition(),
                $this->getConditionVars($request)
            );

            if (!$condition) {
                continue;
            }

            $url = $this->getUrl($redirect, $request);
            $response = new RedirectResponse($url, $redirect->getStatus());

            $event->setController(static function () use ($response) {
                return $response;
            });

            $event->stopPropagation();

            return;
        }
    }

    private function getUrl(Redirect $redirect, Request $request): string
    {
        if (null !== $target = $redirect->getTarget()) {
            return $this->urlGenerator->generate($target[0], $target[1] ?? []);
        }

        if (null !== $expression = $redirect->getTargetExpression()) {
            return $this->targetLanguage->evaluate($expression, $this->getTargetVars($request));
        }

        throw new \RuntimeException('Redirect target is not defined.');
    }

    private function getConditionVars(Request $request): array
    {
        return array_merge($request->attributes->all(), [
            'request' => $request,
            'object' => $request,
            'user' => $this->tokenStorage->getToken()->getUser(),
            'auth_checker' => $this->authChecker,
        ]);
    }

    private function getTargetVars(Request $request): array
    {
        return array_merge($request->attributes->all(), [
            'request' => $request,
            'url_generator' => $this->urlGenerator,
        ]);
    }
}
