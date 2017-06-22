<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StatisticType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;

class StatisticTypesController extends FOSRestController
{
    /**
     * Get all statistic types.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/statisticTypes")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="StatisticTypes",
     *  description="Get all statistic types"
     * )
     */
    public function getStatisticTypesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $statisticTypes = $em->getRepository('AppBundle:StatisticType')->findAll();

        $view = $this->view($statisticTypes, 200);

        return $this->handleView($view);
    }

    /**
     * Get single statistic type.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/statisticTypes/{id}")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="StatisticTypes",
     *  description="Get a single statistic type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Statistic type id"
     *      }
     *  }
     * )
     */
    public function getStatisticTypeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $statisticType = $em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('id' => $id)
        );

        $view = $this->view(array('statistic Type' => $statisticType), 200);

        return $this->handleView($view);
    }

    /**
     * Register a new statistic type.
     *
     * @param string $name
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/statisticTypes/{name}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="StatisticTypes",
     *  description="Register statistic type",
     *     requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="Statistic type name"
     *      }
     *  }
     * )
     */
    public function newStatisticTypeAction($name)
    {
        $statisticType = new StatisticType();
        $statisticType->setName($name);

        $em = $this->getDoctrine()->getManager();
        $em->persist($statisticType);
        $em->flush();

        $view = $this->view(array(
            'Status' => "Statistic Type correctly registered",
            'Device' => $statisticType), 201);

        return $this->handleView($view);
    }

    /**
     * Delete single statistic type.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Delete("/staticticTypes/{id}/delete")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="StatisticTypes",
     *  description="Delete a single statistic type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Statistic type id"
     *      }
     *  }
     * )
     */
    public function deleteStatisticTypeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $statisticType = $em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('id' => $id)
        );

        $em->remove($statisticType);
        $em->flush();

        $view = $this->view(array('StatisticType' => $statisticType), 202);

        return $this->handleView($view);
    }
}