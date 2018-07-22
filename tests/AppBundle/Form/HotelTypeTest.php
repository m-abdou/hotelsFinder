<?php

use AppBundle\Form\HotelType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HotelTypeTest extends KernelTestCase
{
    /** @var \Symfony\Component\Form\FormFactory $formFactory */
    private $formFactory;

    public function setUp()
    {
        static::bootKernel();
        $kernel = static::$kernel;
        $this->formFactory = $kernel->getContainer()->get('form.factory');
    }

    public function testSubmitWithValidAvailability()
    {
        $searchForm = $this->formFactory->create(HotelType::class);
        $searchForm->submit([
            'available_from' => '5-10-2018',
            'available_to' => '20-12-2020',
        ]);

        $this->assertTrue($searchForm->isValid());
    }

    public function testSubmitWithValidPrice()
    {
        $searchForm = $this->formFactory->create(HotelType::class);
        $searchForm->submit([
            'price_from' => 12,
            'price_to' => 13,
        ]);

        $this->assertTrue($searchForm->isValid());
    }

    public function testSubmitWithNotValidPrice()
    {
        $searchForm = $this->formFactory->create(HotelType::class);
        $searchForm->submit([
            'price_from' => 'testFrom',
            'price_to' => 'testTo',
        ]);

        $this->assertNotTrue($searchForm->isValid());
    }

    public function testSubmitWithName()
    {
        $searchForm = $this->formFactory->create(HotelType::class);
        $searchForm->submit([
            'name' => 'testName'
        ]);
        $this->assertTrue($searchForm->isValid());
    }

    public function testSubmitWithCity()
    {
        $searchForm = $this->formFactory->create(HotelType::class);
        $searchForm->submit([
            'city' => 'test'
        ]);

        $this->assertTrue($searchForm->isValid());
    }

    public function testSubmitWithSotBy()
    {
        $searchForm = $this->formFactory->create(HotelType::class);
        $searchForm->submit([
            'sort_by' => 'test'
        ]);

        $this->assertTrue($searchForm->isValid());
    }
}
