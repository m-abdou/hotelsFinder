<?php
/**
 * Created by PhpStorm.
 * User: abdou
 * Date: 8/6/17
 * Time: 12:06 AM
 */

namespace AppBundle\Controller;

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
    public function postLoginAction(Request $request)
    {

        $userSerivce = $this->get('user_serivce');
        $APIHelper = $this->get('api_helper');
        $requestData = $request->request->all();
        $data = $userSerivce->LoginAndTokenHandler($requestData);
        if($data['status'] == true){
            $view = $APIHelper->getSuccessView($data['token']);
        }else{
            $view = $APIHelper->getErrorView($data['reason']);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\View
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function postProfileDataAction(Request $request)
    {

        $userSerivce = $this->get('user_serivce');
        $APIHelper = $this->get('api_helper');
        $requestData = $request->request->all();
        $data = $userSerivce->getProfileData($requestData);
        if($data['status'] == true){
            $view = $APIHelper->getSuccessView($data['data']);
        }else{
            $view = $APIHelper->getErrorView($data['reason']);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\View
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function postAddUserAction(Request $request)
    {

        $userSerivce = $this->get('user_serivce');
        $APIHelper = $this->get('api_helper');
        $requestData = $request->request->all();
        $data = $userSerivce->createNewUser($requestData);
        if($data['status'] == true){
            $view = $APIHelper->getSuccessView(['token' => $data['token'], 'userId' => $data['userId']]);
        }else{
            $view = $APIHelper->getErrorView($data['reason']);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\View
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function postAddAccountAction(Request $request)
    {

        $userSerivce = $this->get('user_serivce');
        $APIHelper = $this->get('api_helper');
        $requestData = $request->request->all();
        $data = $userSerivce->createUserType($requestData);
        if($data['status'] == true){
            $view = $APIHelper->getSuccessView(['token' => $data['token']]);
        }else{
            $view = $APIHelper->getErrorView($data['reason']);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\View
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function postProfileEditAction(Request $request)
    {

        $userSerivce = $this->get('user_serivce');
        $APIHelper = $this->get('api_helper');
        $requestData = $request->request->all();
        $data = $userSerivce->editUserData($requestData);
        if($data['status'] == true){
            $view = $APIHelper->getSuccessView(['data' => $data['data']]);
        }else{
            $view = $APIHelper->getErrorView($data['reason']);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\View
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function postRequestDataAction(Request $request)
    {

        $orderSerivce = $this->get('data_serivce');
        $APIHelper = $this->get('api_helper');
        $requestData = $request->request->all();
        $data = $orderSerivce->getDataArray($requestData);
        $view = $APIHelper->getSuccessView($data);
        return $this->handleView($view);
    }

}