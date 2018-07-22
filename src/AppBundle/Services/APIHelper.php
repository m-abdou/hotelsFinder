<?php

namespace AppBundle\HelperServices;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class APIResponseFormatter
 *
 * @package Edfa3ly\APIBundle\Services
 */
class APIHelper
{

    /**
     * @param null $data
     *
     * @return View
     */
    public function getSuccessView($data = null)
    {
        return $this->getView($data, false, null);
    }


    /**
     * @param $errorMessage
     *
     * @return View
     */
    public function getErrorView($errorMessage)
    {
        return $this->getView(null, true, $errorMessage);
    }


    /**
     * @param $data
     * @param $status
     * @param $errorMessage
     *
     * @return array
     */
    private function getData($data, $status, $errorMessage)
    {
        $json = array();

        //error data
        $json['error'] = array();
        $json['error']['status'] = $status;
        //if status === false set message to success
        $json['error']['message'] = ($status === false) ? 'success' : $errorMessage;

        //data
        $json['data'] = $data;

        return $json;
    }

    /**
     * @param $data
     * @param $status
     * @param $errorMessage
     *
     * @return View
     */
    private function getView($data, $status, $errorMessage)
    {
        $data = $this->getData($data, $status,$errorMessage);

        $view = View::create($data);
        //set Format
        $view->setFormat('json');
        //set headers to prevent Cache
        $view->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $view->setHeader('Pragma', 'no-cache');
        $view->setHeader('Expires', '0');

        return $view;
    }


    /**
     * @param Form $form
     *
     * @return array
     */
    public function getErrorMessages(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }

        }

        return $errors;
    }

    public function getErrorMessagesFromViolationList(ConstraintViolationList $errors)
    {
        $errorMessages = array();
        foreach($errors as $error)
        {
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }

    /**
     * @param Request $request
     * @param $formName
     */
    public function addDataToFormName(Request $request, $formName)
    {
        $data = null;
        switch ($request->getMethod()) {
            case 'GET':
                $request->query->set($formName, $request->query->all());
                break;
            case 'POST':
                $request->request->set($formName, $request->request->all());
                break;
            case 'PUT':
                $request->request->set($formName, $request->request->all());
                break;
        }

    }

}