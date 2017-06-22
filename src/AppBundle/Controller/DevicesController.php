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
     *  section="Devices",
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
     * Get all devices by user.
     *
     * @param int $userId
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     *
     * @Get("/devices/user/{userId}")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Devices",
     *  description="Get all devices",
     *  requirements={
     *      {
     *          "name"="userId",
     *          "dataType"="int",
     *          "requirement"="\d",
     *          "description"="User id"
     *       }
     *  }
     * )
     */
    public function getDevicesByUserAction($userId)
    {
        $devices = $this->getDevicesByParam(array('user' => $userId));

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
     *  section="Devices",
     *  description="Get a single device",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\s",
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
     * @Post("/devices/{macAdress}/{userId}/new")
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Devices",
     *  description="Register device",
     *     requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="Device mac adress"
     *      },
     *      {
     *          "name"="userId",
     *          "dataType"="int",
     *          "requirement"="\s",
     *          "description"="User id"
     *      }
     *  }
     * )
     */
    public function newDeviceAction($macAdress, $userId)
    {
        $device = $this->registerDevice($macAdress, $userId);

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
     *  section="Devices",
     *  description="Check if a single device is active or not",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\s",
     *          "description"="Device mac adress"
     *      }
     *  }
     * )
     */
    public function isDeviceAliveAction($macAdress)
    {
        $device = $this->getDeviceByParam('macAdress', $macAdress);

        if(is_null($device)) {
            $device = $this->registerDevice(array('macAdress' => $macAdress));

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
     *  section="Devices",
     *  description="Activate a single device",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\s",
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
     *  section="Devices",
     *  description="Desactivate a single device",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\s",
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
     *  section="Devices",
     *  description="Delete a single device",
     *  requirements={
     *      {
     *          "name"="macAdress",
     *          "dataType"="string",
     *          "requirement"="\s",
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
     * Get Devices in database
     *
     * @param $key
     * @param $value
     * @return Device|null|object
     */
    protected function getDevicesByParam($args) {
        $em = $this->getDoctrine()->getManager();

        $devices = $em->getRepository('AppBundle:Device')->findBy(
            $args
        );

        return $devices;
    }

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
     * @param array $args
     * @return Device|null|object
     */
    protected function registerDevice($args) {
        $em = $this->getDoctrine()->getManager();

        $device = new Device();

        foreach($args as $key => $value) {
            if($key == "userId") {
                $fullUser = $em->getRepository('AppBundle:User')->findOneBy(
                array('id' => $value)
                );

                $device->setUser($fullUser);
            } elseif ($key == "macAdress") {
                $device->setMacAdress($value);
            }
        }

        $device->setStatus(1);

        $em->persist($device);
        $em->flush();

        return $device;
    }
}