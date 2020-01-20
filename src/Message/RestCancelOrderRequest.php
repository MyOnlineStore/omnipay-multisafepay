<?php
declare(strict_types=1);

namespace Omnipay\MultiSafepay\Message;

/**
 * Cancel an order
 *
 * @link https://docs.multisafepay.com/api/#update-an-order
 */
final class RestCancelOrderRequest extends RestAbstractRequest
{
    public function getData()
    {
        parent::getData();

        $this->validate('transactionId');

        $transactionId = $this->getTransactionId();

        return compact('transactionId');
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $httpResponse = $this->sendRequest(
            'PATCH',
            '/orders/' . $data['transactionId'],
            '{"status":"cancelled"}'
        );

        $this->response = new RestCancelOrderResponse(
            $this,
            json_decode($httpResponse->getBody()->getContents(), true)
        );

        return $this->response;
    }
}
