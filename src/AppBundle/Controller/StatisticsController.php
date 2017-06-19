<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Statistic;
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

class StatisticsController extends FOSRestController
{
    /**
     * Get all statistics.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/statistics")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get all statistics"
     * )
     */
    public function getStatisticsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $statistics = $em->getRepository('AppBundle:Statistic')->findAll();

        $view = $this->view($statistics, 200);

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
     * @Get("/staticticTypes/{statistic_type}/{device}/{start_date}/{end_date}")
     * @ApiDoc(
     *  resource=true,
     *  description="Get a single statistic type",
     *  requirements={
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
     *      },
     *     {
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
     *      }
     *  },
     *  parameters={
     *
     *  }
     * )
     */
    public function getStatisticAction($statisticType, $device, $startDate, $endDate)
    {
        $em = $this->getDoctrine()->getManager();

        $statistic = $em->getRepository('AppBundle:Statistic')->findOneBy(
            array(  'startDate'     => $startDate,
                    'endDate'       => $endDate,
                    'statisticType' => $statisticType,
                    '$device'       => $device
                )
        );

        $view = $this->view(array('statistic' => $statistic), 200);

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
     * @Get("/staticticTypes/{id}")
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
    public function getStatisticTypeByIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $statisticType = $em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('id' => $id)
        );

        $view = $this->view(array('statistic Type' => $statisticType), 200);

        return $this->handleView($view);
    }

    /**
     * Register a new statistic.
     *
     * @param Statistic $statistic
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/statisticTypes/{statistic_type}/{device}/{start_date}/{end_date}/{data}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Register statistic type"
     * )
     */
    public function newStatisticTypeAction($startDate, $endDate, $data, $statisticType, $device)
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
     *
     * @Delete("/staticticTypes/{id}/delete")
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
    public function deleteStatisticTypeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $statistic = $em->getRepository('AppBundle:Statistic')->findOneById($id);

        $em->remove($statistic);
        $em->flush();

        $view = $this->view(array('Statistic' => $statistic), 202);

        return $this->handleView($view);
    }
}