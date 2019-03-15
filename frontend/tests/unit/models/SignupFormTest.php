<?php 

namespace frontend\tests\models;

use frontend\modules\user\models\SignupForm;
use frontend\tests\fixtures\UserFixture;

class SignupFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;
    
    public function _before() 
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
            ],
        ]);
    }

        public function testTrimUsername()
    {
        $model = new SignupForm([
            'username' => ' some_username  ',
            'email' => 'some_email@example.com',
            'password' => '12345',
        ]);
        
        $model->signup();
        
        expect($model->username)->equals('some_username');
    }
    
    public function testUsernameRequired()
    {
        $model = new SignupForm([
            'username' => '',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
        ]);
        
        $model->signup();
        
        expect($model->getFirstError('username'))->equals('Username cannot be blank.');
    }
    
    public function testUsernameTooShort()
    {
        $model = new SignupForm([
            'username' => 'w',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
        ]);
        
        $model->signup();
        
        expect($model->getFirstError('username'))->equals('Username should contain at least 2 characters.');
    }
    
    public function testPasswordRequired()
    {
        $model = new SignupForm([
            'username' => 'some_username',
            'email' => 'some_email@example.com',
            'password' => '',
        ]);
        
        $model->signup();
        
        expect($model->getFirstError('password'))->equals('Password cannot be blank.');
    }
    
    public function testEmailUnique()
    {
        $model = new SignupForm([
            'username' => 'some_username',
            'email' => 'mukolakolia@gmail.com',
            'password' => 'some_password',
        ]);
        
        $model->signup();
        
        sleep(15);
        
        expect($model->getFirstError('email'))->equals('This email address has already been taken.');
    }
}