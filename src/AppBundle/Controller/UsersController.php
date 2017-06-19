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
     * @ApiDoc(
     *  resource=true,
     *  description="Get all users",
     * )
     */
    public function getUsersAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        $view = $this->view(array('Users' => $users), 201);

        return $this->handleView($view);
    }

    /**
     * Get a single user.
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     * @ParamConverter("user", class="AppBundle:User")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get a single user",
     * )
     */
    public function getUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneById($user->getId());

        $view = $this->view(array('user' => $user), 201);

        return $this->handleView($view);
    }

    /**
     * Delete single user.
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
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
     *  },
     *  parameters={
     *
     *  }
     * )
     */
    public function deleteUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneById($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $view = $this->view(array('User' => $user), 201);

        return $this->handleView($view);
    }
}