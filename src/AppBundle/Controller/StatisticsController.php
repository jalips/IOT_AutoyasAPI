<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Statistic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;

class StatisticsController extends FOSRestController
{
    /**
     * Get all statistics.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get all statistics"
     * )
     */
    public function getStatisticsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $statistics = $em->getRepository('AppBundle:Statistic')->findAll();

        $view = $this->view($statistics, 201);

        return $this->handleView($view);
    }

    /**
     * Get single statistic.
     *
     * @param Statistic $statistic
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     * @ParamConverter("statistic", class="AppBundle:Statistic")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get a single statistic type",
     *  requirements={
     *      {
     *          "name"="start_date",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="Statistic start date"
     *      },
     *      {
     *          "name"="end_date",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="Statistic end date"
     *      },
     *      {
     *          "name"="data",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Statistic data"
     *      },
     *      {
     *          "name"="statistic type id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Statistic type id"
     *      },
     *      {
     *          "name"="device id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Statistic device id"
     *      }
     *  },
     *  parameters={
     *
     *  }
     * )
     */
    public function getStatisticAction(Request $request, $startDate, $endDate, $data, $statisticType, $device)
    {
        $em = $this->getDoctrine()->getManager();

        $statistic = $em->getRepository('AppBundle:Statistic')->findOneBy(
            array(  'startDate'     => $startDate,
                    'endDate'       => $endDate,
                    'data'          => $data,
                    'statisticType' => $statisticType,
                    '$device'       => $device
                )
        );

        $view = $this->view(array('statistic' => $statistic), 201);

        return $this->handleView($view);
    }

    /**
     * Get single statistic by id.
     *
     * @param Statistic $statistic
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     * @ParamConverter("statistic", class="AppBundle:Statistic")
     *
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get a single statistic type by id",
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
    public function getStatisticTypeByIdAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $statisticType = $em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('id' => $id)
        );

        $view = $this->view(array('statistic Type' => $statisticType), 201);

        return $this->handleView($view);
    }

    /**
     * Register a new statistic.
     *
     * @param Statistic $statistic
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/statisticType/{start_date}/{end_date}/{data}/{statistic_type}/{device}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Register statistic type"
     * )
     */
    public function newStatisticTypeAction(Request $request, $startDate, $endDate, $data, $statisticType, $device)
    {
        $statistic = new Statistic();
        $statistic->setStartDate($startDate);
        $statistic->setEndDate($endDate);
        $statistic->setData($data);
        $statistic->setStatisticType($statisticType);
        $statistic->setDevice($device);

        $em = $this->getDoctrine()->getManager();
        $em->persist($statistic);
        $em->flush();

        $view = $this->view(array(
            'Status' => "Statistic correctly registered",
            'Device' => $statistic), 201);

        return $this->handleView($view);
    }

    /**
     * Delete single statistic.
     *
     * @param Statistic $statistic
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

        $statistic = new Statistic();
        $statistic = $em->getRepository('AppBundle:Statistic')->findOneBy(
            array('id' => $id)
        );

        $em = $this->getDoctrine()->getManager();
        $em->remove($statistic);
        $em->flush();

        $view = $this->view(array('Statistic' => $statistic), 201);

        return $this->handleView($view);
    }
}