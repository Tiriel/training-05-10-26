<?php

namespace App\EventListener;

use App\Parser\ConferenceApiParser;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(KernelEvents::VIEW)]
final class ConferenceApiListener
{
    public function __construct(
        private readonly ConferenceApiParser $apiParser,
    ) {}

    public function __invoke(ViewEvent $event): void
    {
        $request = $event->getRequest();
        if ('app_conference_search' !== $request->attributes->get('_route')) {
            return;
        }

        $result = $event->getControllerResult();
        $result['conferences'] = $this->apiParser->parseApiResults($result['conferences']);

        $event->setControllerResult($result);
    }
}
