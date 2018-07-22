<?php

namespace AppBundle\Controller;

use AppBundle\Form\HotelType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations AS Rest;

/**
 * REST controller
 * @Prefix("/api")
 * @NamePrefix("api")
 */
class APIController extends FOSRestController
{

    /**
     * @Rest\View
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getHotelsAction(Request $request)
    {

        $manager = $this->get('service.manager');
        $apiHelper = $this->get('service.apiHelper');
        $searchForm = $this->createForm(HotelType::class);
        $searchForm->submit($request->query->all());
        $data = $manager->operate($searchForm);

        if($data['status'] == true){
            $view = $apiHelper->getSuccessView($data);
        }else{
            $view = $apiHelper->getErrorView($data);
        }

        return $this->handleView($view);
    }
}