<?php

namespace Simplex;

use Symfony\Component\HttpKernel\HttpKernel;

class Framework extends HttpKernel
{
}


//use Symfony\Component\EventDispatcher\EventDispatcher;
//use Symfony\Component\HttpFoundation\Request;
//use Exception;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
//use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
//use Symfony\Component\HttpKernel\HttpKernelInterface;
//use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
//use Symfony\Component\Routing;
//
//class Framework implements HttpKernelInterface
//{
//    public function __construct(
//        private EventDispatcher $dispatcher,
//        private UrlMatcherInterface $matcher,
//        private ControllerResolverInterface $resolver,
//        private ArgumentResolverInterface $argumentResolver,
//    ) {
//    }
//
//    public function handle(
//        Request $request,
//       int $type = HttpKernelInterface::MAIN_REQUEST,
//       bool $catch = true) : Response
//    {
//        $this->matcher->getContext()->fromRequest($request);
//
//        try {
//            $request->attributes->add($this->matcher->match($request->getPathInfo()));
//
//            $controller = $this->resolver->getController($request);
//            $arguments = $this->argumentResolver->getArguments($request, $controller);
//
//            $response =  call_user_func_array($controller, $arguments);
//        } catch (Routing\Exception\ResourceNotFoundException $exception) {
//            $response =  new Response('Not Found',404);
//        } catch (Exception $exception) {
//            $response =  new Response('An error occurred', 500);
//        }
//
//        $this->dispatcher->dispatch(new ResponseEvent($response, $request), 'response');
//        return $response;
//    }
//}