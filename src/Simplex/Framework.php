<?php

namespace Simplex;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing;

class Framework
{
    public function __construct(
        private UrlMatcherInterface $matcher,
        private ControllerResolverInterface $resolver,
        private ArgumentResolverInterface $argumentResolver,
    ) {
    }

    public function handle(Request $request):Response
    {

        $this->matcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->resolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            return call_user_func_array($controller, $arguments);
        } catch (Routing\Exception\ResourceNotFoundException $exception) {
            return new Response('Not Found',404);
        } catch (Exception $exception) {
            return new Response('An error occurred', 500);
        }
    }



}