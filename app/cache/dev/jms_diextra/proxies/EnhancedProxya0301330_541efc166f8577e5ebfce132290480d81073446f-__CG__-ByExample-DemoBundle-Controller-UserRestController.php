<?php

namespace EnhancedProxya0301330_633c881d317babd9c37cd6124d01725c641114bb\__CG__\ByExample\DemoBundle\Controller;

/**
 * CG library enhanced proxy class.
 *
 * This code was generated automatically by the CG library, manual changes to it
 * will be lost upon next generation.
 */
class UserRestController extends \EnhancedProxya0301330_541efc166f8577e5ebfce132290480d81073446f\__CG__\ByExample\DemoBundle\Controller\UserRestController
{
    private $__CGInterception__loader;

    public function putUserAction($slug)
    {
        $ref = new \ReflectionMethod('ByExample\\DemoBundle\\Controller\\UserRestController', 'putUserAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($slug));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($slug), $interceptors);

        return $invocation->proceed();
    }

    public function postUsersAction(\FOS\RestBundle\Request\ParamFetcher $paramFetcher)
    {
        $ref = new \ReflectionMethod('ByExample\\DemoBundle\\Controller\\UserRestController', 'postUsersAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($paramFetcher));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($paramFetcher), $interceptors);

        return $invocation->proceed();
    }

    public function getUsersAction()
    {
        $ref = new \ReflectionMethod('ByExample\\DemoBundle\\Controller\\UserRestController', 'getUsersAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array());
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array(), $interceptors);

        return $invocation->proceed();
    }

    public function getUserAction($slug)
    {
        $ref = new \ReflectionMethod('ByExample\\DemoBundle\\Controller\\UserRestController', 'getUserAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($slug));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($slug), $interceptors);

        return $invocation->proceed();
    }

    public function deleteUserAction($slug)
    {
        $ref = new \ReflectionMethod('ByExample\\DemoBundle\\Controller\\UserRestController', 'deleteUserAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($slug));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($slug), $interceptors);

        return $invocation->proceed();
    }

    public function __CGInterception__setLoader(\CG\Proxy\InterceptorLoaderInterface $loader)
    {
        $this->__CGInterception__loader = $loader;
    }
}