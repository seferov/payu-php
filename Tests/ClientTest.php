<?php

namespace Dugun\Component\PayU\Tests;

use Seferov\PayU\Client;
use Seferov\PayU\Request\Token;

/**
 * Class ClientTest.
 *
 * @author Farhad Safarov <farhad.safarov@gmail.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidClientCredentials()
    {
        $client = new Client('CODE', 'SECRET');

        $tokenRequest = new Token();
        $tokenRequest->getInfo('123456');

        $response = $client->request($tokenRequest);

        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals(600, $response['code']);
        $this->assertSame('Invalid merchant', $response['message']);
    }
}
