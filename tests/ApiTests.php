<?php
/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/23/18
 * Time: 5:22 PM
 */

namespace WordInSyllable\tests;

use PHPUnit\Framework\TestCase;

class ApiTests  extends TestCase
{
    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://application.local/'
        ]);
    }


    public function testPOST()
    {
        // create our http client (Guzzle)
        $client = new Client('http://application.local', array(
            'request.options' => array(
                'exceptions' => false,
            )
        ));

        $jsonData = [
            ['word' => "hovercraft", 'syllableWord' => "ho-ver-craft"],
            ['word' => "otherWord", 'syllableWord' => null]
        ];

        $request = $client->post('/api/programmers', null, json_encode($jsonData));
        $response = $request->send();

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        //$data = json_decode($response->getBody(true), true);
        //$this->assertArrayHasKey('nickname', $data);
    }

    public function testPOST2()
    {
        //$bookId = uniqid();

        $response = $this->client->post('/word/', [
            'json' => [
                'word' => 'hovercraft2',
                'syllableword' => 'ho-ver-craft2'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        //$this->assertEquals($bookId, $data['bookId']);
    }
}