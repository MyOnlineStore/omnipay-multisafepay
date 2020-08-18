<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Cancel an order
 *
 * @link https://docs.multisafepay.com/api/#update-an-order
 */
final class CancelOrderRequest extends Request
{
    /**
     * @return mixed[]
     *
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionId');

        return ['transactionId' => $this->getTransactionId()];
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function sendData($data)
    {
        $httpResponse = $this->sendRequest(
            'PATCH',
            '/orders/'.$data['transactionId'],
            '{"status":"cancelled"}'
        );

        $this->response = new CancelOrderResponse(
            $this,
            \json_decode($httpResponse->getBody()->getContents(), true)
        );

        return $this->response;
    }
}
