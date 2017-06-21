<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
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

        $view = $this->view($devices, 200);

        return $this->handleView($view);
    }

    /**
     * Get single device.
     *
     * @param string $macAdress
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/devices/{macAdress}")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get a single devices",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="Device mac adress"
     *      }
     *  }
     * )
     */
    public function getDeviceAction($macAdress)
    {
        $device = $this->getDeviceByParam('macAdress', $macAdress);

        $view = $this->view(array('Device' => $device), 200);

        return $this->handleView($view);
    }

    /**
     * Register a new device.
     *
     * @param int $macAdress
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Post("/devices/{macAdress}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Register device"
     * )
     */
    public function newDeviceAction($macAdress)
    {
        $device = $this->registerDevice($macAdress);

        $view = $this->view(array(
            'Status' => "Device correctly registered",
            'Device' => $device), 201);

        return $this->handleView($view);
    }

    /**
     * Get if device is alive and register it if not.
     *
     * @param string $macAdress
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/devices/{macAdress}/isAlive")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Check if a single device is active or not",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="Device mac adress"
     *      }
     *  }
     * )
     */
    public function isDeviceAliveAction($macAdress)
    {
        $device = $this->getDeviceByParam('macAdress', $macAdress);

        if(is_null($device)) {
            $device = $this->registerDevice($macAdress);

            $view = $this->view(array(
                'Status' => "Device correctly registered",
                'Device' => $device), 201);

        } elseif ($device->getStatus()) {
            $view = $this->view(array(
                'Status' => "Device is alive",
                'Device' => $device), 200);
        } else {
            $view = $this->view(array(
                'Status' => "Device is desactivated",
                'Device' => $device), 200);
        }


        return $this->handleView($view);
    }

    /**
     * Activate single device.
     *
     * @param string $macAdress
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Put("/devices/{macAdress}/activate")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Activate a single device",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="Device mac adress"
     *      }
     *  }
     * )
     */
    public function activateDeviceAction($macAdress)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array('macAdress' => $macAdress)
        );
        $device->setStatus(1);

        $em->flush();

        $view = $this->view(array('Device' => $device), 202);

        return $this->handleView($view);
    }

    /**
     * Desactivate single device.
     *
     * @param string $macAdress
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Put("/devices/{macAdress}/desactivate")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Desactivate a single device",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="Device mac adress"
     *      }
     *  }
     * )
     */
    public function desactivateDeviceAction($macAdress)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array('macAdress' => $macAdress)
        );
        $device->setStatus(0);

        $em->flush();

        $view = $this->view(array('Device' => $device), 202);

        return $this->handleView($view);
    }

    /**
     * Delete single device.
     *
     * @param string $macAdress
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Delete("/devices/{macAdress}/delete")
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete a single device",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="Device mac adress"
     *      }
     *  }
     * )
     */
    public function deleteDeviceAction($macAdress)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array('macAdress' => $macAdress)
        );

        $em->remove($device);
        $em->flush();

        $view = $this->view(array('Device' => $device), 202);

        return $this->handleView($view);
    }

    /****************************************************** Methods **************************************************/

    /**
     * Get Device in database
     *
     * @param $key
     * @param $value
     * @return Device|null|object
     */
    protected function getDeviceByParam($key, $value) {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array($key => $value)
        );

        return $device;
    }

    /**
     * Register Device in database
     *
     * @param string $macAdress
     * @return Device|null|object
     */
    protected function registerDevice($macAdress) {
        $device = new Device();
        $device->setMacAdress($macAdress);
        $device->setStatus(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($device);
        $em->flush();

        return $device;
    }
}