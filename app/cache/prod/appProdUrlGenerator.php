<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Psr\Log\LoggerInterface;

/**
 * appProdUrlGenerator
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlGenerator extends Symfony\Component\Routing\Generator\UrlGenerator
{
    private static $declaredRoutes = array(
        'nelmio_api_doc_index' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'Nelmio\\ApiDocBundle\\Controller\\ApiDocController::indexAction',  ),  2 =>   array (    '_method' => 'GET',  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/',    ),  ),  4 =>   array (  ),),
        'byexample_demo_securityrest_get_token_destroy' => array (  0 =>   array (    0 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\SecurityController::getTokenDestroyAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'GET',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'text',      1 => '/security/token/destroy',    ),  ),  4 =>   array (  ),),
        'byexample_demo_securityrest_post_token_create' => array (  0 =>   array (    0 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\SecurityController::postTokenCreateAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'POST',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'text',      1 => '/security/token/create',    ),  ),  4 =>   array (  ),),
        'byexample_demo_userrest_delete_user' => array (  0 =>   array (    0 => 'slug',    1 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::deleteUserAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'DELETE',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/\\.]++',      3 => 'slug',    ),    2 =>     array (      0 => 'text',      1 => '/api/users',    ),  ),  4 =>   array (  ),),
        'byexample_demo_userrest_get_user' => array (  0 =>   array (    0 => 'slug',    1 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::getUserAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'GET',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/\\.]++',      3 => 'slug',    ),    2 =>     array (      0 => 'text',      1 => '/api/users',    ),  ),  4 =>   array (  ),),
        'byexample_demo_userrest_get_users' => array (  0 =>   array (    0 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::getUsersAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'GET',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'text',      1 => '/api/users',    ),  ),  4 =>   array (  ),),
        'byexample_demo_userrest_post_users' => array (  0 =>   array (    0 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::postUsersAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'POST',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'text',      1 => '/api/users',    ),  ),  4 =>   array (  ),),
        'byexample_demo_userrest_put_user' => array (  0 =>   array (    0 => 'slug',    1 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRestController::putUserAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'PUT',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/\\.]++',      3 => 'slug',    ),    2 =>     array (      0 => 'text',      1 => '/api/users',    ),  ),  4 =>   array (  ),),
        'byexample_demo_userrolrest_delete_user_role' => array (  0 =>   array (    0 => 'slug',    1 => 'id',    2 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRoleRestController::deleteRoleAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'DELETE',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/\\.]++',      3 => 'id',    ),    2 =>     array (      0 => 'text',      1 => '/roles',    ),    3 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/]++',      3 => 'slug',    ),    4 =>     array (      0 => 'text',      1 => '/api/users',    ),  ),  4 =>   array (  ),),
        'byexample_demo_userrolrest_get_user_roles' => array (  0 =>   array (    0 => 'slug',    1 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRoleRestController::getRolesAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'GET',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'text',      1 => '/roles',    ),    2 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/]++',      3 => 'slug',    ),    3 =>     array (      0 => 'text',      1 => '/api/users',    ),  ),  4 =>   array (  ),),
        'byexample_demo_userrolrest_post_user_roles' => array (  0 =>   array (    0 => 'slug',    1 => '_format',  ),  1 =>   array (    '_controller' => 'ByExample\\DemoBundle\\Controller\\UserRoleRestController::postRolesAction',    '_format' => NULL,  ),  2 =>   array (    '_method' => 'POST',    '_format' => 'json|xml|html',  ),  3 =>   array (    0 =>     array (      0 => 'variable',      1 => '.',      2 => 'json|xml|html',      3 => '_format',    ),    1 =>     array (      0 => 'text',      1 => '/roles',    ),    2 =>     array (      0 => 'variable',      1 => '/',      2 => '[^/]++',      3 => 'slug',    ),    3 =>     array (      0 => 'text',      1 => '/api/users',    ),  ),  4 =>   array (  ),),
    );

    /**
     * Constructor.
     */
    public function __construct(RequestContext $context, LoggerInterface $logger = null)
    {
        $this->context = $context;
        $this->logger = $logger;
    }

    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        if (!isset(self::$declaredRoutes[$name])) {
            throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $name));
        }

        list($variables, $defaults, $requirements, $tokens, $hostTokens) = self::$declaredRoutes[$name];

        return $this->doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens);
    }
}
