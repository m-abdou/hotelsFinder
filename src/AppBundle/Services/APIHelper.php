<?php

namespace AppBundle\Services;

use FOS\RestBundle\View\View;


class APIHelper
{

    /**
     * @param null $data
     *
     * @return View
     */
    public function getSuccessView($data = null)
    {
        return $this->getView($data, false);
    }


    /**
     * @param $errorMessage
     *
     * @return View
     */
    public function getErrorView($errorData)
    {
        return $this->getView($errorData, true);
    }


    /**
     * @param $data
     * @param $status
     * @param $errorMessage
     *
     * @return array
     */
    private function getData($data, $status)
    {
        $json = array();

        //error data
        $json['error'] = array();
        $json['error']['status'] = $status;

        if($status === false){
            $json['hotels'] = $data['hotels'];
        } else {
            $json['messages'] = $data['errors'];
        }

        return $json;
    }

    /**
     * @param $data
     * @param $status
     * @param $errorMessage
     *
     * @return View
     */
    private function getView($data, $status)
    {
        $data = $this->getData($data, $status);

        $view = View::create($data);
        //set Format
        $view->setFormat('json');
        //set headers to prevent Cache
        $view->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $view->setHeader('Pragma', 'no-cache');
        $view->setHeader('Expires', '0');

        return $view;
    }
}