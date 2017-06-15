<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StatisticType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
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
     * @ApiDoc(
     *  resource=true,
     *  description="Get all statistic types"
     * )
     */
    public function getStatisticTypesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $statisticTypes = $em->getRepository('AppBundle:StatisticType')->findAll();

        $view = $this->view($statisticTypes, 201);

        return $this->handleView($view);
    }

    /**
     * Get single statistic type.
     *
     * @param StatisticType $statisticType
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     * @ParamConverter("statistic_type", class="AppBundle:StatisticType")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get a single statistic type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Statistic type id"
     *      }
     *  },
     *  parameters={
     *
     *  }
     * )
     */
    public function getStatisticTypeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $statisticType = $em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('id' => $id)
        );

        $view = $this->view(array('statistic Type' => $statisticType), 201);

        return $this->handleView($view);
    }

    /**
     * Register a new statistic type.
     *
     * @param StatisticType $statisticType
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/statisticType/{name}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Register statistic type"
     * )
     */
    public function newStatisticTypeAction(Request $request, $name)
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
     * @param StatisticType $statisticType
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     * @Post("/staticticType/{id}/delete")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a single statistic type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Statistic type id"
     *      }
     *  },
     *  parameters={
     *
     *  }
     * )
     */
    public function deleteStatisticTypeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $statisticType = new StatisticType();
        $statisticType = $em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('id' => $id)
        );

        $em = $this->getDoctrine()->getManager();
        $em->remove($statisticType);
        $em->flush();

        $view = $this->view(array('StatisticType' => $statisticType), 201);

        return $this->handleView($view);
    }
}