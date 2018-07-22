<?php

use AppBundle\Form\HotelType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Model\Hotel;

class ValidatorTest extends KernelTestCase
{

    /** @var \Symfony\Component\Form\FormFactory $formFactory */
    private $formFactory;

    private $form;

    public function setUp()
    {
        static::bootKernel();
        $kernel = static::$kernel;
        $this->formFactory = $kernel->getContainer()->get('form.factory');
        $this->form = $this->formFactory->create(HotelType::class);
        $this->validatorHandler = $kernel->getContainer()->get('service.handler.validator');
    }

    public function testIsValidateTrue()
    {
        $this->form->submit(['price_from' =>20 , 'price_to' => 40]);
        $this->assertTrue($this->validatorHandler->isValidate($this->form));
    }

    public function testIsValidateNotTrue()
    {
        $this->form->submit(['price_from' =>'test' , 'price_to' => 40]);
        $this->assertNotTrue($this->validatorHandler->isValidate($this->form));
    }

    public function testOperateWithError()
    {
        $this->form->submit(['price_from' =>'test']);
        $error = $this->validatorHandler->notValidResponse($this->form);
        $this->assertEquals($error['errors']['price_from'][0], 'This value is not valid.');
    }
}