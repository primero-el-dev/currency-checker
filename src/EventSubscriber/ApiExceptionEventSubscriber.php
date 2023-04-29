<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\FirewallMapInterface;

class ApiExceptionEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private FirewallMapInterface $firewallMap) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'handleException',
        ];
    }

    public function handleException(ExceptionEvent $event): void
    {
        if ($this->getFirewallName($event) !== 'api') {
            return;
        }
        
        $exception = $event->getThrowable();
        // We don't want to disclose internal server error on production unless it's an HTTP exception
        $message = ($_ENV['APP_ENV'] === 'prod' && !$exception instanceof HttmlException) 
            ? 'Something has gone wrong'
            : $exception->getMessage();
        $statusCode = ($exception instanceof HttpExceptionInterface) 
            ? $exception->getStatusCode() 
            : 500;
        $headers = $event->getResponse()?->headers->all() ?? [];
        
        $event->setResponse(new JsonResponse(['error' => $message], $statusCode, $headers));
    }

    private function getFirewallName(RequestEvent $event): ?string
    {
        $firewallConfig = $this->firewallMap->getFirewallConfig($event->getRequest());
        
        return $firewallConfig?->getName();
    }
}