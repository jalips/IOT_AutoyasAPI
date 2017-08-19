<?php

namespace ApiBundle\Controller;

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
        $total = count($devices);

        $view = $this->view(
            array(
                'Total'     => $total,
                'Devices'   => $devices
            ), 200);

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
     *  description="Get all devices"
     * )
     */
    public function getDevicesByUserAction($userId)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($userId);

        $view = $this->view($user->getDevices(), 200);

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
     *  description="Get a single device"
     * )
     */
    public function getDeviceAction($macAdress)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array('macAdress' => $macAdress)
        );

        $view = $this->view($device, 200);

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
     *  description="Register device"
     * )
     */
    public function newDeviceAction($macAdress, $userId)
    {
        $em = $this->getDoctrine()->getManager();

        $fullUser = $em->getRepository('AppBundle:User')->find($userId);

        $device = new Device();
        $device->setMacAdress($macAdress);
        $device->setUser($fullUser);
        $device->setStatus(1);

        $em->persist($device);
        $em->flush();

        $view = $this->view($device, 201);

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
     *  description="Check if a single device is active or not"
     * )
     */
    public function isDeviceAliveAction($macAdress)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array('macAdress' => $macAdress)
        );

        $view = $this->view($device->getStatus(), 200);

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
     *  description="Activate a single device"
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

        $view = $this->view($device, 202);

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
     *  description="Desactivate a single device"
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

        $view = $this->view($device, 202);

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
     *  description="Delete a single device"
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

        $view = $this->view($device, 202);

        return $this->handleView($view);
    }
}