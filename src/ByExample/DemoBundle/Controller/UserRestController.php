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
    * Renvoie la note d'un utilisateur pour un item
     * @Get("users/{iduser}/note/item/{id_item}")
     * @ApiDoc()
    * @return FOSView
   */
    public function getNoteItemAction($iduser, $id_item){
        $view = FOSView::create();
        $em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('ByExampleDemoBundle:Note');
        $notes=$repo->getUserNoteItem($id_item,$iduser);
        if ($notes){
            $notes["idItem"] = intval($id_item);
            $view->setData($notes);
            $view->setStatusCode(200);
        } else {
            $view->setStatusCode(404);
        }
        return $view;
    }

    /**
    * Met à jour une playlist
     * @Put("users/{id}/playlist/{id_playlist}")
     * @ApiDoc()
    * @return FOSView
   */
    public function putPlaylistAction($id, $id_playlist){
        $view = FOSView::create();
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $name = $request->get('nomPlaylist');
        $repo=$em->getRepository('ByExampleDemoBundle:Playlist');
        $idplaylist=$repo->updatePlaylist($id_playlist, $name, $id);
        if($idplaylist) {
            $view->setStatusCode(200);
        } else {
            $view->setStatusCode(404);
        }
        return $view;
    }


    /**
    * Créé une playlist
     * @Post("users/{id}/playlist")
     * @ApiDoc()
    * @return FOSView
   */
    public function postPlaylistAction($id){
        $view = FOSView::create();
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $name = $request->get('nomPlaylist');
        $repo=$em->getRepository('ByExampleDemoBundle:Playlist');
        $idplaylist=$repo->insertPlaylist($name, $id);
        
        if($idplaylist) {
            $view->setStatusCode(200)->setData(array("id"=> $idplaylist));
        } else {
            $view->setStatusCode(404);
        }
        return $view;
    }


    /**
    * Récupère les playlists d'un utilisateur
     * @Get("users/{id}/playlist")
     * @ApiDoc()
    * @return FOSView
   */
    public function getPlaylistAction($id){
        $view = FOSView::create();
        $em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('ByExampleDemoBundle:Playlist');
        $idplaylist=$repo->findPlaylistByUser($id);
        if($idplaylist) {
            $view->setStatusCode(200)->setData($idplaylist);
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
    * @Put("api/users/{id}")
     * @ApiDoc()
     */
    public function putUserAction($id)
    {
        $request = $this->getRequest();
        $userManager = $this->container->get('fos_user.user_manager');

        //$user = $userManager->findUserByUsernameOrEmail($id);
        $user=$userManager->findUserBy(array('id' => $id));
       // $user= $userManager->findOneById($id);
        $em = $this->getDoctrine()->getManager();
        $repo=$em->getRepository('ByExampleDemoBundle:Utilisateur');
        

        if (!$user) {
            $view = FOSView::create();
            $view->setStatusCode(201);
            return $view;
        }

        if ($request->get('username')) {
            $user->setUsername($request->get('username'));
        }
        if ($request->get('email')) {
            $user->setEmail($request->get('email'));
        }

        if($request->get('newPassword') && $request->get('plainPassword')){

            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($user);
            $encoded_pass = $encoder->encodePassword($request->get('plainPassword'), $user->getSalt());
            if($user->getPassword() == $encoded_pass){
                $user->setPlainPassword($request->get('newPassword'));
            }
            else{
                $view = FOSView::create();
                $view->setStatusCode(403);
                return $view;
            }
        }

        /*if ($request->get('plainPassword')) {    
            $user->setPlainPassword($request->get('plainPassword'));
        }*/

        if($request->get('pays')){
            $query = $em->createQuery('UPDATE ByExampleDemoBundle:Utilisateur n SET n.pays = :pays WHERE n.id = :id')
            ->setParameter('pays', $request->get('pays'))->setParameter('id', $id);
            $query->getResult();
        }


        $validator = $this->get('validator');
        $errors = $validator->validate($user);
        if (count($errors) == 0) {
            $userManager->updateUser($user);
            $view = FOSView::create();
            $view->setStatusCode(204);

        } else {
            //$view = $this->get_errors_view($errors);
        }
        return $view->setData();
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
