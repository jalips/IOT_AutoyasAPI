<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
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

class DevicesController extends FOSRestController
{
    /**
     * Get all devices.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/devices")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get all devices"
     * )
     */
    public function getDevicesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $devices = $em->getRepository('AppBundle:Device')->findAll();

        $view = $this->view($devices, 201);

        return $this->handleView($view);
    }

    /**
     * Get single device.
     *
     * @param int $guid
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/devices/{guid}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get a single devices",
     *  requirements={
     *      {
     *          "name"="guid",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Device guid"
     *      }
     *  }
     * )
     */
    public function getDeviceAction($guid)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array('guid' => $guid)
        );

        $view = $this->view(array('Device' => $device), 200);

        return $this->handleView($view);
    }

    /**
     * Register a new device.
     *
     * @param int $guid
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/devices/{guid}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Register device"
     * )
     */
    public function newDeviceAction($guid)
    {
        $device = new Device();
        $device->setGuid($guid);
        $device->setStatus("OK");

        $em = $this->getDoctrine()->getManager();
        $em->persist($device);
        $em->flush();

        $view = $this->view(array(
            'Status' => "Device correctly registered",
            'Device' => $device), 201);

        return $this->handleView($view);
    }

    /**
     * Activate single device.
     *
     * @param int $guid
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/devices/{guid}/activate")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Activate a single device",
     *  requirements={
     *      {
     *          "name"="guid",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Device guid"
     *      }
     *  }
     * )
     */
    public function activateDeviceAction($guid)
    {
        $em = $this->getDoctrine()->getManager();

        $device = new Device($em->getRepository('AppBundle:Device')->findOneBy(
            array('guid' => $guid)
        ));
        $device->setStatus("OK");

        $em->flush();

        $view = $this->view(array('Device' => $device), 202);

        return $this->handleView($view);
    }

    /**
     * Desactivate single device.
     *
     * @param int $guid
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/devices/{guid}/desactivate")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Desactivate a single device",
     *  requirements={
     *      {
     *          "name"="guid",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Device guid"
     *      }
     *  }
     * )
     */
    public function desactivateDeviceAction($guid)
    {
        $em = $this->getDoctrine()->getManager();

        $device = new Device($em->getRepository('AppBundle:Device')->findOneBy(
            array('guid' => $guid)
        ));
        $device->setStatus("KO");

        $em->flush();

        $view = $this->view(array('Device' => $device), 202);

        return $this->handleView($view);
    }

    /**
     * Delete single device.
     *
     * @param int $guid
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Delete("/devices/{guid}/delete")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a single device",
     *  requirements={
     *      {
     *          "name"="guid",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Device guid"
     *      }
     *  }
     * )
     */
    public function deleteDeviceAction($guid)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array('guid' => $guid)
        );

        $em->remove($device);
        $em->flush();

        $view = $this->view(array('Device' => $device), 202);

        return $this->handleView($view);
    }
}