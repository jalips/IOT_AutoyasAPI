<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UsersController extends FOSRestController
{
    /**
     * Get all users.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/users")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Users",
     *  description="Get all users",
     * )
     */
    public function getUsersAction()
    {

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        $view = $this->view(array('Users' => $users), 200);

        return $this->handleView($view);
    }

    /**
     * Get a single user.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/users/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Users",
     *  description="Get a single user",
     * )
     */
    public function getUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneById($id);

        $view = $this->view(array('user' => $user), 200);

        return $this->handleView($view);
    }

    /**
     * Register a new user.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/users/{username}/{email}/{password}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Users",
     *  description="Register user",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="User name"
     *      },
     *      {
     *          "name"="email",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="User email"
     *      },
     *      {
     *          "name"="password",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="User password"
     *      }
     *  }
     * )
     */
    public function registerUserAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $entityManager = $this->get('doctrine')->getManager();
        $data = $request->attributes->all();

        $user = $userManager->createUser();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPlainPassword($data['password']);
        $user->setEnabled(true);

        $userManager->updateUser($user);

        $view = $this->view(array(
            'Status' => "User correctly registered",
            'User' => $user), 201);

        return $this->handleView($view);
    }

    /**
     * Register a new user.
     *
     * @param string $email
     * @param string $password
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/users/login/{email}/{password}")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Users",
     *  description="Register user",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="User name"
     *      },
     *      {
     *          "name"="password",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="User password"
     *      }
     *  }
     * )
     */
    public function loginUserAction(Request $request, $username, $password)
    {
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');

        $user = $user_manager->findUserByUsername($username);

        if($user === null){
            $view = $this->view(array(
                'Status' => false), 200);

            return $this->handleView($view);
        }

        $encoder = $factory->getEncoder($user);
        $auth = ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) ? "true" : "false";

        if($auth){
            $providerKey = $this->getParameter('fos_user.firewall_name');
            $token = new UsernamePasswordToken($username, $password, $providerKey, $user->getRoles());
            $this->get("security.token_storage")->setToken($token);
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        }

        $view = $this->view(array(
            'Status' => $auth), 200);

        return $this->handleView($view);
    }

    /**
     * Delete single user.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Delete("/users/{id}/delete")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Users",
     *  description="Delete a single user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="User id"
     *      }
     *  }
     * )
     */
    public function deleteUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneById($id);

        $em->remove($user);
        $em->flush();

        $view = $this->view(array('User' => $user), 202);

        return $this->handleView($view);
    }
}