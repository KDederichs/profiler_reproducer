<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\Exception\RateLimitExceededException;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class RateLimitListener
{
    private RateLimiterFactory $factory;


    public function __construct(RateLimiterFactory $testLimiter)
    {
        $this->factory = $testLimiter;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $limiter = $this->factory->create($request->getClientIp());


        $limit = $limiter->consume();
        if (false === $limit->isAccepted()) {
            throw new RateLimitExceededException($limit);
        }
    }
}
