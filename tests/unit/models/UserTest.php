<?php

namespace tests\unit\models;

use app\models\Task;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        expect_that($user = Task::findIdentity(100));
        expect($user->username)->equals('admin');

        expect_not(Task::findIdentity(999));
    }

    public function testFindUserByAccessToken()
    {
        expect_that($user = Task::findIdentityByAccessToken('100-token'));
        expect($user->username)->equals('admin');

        expect_not(Task::findIdentityByAccessToken('non-existing'));
    }

    public function testFindUserByUsername()
    {
        expect_that($user = Task::findByUsername('admin'));
        expect_not(Task::findByUsername('not-admin'));
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser($user)
    {
        $user = Task::findByUsername('admin');
        expect_that($user->validateAuthKey('test100key'));
        expect_not($user->validateAuthKey('test102key'));

        expect_that($user->validatePassword('admin'));
        expect_not($user->validatePassword('123456'));        
    }

}
