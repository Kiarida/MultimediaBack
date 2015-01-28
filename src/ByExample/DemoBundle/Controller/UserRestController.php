<?php

/**
 * This file is part of the FOSRestByExample package.
 *
 * (c) Santiago Diaz <santiago.diaz@me.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace ByExample\DemoBundle\Controller;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;                  // @ApiDoc(resource=true, description="Filter",filters={{"name"="a-filter", "dataType"="string", "pattern"="(foo|bar) ASC|DESC"}})
use FOS\RestBundle\Controller\Annotations\NamePrefix;       // NamePrefix Route annotation class @NamePrefix("bdk_core_user_userrest_")
use FOS\RestBundle\View\RouteRedirectView;                  // Route based redirect implementation
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\View\View AS FOSView;                    // Default View implementation.
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use JMS\SecurityExtraBundle\Annotation\Secure;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use ByExample\DemoBundle\Entity\Utilisateur;
use ByExample\DemoBundle\Repository\NoteRepository;

/**
 * Controller that provides Restful sercies over the resource Users.
 *
 * @NamePrefix("byexample_demo_userrest_")
 * @author Santiago Diaz <santiago.diaz@me.com>
 */
class UserRestController extends Controller
{

  


    /**
     * Returns an user by id
     *
     * @param string $id
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ApiDoc()
     * @Get("/api/users/{id}")
     */
    public function getUserAction($id)
    {
        $view = FOSView::create();
        $userManager = $this->container->get('fos_user.user_manager');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('ByExampleDemoBundle:User');
        $user = $repo->findBy(array("id" => $id));

        if ($user) {
            $view->setStatusCode(200)->setData($user);
        } else {
            $view->setStatusCode(404);
        }

        return $view;
    }

    /**
     * Test if a user has the connected rights
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ApiDoc()
     * @Get("/api/connected")
     */
    public function getConnectedUserAction()
    {
        $view = FOSView::create();
        $view->setStatusCode(200)->setData(array("connected"));
        return $view;
    }

    /**
    * Créé ou met à jour une note sur un item
     * @Put("users/{id}/note/item/{id_item}")
     * @ApiDoc()
    * @return FOSView
   */
    public function putNoteItemAction($id, $id_item){
        $view = FOSView::create();
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $note = $request->get('note');
        $repo=$em->getRepository('ByExampleDemoBundle:Note');
        $notes=$repo->putNote($id_item, $note, $id);
        
        if ($notes == 0){
            $view->setStatusCode(200);
        }elseif($notes) {
            $view->setStatusCode(201);
        } else {
            $view->setStatusCode(404);
        }
        return $view;
    }


    /**
     * Creates a new User entity.
     * Using param_fetcher_listener: force
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="username", requirements="\d+", default="", description="Username.")
     * @RequestParam(name="email", requirements="\d+", default="", description="Email.")
     * @RequestParam(name="plainPassword", requirements="\d+", default="", description="Plain Password.")
     * @RequestParam(name="role", requirements="\d+", default="", description="Role.")
     *
     * @return FOSView
     * @ApiDoc()
     * @Post("/users")
     */
    public function postUsersAction(ParamFetcher $paramFetcher)
    {
        $request = $this->getRequest();
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setPlainPassword($request->get('plainPassword'));
        $user->addRole($request->get('role'));

        $validator = $this->get('validator');
        //UTILIZAR GRUPO DE VALIDACION 'Registration' DEL FOSUserBundle
        $errors = $validator->validate($user, array('Registration'));
        if (count($errors) == 0) {
            $userManager->updateUser($user);
            $param = array("id" => $user->getId());
            $utilisateur = new Utilisateur();
            $utilisateur->setId($user->getId());
            $utilisateur->setDateinscription(new \DateTime());
            $utilisateur->setBirthdate(new \DateTime($request->get('birthyear')."/".$request->get('birthmonth')."/".$request->get('birthday')));
            $utilisateur->setGenre($request->get('genre'));
            $utilisateur->setPays($request->get('country'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();
            $view = RouteRedirectView::create("byexample_demo_userrest_get_user", $param);
        } else {
            $view = $this->get_errors_view($errors);
        }
        return $view;
    }

    /**
     * Update an user by id
     *
     * @param string $id
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
    * @Put("/api/users/{id}")
     * @ApiDoc()
     */
    public function putUserAction($id)
    {
        $request = $this->getRequest();
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->findUserByUsernameOrEmail($id);
        if (!$user) {
            $view = FOSView::create();
            $view->setStatusCode(204);
            return $view;
        }

        if ($request->get('username')) {
            $user->setUsername($request->get('username'));
        }
        if ($request->get('email')) {
            $user->setEmail($request->get('email'));
        }
        if ($request->get('plainPassword')) {
            $user->setPlainPassword($request->get('plainPassword'));
        }

        $validator = $this->get('validator');
        $errors = $validator->validate($user, array('Registration'));
        if (count($errors) == 0) {
            $userManager->updateUser($user);
            $view = FOSView::create();
            $view->setStatusCode(204);
        } else {
            $view = $this->get_errors_view($errors);
        }
        return $view;
    }

    


    /**
     * Delete an user by username/email.
     *
     * @param string $slug Username or Email
     *
     * @return FOSView
     * @Secure(roles="ROLE_USER")
     * @ApiDoc()
     */
    /*public function deleteUserAction($slug)
    {
        $view = FOSView::create();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsernameOrEmail($slug);
        if ($user) {
            $userManager->deleteUser($user);
            $view->setStatusCode(204)->setData("User removed.");
        } else {
            $view->setStatusCode(204)->setData("No data available.");
        }
        return $view;
    }*/

    /**
     * Get the validation errors
     *
     * @param ConstraintViolationList $errors Validator error list
     *
     * @return FOSView
     */
    private function get_errors_view($errors)
    {
        $msgs = array();
        $it = $errors->getIterator();
        //$val = new \Symfony\Component\Validator\ConstraintViolation();
        foreach ($it as $val) {
            $msg = $val->getMessage();
            $params = $val->getMessageParameters();
            //using FOSUserBundle translator domain 'validators'
            $msgs[$val->getPropertyPath()][] = $this->get('translator')->trans($msg, $params, 'validators');
        }
        $view = FOSView::create($msgs);
        $view->setStatusCode(400);
        return $view;
    }


  
    
}
