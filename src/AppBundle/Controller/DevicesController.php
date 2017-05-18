<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
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
    public function getDeviceAction(Request $request, Device $device)
    {
        $em = $this->getDoctrine()->getManager();

        $device = $em->getRepository('AppBundle:Device')->findOneById($device->getId());

        $view = $this->view(array('Device' => $device), 201);

        return $this->handleView($view);
    }

    /**
     * Post register.
     *
     * @param Device $Device
     * @return \Symfony\Component\HttpFoundation\Response
     * @Post("/devices/register")
     */
    public function postRegisterDeviceAction(Request $request)
    {
        $Device = new Device();
        $Device->setGuid($request->get('guid'));
        $Device->setStatus('OK');

        $em = $this->getDoctrine()->getManager();
        $em->persist($Device);
        $em->flush();

        $view = $this->view(array('Device' => $Device), 201);

        return $this->handleView($view);
    }

    /**
     * Post update.
     *
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Post("/devices/{id}/update")
     */
    public function postUpdateDeviceAction(Request $request)
    {
        $Device = new Device();

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $view = $this->view(array('Device' => $Device), 201);

        return $this->handleView($view);
    }
}