<?php
/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/23/18
 * Time: 5:22 PM
 */

namespace WordInSyllable\tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp;

class ApiTests extends TestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://application.local/'
        ]);
    }

    public function testPOST()
    {
        $response = $this->client->post('/word/', [
            'json' => [
                'word' => 'hovercraft2',
                'syllableword' => 'ho-ver-craft2'
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        //$this->assertEquals($response, $data);

    }

    public function testDeleteError()
    {
//        $response = $this->client->delete('/books/random-book', [
//            'http_errors' => false
//        ]);
//
//        $this->assertEquals(405, $response->getStatusCode());
    }
}