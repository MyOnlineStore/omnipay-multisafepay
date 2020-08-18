<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Item;
use MyOnlineStore\Omnipay\MultiSafepay\Message\AuthorizeRequest;
use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

final class AuthorizeRequestTest extends TestCase
{
    /** @var AuthorizeRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new AuthorizeRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize(
            [
                'apiKey' => '123456789',
                'amount' => '595',
                'currency' => 'eur',
                'description' => 'Test transaction',
                'cancel_url' => 'http://localhost/cancel',
                'notify_url' => 'http://localhost/notify',
                'return_url' => 'http://localhost/return',
                'close_window' => false,
                'days_active' => 3,
                'send_mail' => true,
                'gateway' => 'IDEAL',
                'google_analytics_code' => '123456789',
                'manual' => false,
                'transactionId' => 'TEST-TRANS-1',
                'type' => 'redirect',
                'var1' => 'extra data 1',
                'var2' => 'extra data 2',
                'var3' => 'extra data 3',
                'items' => [
                    new Item(
                        [
                            'id' => 'ff75c5f4-1a54-437c-abf1-b236cd474c08',
                            'name' => 'Foo',
                            'description' => 'FooBar',
                            'quantity' => '1',
                            'price' => '5.95',
                            'taxRate' => '0.21',
                        ]
                    ),
                    new Item(
                        [
                            'id' => '6c334d4a-92f5-4943-b104-4a72a6962036',
                            'name' => 'Bar',
                            'description' => 'BarFoo',
                            'quantity' => '1',
                            'price' => '17,99',
                            'taxRate' => '0.21',
                        ]
                    ),
                    [
                        'id' => '317d2e03-e784-4bc5-8619-3d633277e65a',
                        'name' => 'Qux',
                        'description' => 'QuxBar',
                        'quantity' => '1',
                        'price' => '345.65',
                        'taxRate' => '0.06',
                    ],
                ],
                'card' => new CreditCard(
                    [
                        'firstName'     => 'Example',
                        'lastName'      => 'Customer',
                        'number'        => '4222222222222222',
                        'expiryMonth'   => '01',
                        'expiryYear'    => '2020',
                        'cvv'           => '123',
                        'email'         => 'customer@example.com',
                        'phone'         => '0612345678',
                        'address1'      => '1 Scrubby Creek Road',
                        'country'       => 'AU',
                        'city'          => 'Scrubby Creek',
                        'postalcode'    => '4999',
                        'state'         => 'QLD',
                        'gender'        => 'M',
                        'birthday'      => '1970-07-10',
                    ]
                ),
            ]
        );
    }

    public function testInitializeWithInvalidItem(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $request = new AuthorizeRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $request->initialize(
            [
                'items' => [new \stdClass()],
            ]
        );
    }

    public function testGetEndpoint(): void
    {
        self::assertSame('https://api.multisafepay.com/v1/json', $this->request->getEndpoint());

        $this->request->setTestMode(true);
        self::assertSame('https://testapi.multisafepay.com/v1/json', $this->request->getEndpoint());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertNull($response->getMessage());
        self::assertNull($response->getCode());

        self::assertEquals(
            'https://testpayv2.multisafepay.com/connect/82Cr8PsD1cGj43XsNGAEM9RE4REYGfwdXIQ/?lang=nl_NL',
            $response->getRedirectUrl()
        );

        self::assertEquals('f4b5ea53-b4e4-4c81-a92e-f7106c7a78d5', $response->getTransactionId());
    }

    public function testFailedWithUnknownError(): void
    {
        $this->setMockHttpResponse('AuthorizeFailedUnknown.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNull($response->getMessage());
        self::assertNull($response->getCode());
    }

    public function testFailedInvalidTax(): void
    {
        $this->setMockHttpResponse('AuthorizeFailedInvalidTax.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertEquals(
            'Totaalbedrag van de winkelwagen moet gelijk zijn aan het bedrag van de transactie',
            $response->getMessage()
        );
        self::assertEquals(1027, $response->getCode());
    }

    public function testDataIntegrity(): void
    {
        self::assertEquals('123456789', $this->request->getGoogleAnalyticsCode());
        self::assertEquals('EUR', $this->request->getCurrency());
        self::assertEquals('extra data 1', $this->request->getVar1());
        self::assertEquals('extra data 2', $this->request->getVar2());
        self::assertEquals('extra data 3', $this->request->getVar3());
        self::assertEquals('http://localhost/cancel', $this->request->getCancelUrl());
        self::assertEquals('http://localhost/notify', $this->request->getNotifyUrl());
        self::assertEquals('http://localhost/return', $this->request->getReturnUrl());
        self::assertEquals('IDEAL', $this->request->getGateway());
        self::assertEquals('redirect', $this->request->getType());
        self::assertEquals('Test transaction', $this->request->getDescription());
        self::assertEquals('TEST-TRANS-1', $this->request->getTransactionId());
        self::assertEquals(595, $this->request->getAmount());
        self::assertEquals(3, $this->request->getDaysActive());
        self::assertFalse($this->request->getCloseWindow());
        self::assertFalse($this->request->getManual());
        self::assertTrue($this->request->getSendMail());

        $data = $this->request->getData();

        self::assertArrayHasKey('shopping_cart', $data);
        self::assertEquals(
            [
                'items' => [
                    [
                        'merchant_item_id' => 'ff75c5f4-1a54-437c-abf1-b236cd474c08',
                        'name' => 'Foo',
                        'description' => 'FooBar',
                        'quantity' => '1',
                        'unit_price' => '5.95',
                        'tax_table_selector' => '0.21',
                    ],
                    [
                        'merchant_item_id' => '6c334d4a-92f5-4943-b104-4a72a6962036',
                        'name' => 'Bar',
                        'description' => 'BarFoo',
                        'quantity' => '1',
                        'unit_price' => '17,99',
                        'tax_table_selector' => '0.21',
                    ],
                    [
                        'merchant_item_id' => '317d2e03-e784-4bc5-8619-3d633277e65a',
                        'name' => 'Qux',
                        'description' => 'QuxBar',
                        'quantity' => '1',
                        'unit_price' => '345.65',
                        'tax_table_selector' => '0.06',
                    ],
                ],
            ],
            $data['shopping_cart']
        );
        self::assertArrayHasKey('checkout_options', $data);
        self::assertEquals(
            [
                'tax_tables' => [
                    'alternate' => [
                        [
                            'name' => '0.21',
                            'rules' => [
                                ['rate' => '0.21'],
                            ],
                        ],
                        [
                            'name' => '0.06',
                            'rules' => [
                                ['rate' => '0.06'],
                            ],
                        ],
                    ],
                ],
            ],
            $data['checkout_options']
        );
        self::assertArrayHasKey('gateway_info', $data);
        self::assertEquals(
            [
                'birthday' => '1970-07-10',
                'email'    => 'customer@example.com',
                'phone'    => '0612345678',
                'gender'   => 'male',
            ],
            $data['gateway_info']
        );
    }
}
