<?php

namespace EnhancedProxya0301330_4894b995b67fb05b60fdcbe09f8f6827f11a0f1b\__CG__\ByExample\DemoBundle\Controller;

/**
 * CG library enhanced proxy class.
 *
 * This code was generated automatically by the CG library, manual changes to it
 * will be lost upon next generation.
 */
class UserRoleRestController extends \EnhancedProxya0301330_4828730ea97f44e5d33f42ad46418158bf1ded0f\__CG__\ByExample\DemoBundle\Controller\UserRoleRestController
{
    private $__CGInterception__loader;

    public function postRolesAction($slug)
    {
        $ref = new \ReflectionMethod('ByExample\\DemoBundle\\Controller\\UserRoleRestController', 'postRolesAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($slug));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($slug), $interceptors);

        return $invocation->proceed();
    }

    public function getRolesAction($slug)
    {
        $ref = new \ReflectionMethod('ByExample\\DemoBundle\\Controller\\UserRoleRestController', 'getRolesAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($slug));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($slug), $interceptors);

        return $invocation->proceed();
    }

    public function deleteRoleAction($slug, $id)
    {
        $ref = new \ReflectionMethod('ByExample\\DemoBundle\\Controller\\UserRoleRestController', 'deleteRoleAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($slug, $id));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($slug, $id), $interceptors);

        return $invocation->proceed();
    }

    public function __CGInterception__setLoader(\CG\Proxy\InterceptorLoaderInterface $loader)
    {
        $this->__CGInterception__loader = $loader;
    }
}