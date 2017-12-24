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

use PHPUnit\Framework\TestCase;

/**
 * Description of UserStoreTest
 *
 * @author grigory
 */
class UserStoreTest extends TestCase
{

    private $store;
    
    /**
     * @covers AppUserthing\userthing\persist\UserStore::__construct
     * 
     * 
     * @covers AppUserthing\test\UserStoreTest::setUp
     */
    public function setUp()
    {
        $this->store = new UserStore;
    }

    /*
     * 
     */
    public function tearDown()
    {
        
    }
    
    /**
     * @covers AppUserthing\userthing\persist\UserStore::addUser
     * @covers AppUserthing\userthing\persist\UserStore::getUser
     * @covers AppUserthing\userthing\domain\User::getMail
     * @covers AppUserthing\userthing\domain\User::getName
     * @covers AppUserthing\userthing\domain\User::getPass
     * @covers AppUserthing\userthing\domain\User::__construct
     * 
     * @covers AppUserthing\test\UserStoreTest::setUp
     * @covers AppUserthing\test\UserStoreTest::tearDown
     * @covers AppUserthing\test\UserStoreTest::testGetUser
     */
    public function testGetUser()
    {
        $this->store->addUser("bob williams", "a@b.com", "12345");
        $user = $this->store->getUser("a@b.com");
        $this->assertEquals($user->getMail(), "a@b.com");
        $this->assertEquals($user->getName(), "bob williams");
        $this->assertEquals($user->getPass(), "12345");
    }

    /**
     * Проверяет создает ли метод исключение если пароль меньше 5 символов.
     * @covers AppUserthing\userthing\domain\User::__construct
     * @covers AppUserthing\userthing\persist\UserStore::addUser
     * @expectedException
     * 
     * @covers AppUserthing\test\UserStoreTest::setUp
     * @covers AppUserthing\test\UserStoreTest::tearDown
     * @covers AppUserthing\test\UserStoreTest::testAddUserShortPass
     */
    public function testAddUserShortPass()
    {
        $this->expectException('Exception');
        $this->store->addUser("bob williams", "bob@example.com", "ff");
    }

    /**
     * Объект UserStore не должен разрешать добавлять одинаковые адреса почты.
     * @covers AppUserthing\userthing\persist\UserStore::addUser
     * @covers AppUserthing\userthing\persist\UserStore::getUser
     * @covers AppUserthing\userthing\domain\User::__construct
     * @expectedException 
     * 
     * @covers AppUserthing\test\UserStoreTest::setUp
     * @covers AppUserthing\test\UserStoreTest::tearDown
     * @covers AppUserthing\test\UserStoreTest::testAddUserDublicate
     */
    public function testAddUserDublicate()
    {
        try {
            $ret = $this->store->addUser("bob williams", "a@b.com", "12345");
            $ret = $this->store->addUser("bob stevens", "a@b.com", "12345");
            self::fail("Здесь должно быть вызвано исключение.");
        } catch (\Exception $exc) {
            $const = $this->logicalAnd(
                    $this->logicalNot($this->contains("bob stevens")), $this->isType('object')
            );
            self::assertThat($this->store->getUser("a@b.com"), $const);
        }
    }

}
