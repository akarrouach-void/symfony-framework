<?php

namespace Simplex\Tests;

use Calendar\Controller\LeapYearController;
use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FrameworkTest extends TestCase
{
    public function testNotFoundHandling(): void
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $response = $framework->handle(new Request());

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testErrorHandling(): void
    {
        $framework = $this->getFrameworkForException(new \RuntimeException());

        $response = $framework->handle(new Request());

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testControllerResponse(): void
    {
        $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);
        $context = $this->createMock(Routing\RequestContext::class);

        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->willReturn($context);

        $context
            ->expects($this->once())
            ->method('fromRequest')
            ->willReturn($context);

        $matcher
            ->expects($this->once())
            ->method('match')
            ->willReturn([
                '_route' => 'is_leap_year',
                'year' => '2000',
                '_controller' => [new LeapYearController(), 'index'],
            ]);

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $framework = new Framework($matcher, $controllerResolver, $argumentResolver);

        $response = $framework->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Yep, this is a leap year!', $response->getContent());
    }

    private function getFrameworkForException($exception): Framework
    {
        $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);
        $context = $this->createMock(Routing\RequestContext::class);

        $matcher->expects($this->once())->method('getContext')->willReturn($context);

        $context->expects($this->once())->method('fromRequest')->willReturn($context);

        $matcher->expects($this->once())->method('match')->willThrowException($exception);

        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($matcher, $controllerResolver, $argumentResolver);
    }
}