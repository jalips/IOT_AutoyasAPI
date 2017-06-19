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
     * @ApiDoc(
     *  resource=true,
     *  description="Get all devices"
     * )
     */
    public function getDevicesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $devices = $em->getRepository('AppBundle:Device')->findAll();

        $view = $this->view($devices, 201);

        return $this->handleView($view);
    }

    /**
     * Get single device.
     *
     * @param device $Device
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     * @ParamConverter("device", class="AppBundle:Device")
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
     *  },
     *  parameters={
     *
     *  }
     * )
     */
    public function getDeviceAction(Request $request, $guid)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array('guid' => $guid)
        );

        $view = $this->view(array('Device' => $device), 201);

        return $this->handleView($view);
    }

    /**
     * Register a new device.
     *
     * @param Device $Device
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
    public function newDeviceAction(Request $request, $guid)
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
     * Desactivate single device.
     *
     * @param device $Device
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
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
     *  },
     *  parameters={
     *
     *  }
     * )
     */
    public function desactivateDeviceAction(Request $request, $guid)
    {
        $em = $this->getDoctrine()->getManager();

        $device = new Device($em->getRepository('AppBundle:Device')->findBy(
            array('guid' => $guid)
        ));
        $device->setStatus("KO");

        $em->flush();

        $view = $this->view(array('Device' => $device), 201);

        return $this->handleView($view);
    }

    /**
     * Delete single device.
     *
     * @param device $Device
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
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
     *  },
     *  parameters={
     *
     *  }
     * )
     */
    public function deleteDeviceAction(Request $request, $guid)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneBy(
            array('guid' => $guid)
        );

        $em->remove($device);
        $em->flush();

        $view = $this->view(array('Device' => $device), 201);

        return $this->handleView($view);
    }
}