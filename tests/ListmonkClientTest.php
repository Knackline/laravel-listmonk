<?php

namespace Knackline\Listmonk\Tests;

use Knackline\Listmonk\ListmonkClient;
use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Http;

class ListmonkClientTest extends TestCase
{
    protected $client;
    protected $mockResponse;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->client = new ListmonkClient('https://listmonk.test', 'admin', 'admin');
        
        $this->mockResponse = [
            'data' => [],
            'message' => 'Success'
        ];
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateSubscriber()
    {
        Http::fake([
            'listmonk.test/api/*' => Http::response($this->mockResponse, 200)
        ]);

        $response = $this->client->createSubscriber([
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);

        $this->assertEquals($this->mockResponse, $response);
    }

    public function testGetSubscribers()
    {
        $mockResponse = ['data' => [['id' => 1, 'email' => 'test@example.com']]];
        
        Http::fake([
            'listmonk.test/api/*' => Http::response($mockResponse, 200)
        ]);

        $response = $this->client->getSubscribers();
        
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('test@example.com', $response['data'][0]['email']);
    }

    public function testCreateList()
    {
        Http::fake([
            'listmonk.test/api/*' => Http::response($this->mockResponse, 200)
        ]);

        $response = $this->client->createList([
            'name' => 'Test List',
            'type' => 'public'
        ]);

        $this->assertEquals($this->mockResponse, $response);
    }

    public function testGetLists()
    {
        $mockResponse = ['data' => [['id' => 1, 'name' => 'Test List']]];
        
        Http::fake([
            'listmonk.test/api/*' => Http::response($mockResponse, 200)
        ]);

        $response = $this->client->getLists();
        
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('Test List', $response['data'][0]['name']);
    }

    public function testCreateCampaign()
    {
        Http::fake([
            'listmonk.test/api/*' => Http::response($this->mockResponse, 200)
        ]);

        $response = $this->client->createCampaign([
            'name' => 'Test Campaign',
            'subject' => 'Test Subject',
            'lists' => [1],
            'type' => 'regular'
        ]);

        $this->assertEquals($this->mockResponse, $response);
    }

    public function testSendCampaign()
    {
        Http::fake([
            'listmonk.test/api/*' => Http::response($this->mockResponse, 200)
        ]);

        $response = $this->client->sendCampaign(1);
        
        $this->assertEquals($this->mockResponse, $response);
    }

    public function testGetTemplates()
    {
        $mockResponse = ['data' => [['id' => 1, 'name' => 'Test Template']]];
        
        Http::fake([
            'listmonk.test/api/*' => Http::response($mockResponse, 200)
        ]);

        $response = $this->client->getTemplates();
        
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('Test Template', $response['data'][0]['name']);
    }
}
