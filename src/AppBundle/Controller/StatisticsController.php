<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Statistic;
use DateTime;
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
    protected $format = "Y-m-d h:i:s";

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
     * @param string $statisticType
     * @param string $device
     * @param string $createdAt
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/statictics/{statisticType}/{device}/{createdAt}")
     * @ApiDoc(
     *  resource=true,
     *  description="Get a single statistic",
     *  requirements={
     *      {
     *          "name"="statisticType",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="Statistic type name"
     *      },
     *      {
     *          "name"="device",
     *          "dataType"="string",
     *          "requirement"="\",
     *          "description"="Statistic device mac adress"
     *      },
     *     {
     *          "name"="createdAt",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="Statistic date of creation"
     *      }
     *  }
     * )
     */
    public function getStatisticAction($statisticType, $device, $createdAt)
    {
        $em = $this->getDoctrine()->getManager();

        $fullStatisticType = $em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('name' => $statisticType)
        );

        $fullDevice = $em->getRepository('AppBundle:Device')->findOneBy(
            array('macAdress' => $device)
        );

        $statistic = $em->getRepository('AppBundle:Statistic')->findOneBy(
            array(  'createdAt'     => DateTime::createFromFormat($this->format, $createdAt),
                    'statisticType' => $fullStatisticType->getId(),
                    '$device'       => $fullDevice->getId()
                )
        );

        $view = $this->view(array('statistic' => $statistic), 200);

        return $this->handleView($view);
    }

    /**
     * Get single statistic by id.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/statictics/{id}")
     * @ApiDoc(
     *  resource=true,
     *  description="Get a single statistic by id",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Statistic id"
     *      }
     *  }
     * )
     */
    public function getStatisticByIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $statistic = $em->getRepository('AppBundle:Statistic')->findOneBy(
            array('id' => $id)
        );

        $view = $this->view(array('statistic' => $statistic), 200);

        return $this->handleView($view);
    }

    /**
     * Register a new statistic.
     *
     * @param string $data
     * @param string $statisticType
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/statistics/{data}/{statisticType}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Insert new statistic",
     *  requirements={
     *      {
     *          "name"="data",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="Statistic data"
     *      },
     *      {
     *          "name"="statisticType",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="Statistic type name"
     *      }
     *  }
     * )
     */
    public function newStatisticAction($data, $statisticType)
    {
        $em = $this->getDoctrine()->getManager();

        $datas = explode("***", $data);
        $device = $datas[0];
        $value = intval($datas[1]);

        $statistic = new Statistic();
        $statistic->setData($value);
        $statistic->setStatisticType($em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('name' => $statisticType)
        ));
        $statistic->setDevice($em->getRepository('AppBundle:Device')->findOneBy(
            array('macAdress' => $device)
        ));

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
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Delete("/statictic/{id}/delete")
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a single statistic",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Statistic id"
     *      }
     *  }
     * )
     */
    public function deleteStatisticAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $statistic = $em->getRepository('AppBundle:Statistic')->findOneBy(
            array('id' => $id)
        );

        $em->remove($statistic);
        $em->flush();

        $view = $this->view(array('Statistic' => $statistic), 202);

        return $this->handleView($view);
    }
}