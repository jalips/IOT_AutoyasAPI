<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;

class UsersController extends FOSRestController
{
    /**
     * Get all users.
     *
     * @return array
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

        $user = $em->getRepository('AppBundle:User')->findAll();

        $view = $this->view(array('sensor' => $user), 201);

        return $this->handleView($view);
    }

    /**
     * Get a single user.
     *
     * @param User $user
     * @return array
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
}