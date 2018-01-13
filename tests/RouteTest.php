<?php

class RouteTest extends TestCase
{
    /*
     * Just a few quick tests for validation etc.
     *
     * ( I am aware that some of these tests loop over
     * and therefore could be merged into a single function
     * for efficiency. However, I left them like this for
     * clarity )
     *
     */

    public function testRoot()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
    }

    public function testError404()
    {
        $response = $this->call('GET', '/fake/route');
        $this->assertTrue($response->isRedirect());
        // $this->assertEquals(404,$response->getStatusCode());
    }

    public function testEmptySubmit()
    {
        $response = $this->visit('/')->press('Download');
        $this->assertContains('Looks like you forgot to enter something',$response->response.$this->toString());
    }

    public function testCharacterButNoSelect()
    {
        $response = $this->visit('/')->type('hulk','character')->press('Download');
        $this->assertContains('Looks like you forgot to enter something',$response->response.$this->toString());
    }

    public function testNoCharacterButSelect()
    {
       $options = [
           'comics','events','series','stories'
       ];

       foreach($options as $option)
       {
           $response = $this->visit('/')->select($option, 'type')->press('Download');
           $this->assertContains('Looks like you forgot to enter something', $response->response . $this->toString());
       }
    }

    public function testEmptyString()
    {

        $options = [
            'comics','events','series','stories'
        ];

        foreach($options as $option)
        {
            $response = $this->visit('/')
                ->type('           ','character')
                ->select($option, 'type')
                ->press('Download');
            $this->assertContains('Looks like we could not find this character', $response->response . $this->toString());
        }
    }

    public function testRandomAsciiCharacters()
    {

        $options = [
            'comics','events','series','stories'
        ];

        foreach($options as $option)
        {
            $response = $this->visit('/')
                ->type('@@£$£@$£$%£$%$$%','character')
                ->select($option, 'type')
                ->press('Download');
            $this->assertContains('Looks like we could not find this character', $response->response . $this->toString());
        }
    }

    public function testEmojiInjection()
    {

        $options = [
            'comics','events','series','stories'
        ];

        foreach($options as $option)
        {
            $response = $this->visit('/')
                ->type('🌑🌙👨‍❤️‍👨','character')
                ->select($option, 'type')
                ->press('Download');
            $this->assertContains('Looks like we could not find this character', $response->response . $this->toString());
        }
    }


    public function testValidStringNotCharacter()
    {
        $options = [
            'comics','events','series','stories'
        ];

        foreach($options as $option)
        {
            $response = $this->visit('/')
                ->type('SpideyMizman','character')
                ->select($option, 'type')
                ->press('Download');
            $this->assertContains('Looks like we could not find this character', $response->response . $this->toString());
        }
    }
}