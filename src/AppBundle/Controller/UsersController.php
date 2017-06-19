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
     * @Post("/users/{username}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Register user"
     * )
     */
    public function registerUserAction($username, $email, $password)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $view = $this->view(array(
            'Status' => "User correctly registered",
            'Device' => $user), 201);

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