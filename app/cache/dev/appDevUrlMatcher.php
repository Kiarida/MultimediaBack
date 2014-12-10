<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appDevUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appDevUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);

        if (0 === strpos($pathinfo, '/_')) {
            // _wdt
            if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_wdt')), array (  '_controller' => 'web_profiler.controller.profiler:toolbarAction',));
            }

            if (0 === strpos($pathinfo, '/_profiler')) {
                // _profiler_home
                if (rtrim($pathinfo, '/') === '/_profiler') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_profiler_home');
                    }

                    return array (  '_controller' => 'web_profiler.controller.profiler:homeAction',  '_route' => '_profiler_home',);
                }

                if (0 === strpos($pathinfo, '/_profiler/search')) {
                    // _profiler_search
                    if ($pathinfo === '/_profiler/search') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchAction',  '_route' => '_profiler_search',);
                    }

                    // _profiler_search_bar
                    if ($pathinfo === '/_profiler/search_bar') {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchBarAction',  '_route' => '_profiler_search_bar',);
                    }

                }

                // _profiler_purge
                if ($pathinfo === '/_profiler/purge') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:purgeAction',  '_route' => '_profiler_purge',);
                }

                // _profiler_info
                if (0 === strpos($pathinfo, '/_profiler/info') && preg_match('#^/_profiler/info/(?P<about>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_info')), array (  '_controller' => 'web_profiler.controller.profiler:infoAction',));
                }

                // _profiler_phpinfo
                if ($pathinfo === '/_profiler/phpinfo') {
                    return array (  '_controller' => 'web_profiler.controller.profiler:phpinfoAction',  '_route' => '_profiler_phpinfo',);
                }

                // _profiler_search_results
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/search/results$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_search_results')), array (  '_controller' => 'web_profiler.controller.profiler:searchResultsAction',));
                }

                // _profiler
                if (preg_match('#^/_profiler/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler')), array (  '_controller' => 'web_profiler.controller.profiler:panelAction',));
                }

                // _profiler_router
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/router$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_router')), array (  '_controller' => 'web_profiler.controller.router:panelAction',));
                }

                // _profiler_exception
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception')), array (  '_controller' => 'web_profiler.controller.exception:showAction',));
                }

                // _profiler_exception_css
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception\\.css$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception_css')), array (  '_controller' => 'web_profiler.controller.exception:cssAction',));
                }

            }

            if (0 === strpos($pathinfo, '/_configurator')) {
                // _configurator_home
                if (rtrim($pathinfo, '/') === '/_configurator') {
                    if (substr($pathinfo, -1) !== '/') {
                        return $this->redirect($pathinfo.'/', '_configurator_home');
                    }

                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::checkAction',  '_route' => '_configurator_home',);
                }

                // _configurator_step
                if (0 === strpos($pathinfo, '/_configurator/step') && preg_match('#^/_configurator/step/(?P<index>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_configurator_step')), array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::stepAction',));
                }

                // _configurator_final
                if ($pathinfo === '/_configurator/final') {
                    return array (  '_controller' => 'Sensio\\Bundle\\DistributionBundle\\Controller\\ConfiguratorController::finalAction',  '_route' => '_configurator_final',);
                }

            }

        }

        // nelmio_api_doc_index
        if (rtrim($pathinfo, '/') === '') {
            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                $allow = array_merge($allow, array('GET', 'HEAD'));
                goto not_nelmio_api_doc_index;
            }

            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'nelmio_api_doc_index');
            }

            return array (  '_controller' => 'Nelmio\\ApiDocBundle\\Controller\\ApiDocController::indexAction',  '_route' => 'nelmio_api_doc_index',);
        }
        not_nelmio_api_doc_index:

        if (0 === strpos($pathinfo, '/security/token')) {
            // byexample_demo_securityrest_get_token_destroy
            if (0 === strpos($pathinfo, '/security/token/destroy') && preg_match('#^/security/token/destroy(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_byexample_demo_securityrest_get_token_destroy;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_securityrest_get_token_destroy')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\SecurityController::getTokenDestroyAction',  '_format' => NULL,));
            }
            not_byexample_demo_securityrest_get_token_destroy:

            // byexample_demo_securityrest_post_token_create
            if (0 === strpos($pathinfo, '/security/token/create') && preg_match('#^/security/token/create(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_byexample_demo_securityrest_post_token_create;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_securityrest_post_token_create')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\SecurityController::postTokenCreateAction',  '_format' => NULL,));
            }
            not_byexample_demo_securityrest_post_token_create:

        }

        if (0 === strpos($pathinfo, '/api/users')) {
            // byexample_demo_userrest_delete_user
            if (preg_match('#^/api/users/(?P<slug>[^/\\.]++)(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'DELETE') {
                    $allow[] = 'DELETE';
                    goto not_byexample_demo_userrest_delete_user;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_userrest_delete_user')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::deleteUserAction',  '_format' => NULL,));
            }
            not_byexample_demo_userrest_delete_user:

            // byexample_demo_userrest_get_user
            if (preg_match('#^/api/users/(?P<slug>[^/\\.]++)(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_byexample_demo_userrest_get_user;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_userrest_get_user')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::getUserAction',  '_format' => NULL,));
            }
            not_byexample_demo_userrest_get_user:

            // byexample_demo_userrest_get_users
            if (preg_match('#^/api/users(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_byexample_demo_userrest_get_users;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_userrest_get_users')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::getUsersAction',  '_format' => NULL,));
            }
            not_byexample_demo_userrest_get_users:

            // byexample_demo_userrest_post_users
            if (preg_match('#^/api/users(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_byexample_demo_userrest_post_users;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_userrest_post_users')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::postUsersAction',  '_format' => NULL,));
            }
            not_byexample_demo_userrest_post_users:

            // byexample_demo_userrest_put_user
            if (preg_match('#^/api/users/(?P<slug>[^/\\.]++)(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'PUT') {
                    $allow[] = 'PUT';
                    goto not_byexample_demo_userrest_put_user;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_userrest_put_user')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::putUserAction',  '_format' => NULL,));
            }
            not_byexample_demo_userrest_put_user:

            // byexample_demo_userrolrest_delete_user_role
            if (preg_match('#^/api/users/(?P<slug>[^/]++)/roles/(?P<id>[^/\\.]++)(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'DELETE') {
                    $allow[] = 'DELETE';
                    goto not_byexample_demo_userrolrest_delete_user_role;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_userrolrest_delete_user_role')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRoleRestController::deleteRoleAction',  '_format' => NULL,));
            }
            not_byexample_demo_userrolrest_delete_user_role:

            // byexample_demo_userrolrest_get_user_roles
            if (preg_match('#^/api/users/(?P<slug>[^/]++)/roles(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_byexample_demo_userrolrest_get_user_roles;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_userrolrest_get_user_roles')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRoleRestController::getRolesAction',  '_format' => NULL,));
            }
            not_byexample_demo_userrolrest_get_user_roles:

            // byexample_demo_userrolrest_post_user_roles
            if (preg_match('#^/api/users/(?P<slug>[^/]++)/roles(?:\\.(?P<_format>json|xml|html))?$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_byexample_demo_userrolrest_post_user_roles;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'byexample_demo_userrolrest_post_user_roles')), array (  '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRoleRestController::postRolesAction',  '_format' => NULL,));
            }
            not_byexample_demo_userrolrest_post_user_roles:

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
