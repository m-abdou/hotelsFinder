<?php

use AppBundle\Form\HotelType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\Hotel;

class ManagerTest extends WebTestCase
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
        $this->managerService = $kernel->getContainer()->get('service.manager');
    }

    public function testOperate()
    {
        $this->form->submit(['name' => 'Media One Hotel']);
        $hotels = $this->managerService->operate($this->form);
        $this->assertCount(1, $hotels['hotels']);
    }

    public function testOperateWithError()
    {
        $this->form->submit(['price_from' =>'test']);
        $error = $this->managerService->operate($this->form);
        $this->assertEquals($error['errors']['price_from'][0], 'This value is not valid.');
    }
}