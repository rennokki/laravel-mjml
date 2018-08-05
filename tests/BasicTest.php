<?php

namespace Rennokki\LaravelMJML\Test;

use Rennokki\LaravelMJML\LaravelMJML;

class BasicTest extends TestCase
{
    protected $goodMjml;
    protected $badMjml;
    protected $mustacheMjml;
    protected $mjmlApi;

    public function setUp()
    {
        parent::setUp();

        $this->goodMjml = '<mjml><mj-body><mj-section><mj-column><mj-image width="100" src="/assets/img/logo-small.png"></mj-image><mj-divider border-color="#F45E43"></mj-divider><mj-text font-size="20px" color="#F45E43" font-family="helvetica">Hello World</mj-text></mj-column></mj-section></mj-body></mjml>';
        $this->mustacheMjml = '<mjml><mj-body><mj-section><mj-column><mj-text font-size="20px" color="#F45E43" font-family="helvetica">{{message}}</mj-text></mj-column></mj-section></mj-body></mjml>';
        $this->badMjml = '<h1>wrong</h1>';

        $this->mjmlApi = new LaravelMJML();
        $this->mjmlApi->setAppId(getenv('MJML_APP_ID'))
                      ->setSecretKey(getenv('MJML_SECRET_KEY'));
    }

    public function testRenderFailure()
    {
        $html = $this->mjmlApi->render($this->badMjml);
        $this->assertNull($html);
    }

    public function testRenderSuccess()
    {
        $html = $this->mjmlApi->render($this->goodMjml);
        $this->assertNotNull($html);
    }

    public function testRenderWithMustache()
    {
        $html = $this->mjmlApi->renderWithMustache($this->mustacheMjml, [
            'message' => 'testing',
        ]);

        $this->assertContains('testing', $html);
    }
}
