<?php namespace App\Tests;
use App\Tests\AcceptanceTester;
use Codeception\Example;


class IndexPageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function loginContent(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('username', 'user');
        $I->fillField('password', 'user');
        $I->click('Login');
    }

    public function adminloginContent(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('username', 'admin');
        $I->fillField('password', 'admin');
        $I->click('Login');
    }

    public function IndexPageContent(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('A student polling website');
        $I->click('About');
        $I->seeInCurrentUrl('/about');
        $I->see('About');
    }

    public function SitemapPageContent(AcceptanceTester $I)
    {
        $I->amOnPage('/sitemap');
        $I->see('Public Pages:');
        $I->click('About');
        $I->seeInCurrentUrl('/about');
        $I->see('About');
    }

    /**
     * @example(url="/", text="Ending Soon")
     * @example(url="/about", text="About")
     */
    public function staticPageContent(AcceptanceTester $I, Example $example)
    {
        $I->amOnPage($example['url']);
        $I->see($example['text']);
    }


}
