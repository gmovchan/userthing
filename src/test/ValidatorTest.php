<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppUserthing\test;

require_once __DIR__ . '/../../vendor/autoload.php';

//require_once __DIR__ . '/../UserStore.php';
use AppUserthing\userthing\persist\UserStore;
//require_once __DIR__ . '/../Validator.php';
use AppUserthing\userthing\util\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Description of ValidatorTest
 *
 * @author grigory
 */
class ValidatorTest extends TestCase
{

    private $validator;

    public function setUp()
    {
        $store = new UserStore;
        $store->addUser("bob williams", "bob@example.com", "12345");
        //print_r($store->getUser("bob@example.com"));
        $this->validator = new Validator($store);
    }

    public function tearDown()
    {
        
    }

    public function testValidateCurrectPass()
    {
        $this->assertTrue(
                $this->validator->validateUser("bob@example.com", "12345"), "Ожидалась успешная проверка."
        );
    }

    /*
     * Проверка метода, который должен вызываться объектом Validator при вводе
     *  пользователем неправильного пароля.
     */

    public function testValidateFalsePass()
    {
        //$store = $this->createMock("UserStore");
        $store = $this->getMockBuilder("AppUserthing\userthing\persist\UserStore")
                ->setMethods(['notifyPasswordFailure', 'getUser'])
                ->getMock();

        //$user = $this->createMock("AppUserthing\userthing\domain\User");
        $user = $this->getMockBuilder("AppUserthing\userthing\domain\User")
                ->disableOriginalConstructor()
                ->setMockClassName("User")
                ->getMock();

        $user->expects($this->any())
                ->method('getPass')
                ->will($this->returnValue("12345"));

        $this->validator = new Validator($store);

        $store->expects($this->once())
                ->method('notifyPasswordFailure')
                ->with($this->equalTo('bob@example.com'));

        $store->expects($this->any())
                ->method("getUser")
                ->will($this->returnValue($user));

        $this->validator->validateUser("bob@example.com", "wrong");
    }

}
