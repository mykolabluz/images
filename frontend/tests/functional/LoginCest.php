<?php 

namespace frontend\tests\functional;


use frontend\tests\FunctionalTester;
use frontend\tests\fixtures\UserFixture;

class LoginCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
            ],
        ]);
    }
    
    public function checkLoginWorking(FunctionalTester $I)
    {
        $I->amOnRoute('user/default/login');
        
        $formParams = [
            'LoginForm[email]' => '1@gmail.com',
            'LoginForm[password]' => '123456',
        ];
        
        $I->submitForm('#login-form', $formParams);
        
        $I->see('Alex', 'form button[type=submit]');
    }
    
    public function checkLoginWrong(FunctionalTester $I)
    {
        $I->amOnRoute('user/default/login');
        
        $formParams = [
            'LoginForm[email]' => '1@gmail.com',
            'LoginForm[password]' => 'wrong',
        ];
        
        $I->submitForm('#login-form', $formParams);
        
        $I->seeValidationError('Incorrect email or password');
    }

}
