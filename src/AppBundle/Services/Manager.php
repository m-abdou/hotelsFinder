<?php

namespace AppBundle\Services;

use AppBundle\Services\Handlers\HotelHandler;
use AppBundle\Services\Handlers\Validator;
use Symfony\Component\Form\Form;

class Manager
{

    /**
     * @var HotelHandler $hotelHandler;
     */
    private $hotelHandler;

    /**
     * @var Validator $validator;
     */
    private $validator;

    public function __construct(HotelHandler $hotelHandler, Validator $validator)
    {
        $this->validator = $validator;
        $this->hotelHandler = $hotelHandler;
    }

    /**
     * manage request for query validation and execute it
     * @param Form $form
     * @return array
     */
    public function operate(Form $form)
    {
        if($this->validator->isValidate($form)){
            return $this->hotelHandler->searchByQuery($form->getData());
        }

        return $this->validator->notValidResponse($form);
    }


}