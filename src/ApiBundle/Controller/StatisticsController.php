<?php

namespace ApiBundle\Controller;

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
     *  section="Statistics",
     *  description="Get all statistics"
     * )
     */
    public function getStatisticsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $statistics = $em->getRepository('AppBundle:Statistic')->findAll();
        $total = count($statistics);

        $view = $this->view(
            array(
                'Total'         => $total,
                'Statistics'    => $statistics
            ), 200);

        return $this->handleView($view);
    }

    /**
     * Get single statistic by params.
     *
     * @param string $statisticType
     * @param string $device
     * @param string $createdAt
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/statistics/{statisticType}/{device}/{createdAt}")
     * @ApiDoc(
     *  resource=true,
     *  section="Statistics",
     *  description="Get a single statistic by params"
     * )
     */
    public function getStatisticByParamsAction($statisticType, $device, $createdAt)
    {
        $em = $this->getDoctrine()->getManager();

        $fullStatisticType = $em->getRepository('AppBundle:StatisticType')->findOneBy(
            array('name' => $statisticType)
        );

        $fullDevice = $em->getRepository('AppBundle:Device')->findOneBy(
            array('macAdress' => $device)
        );

        $date = $date = DateTime::createFromFormat("Y-m-d H:i:s", $createdAt);

        $statistic = $em->getRepository('AppBundle:Statistic')->findOneBy(
            array(
                'createdAt'     => $date,
                'device'        => $fullDevice->getId(),
                'statisticType' => $fullStatisticType->getId()
            )
        );

        $view = $this->view($statistic, 200);

        return $this->handleView($view);
    }

    /**
     * Get single statistic by id.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/statistics/{id}")
     * @ApiDoc(
     *  resource=true,
     *  section="Statistics",
     *  description="Get a single statistic by id"
     * )
     */
    public function getStatisticByIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $statistic = $em->getRepository('AppBundle:Statistic')->find($id);

        $view = $this->view($statistic, 200);

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
     *  section="Statistics",
     *  description="Insert new statistic"
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

        $view = $this->view($statistic, 201);

        return $this->handleView($view);
    }

    /**
     * Delete single statistic.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Delete("/statistic/{id}/delete")
     * @ApiDoc(
     *  resource=true,
     *  section="Statistics",
     *  description="Delete a single statistic"
     * )
     */
    public function deleteStatisticAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $statistic = $em->getRepository('AppBundle:Statistic')->find($id);

        $em->remove($statistic);
        $em->flush();

        $view = $this->view($statistic, 202);

        return $this->handleView($view);
    }
}