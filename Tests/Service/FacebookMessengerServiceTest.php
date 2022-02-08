<?php

namespace Erelke\FacebookMessengerBundle\Tests\Service;

use Facebook\Exceptions\FacebookSDKException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Erelke\FacebookMessengerBundle\Core\Configuration\GetStartedConfiguration;
use Erelke\FacebookMessengerBundle\Core\Configuration\GreetingTextConfiguration;
use Erelke\FacebookMessengerBundle\Core\Entity\Recipient;
use Erelke\FacebookMessengerBundle\Core\Message;
use Erelke\FacebookMessengerBundle\Exception\FacebookMessengerException;
use Erelke\FacebookMessengerBundle\Service\FacebookMessengerService;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request as SymRequest;

/**
 * Class FacebookMessengerServiceTest.
 */
class FacebookMessengerServiceTest extends TestCase
{
    /**
     * @var LoggerInterface|MockObject
     */
    protected $logger;

    /**
     * @var array
     */
    protected $requestContainer = [];

    /**
     * @var MockHandler
     */
    protected $clientHandler;

    /**
     * @var FacebookMessengerService
     */
    protected $messengerService;

    /**
     * @throws FacebookSDKException
     */
    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->clientHandler = new MockHandler();
        $this->requestContainer = [];

        $history = Middleware::history($this->requestContainer);
        $stack = HandlerStack::create($this->clientHandler);
        $stack->push($history);

        $this->messengerService = new FacebookMessengerService(
            'app.id',
            'app.secret',
            new NullLogger(),
            new Client(['handler' => $stack])
        );
        $this->messengerService->setAccessToken('access.token');
    }

    /**
     * @throws FacebookSDKException
     */
    public function testPostMessage()
    {
        $this->clientHandler->append(new Response());
        $this->messengerService->postMessage(
            new Recipient(1),
            new Message('Test'),
            FacebookMessengerService::MSG_TYPE_RESPONSE
        );

        /** @var Request $request */
        $request = $this->requestContainer[0]['request'];
        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/v2.10/me/messages', $request->getUri()->getPath());

        parse_str($request->getBody(), $requestBody);
        self::assertEquals('{"id":1,"phone_number":null}', $requestBody['recipient']);
        self::assertEquals('{"text":"Test","attachment":null}', $requestBody['message']);
        self::assertEquals('RESPONSE', $requestBody['type']);
        self::assertEquals('access.token', $requestBody['access_token']);
    }

    /**
     * @throws FacebookSDKException
     */
    public function testGetUser()
    {
        $this->clientHandler->append(new Response(
            200,
            [],
            json_encode(['first_name' => 'Unit', 'last_name' => 'Test'])
        ));
        $result = $this->messengerService->getUser(4001);

        /** @var Request $request */
        $request = $this->requestContainer[0]['request'];
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/v2.10/4001', $request->getUri()->getPath());

        self::assertEquals('Unit', $result['first_name']);
        self::assertEquals('Test', $result['last_name']);
    }

    /**
     * @throws FacebookSDKException
     */
    public function testGetPsid()
    {
        $this->clientHandler->append(new Response(
            200,
            [],
            json_encode(['id' => 'PAGE_ID', 'recipient' => 'PSID'])
        ));
        $result = $this->messengerService->getPsid('linking_token');

        /** @var Request $request */
        $request = $this->requestContainer[0]['request'];
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/v2.10/me', $request->getUri()->getPath());

        parse_str($request->getUri()->getQuery(), $requestParams);
        self::assertEquals('linking_token', $requestParams['account_linking_token']);
        self::assertEquals('recipient', $requestParams['fields']);

        self::assertEquals('PAGE_ID', $result['id']);
        self::assertEquals('PSID', $result['recipient']);
    }

    /**
     * @throws FacebookSDKException
     */
    public function testUnlinkAccount()
    {
        $this->clientHandler->append(new Response(
            200,
            [],
            json_encode(['result' => 'unlink account success'])
        ));
        $result = $this->messengerService->unlinkAccount(123456);

        /** @var Request $request */
        $request = $this->requestContainer[0]['request'];
        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/v2.10/me/unlink_accounts', $request->getUri()->getPath());

        parse_str($request->getBody(), $requestParams);
        self::assertEquals(123456, $requestParams['psid']);
        self::assertEquals('unlink account success', $result['result']);
    }

    /**
     * @throws FacebookSDKException
     */
    public function testGetStarted()
    {
        $this->clientHandler->append(new Response());

        $getStarted = new GetStartedConfiguration();
        $getStarted->setPayload('payload');
        $this->messengerService->setGetStarted($getStarted);

        /** @var Request $request */
        $request = $this->requestContainer[0]['request'];
        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/v2.10/me/messenger_profile', $request->getUri()->getPath());

        parse_str($request->getBody(), $requestParams);
        self::assertEquals('{"payload":"payload"}', $requestParams['get_started']);
    }

    /**
     * @throws FacebookSDKException
     */
    public function testGreetingText()
    {
        $this->clientHandler->append(new Response());

        $greeting = new GreetingTextConfiguration();
        $greeting->setText('Hello');
        $greeting->setLocale('en_US');
        $this->messengerService->setGreetingText($greeting);

        /** @var Request $request */
        $request = $this->requestContainer[0]['request'];
        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/v2.10/me/messenger_profile', $request->getUri()->getPath());

        parse_str($request->getBody(), $requestParams);
        self::assertEquals(['{"text":"Hello","locale":"en_US"}'], $requestParams['greeting']);
    }

    /**
     * Test if null is returned when there is no hub_mode set in the request
     *
     * @throws FacebookMessengerException
     */
    public function testEmptyVerificationToken()
    {
        $request = $this->createMock(SymRequest::class);

        $request->expects($this->exactly(1))
            ->method('get')
            ->withConsecutive(['hub_mode'])
            ->willReturnOnConsecutiveCalls(null);

        $challenge = $this->messengerService->handleVerificationToken($request, '12345');
        self::assertNull($challenge);
    }

    /**
     * Test if an exception is thrown when the verification token is incorrect
     */
    public function testInvalidVerificationToken()
    {
        $request = $this->createMock(SymRequest::class);

        $request->expects($this->exactly(3))
            ->method('get')
            ->withConsecutive(
                ['hub_mode'],
                ['hub_mode'],
                ['hub_verify_token']
            )
            ->willReturnOnConsecutiveCalls(
                'subscribe',
                'subscribe',
                '12345'
            );
	    $this->expectException(FacebookMessengerException::class);
        $this->messengerService->handleVerificationToken($request, '98765');
    }

    /**
     * Test a valid verification token
     *
     * @throws FacebookMessengerException
     */
    public function testValidVerificationToken()
    {
        /** @var SymRequest|MockObject $request */
        $request = $this->getMockBuilder(SymRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->exactly(4))
            ->method('get')
            ->withConsecutive(
                ['hub_mode'],
                ['hub_mode'],
                ['hub_verify_token'],
                ['hub_challenge']
            )
            ->willReturnOnConsecutiveCalls(
                'subscribe',
                'subscribe',
                '12345',
                'challenge_code'
            );

        $challenge = $this->messengerService->handleVerificationToken($request, '12345');
        self::assertEquals('challenge_code', $challenge);
    }

    /**
     * @throws FacebookSDKException
     */
    public function testAddMessageToBatchLimit()
    {
        for ($i = 1; $i < 60; $i++) {
            $result = $this->messengerService->addMessageToBatch(new Recipient(1), new Message('test'));
            self::assertEquals($i <= 50, $result);
        }
    }

    /**
     * @throws FacebookSDKException
     * @throws FacebookMessengerException
     */
    public function testSendBatchRequestsException()
    {
	    $this->expectException(FacebookMessengerException::class);
        // No batch requests added
        $this->messengerService->sendBatchRequests();
    }

    /**
     * @throws FacebookSDKException
     * @throws FacebookMessengerException
     */
    public function testSendBatchRequests()
    {
        $this->messengerService->addMessageToBatch(new Recipient(2), new Message('test1'));
        $this->messengerService->addMessageToBatch(new Recipient(4), new Message('test2'));
        $this->messengerService->addMessageToBatch(new Recipient(6), new Message('test3'));
        $this->messengerService->addMessageToBatch(new Recipient(8), new Message('test4'));

        $this->clientHandler->append(new Response(
            200,
            [],
            json_encode([
                [
                    'code' => 200,
                ],
                [
                    'code' => 500,
                    'body' => 'error'
                ],
                [
                    'code' => 200
                ],
                [
                    'code' => 501,
                    'body' => '{"error":{"message":"(#100) No matching user found"}}'
                ]
            ])
        ));

        $result = $this->messengerService->sendBatchRequests();

        /** @var Request $request */
        $request = $this->requestContainer[0]['request'];
        self::assertEquals('POST', $request->getMethod());
        self::assertEquals('/v2.10', $request->getUri()->getPath());

        parse_str($request->getBody(), $requestParams);
        self::assertCount(4, json_decode($requestParams['batch']));

        self::assertCount(2, $result);
        self::assertEquals(500, $result[0]->getResponseCode());
        self::assertEquals('error', $result[0]->getResponseBody());
        self::assertEquals(4, $result[0]->getPsid());
        self::assertEquals(501, $result[1]->getResponseCode());
        self::assertEquals('{"error":{"message":"(#100) No matching user found"}}', $result[1]->getResponseBody());
        self::assertEquals(8, $result[1]->getPsid());
    }
}
