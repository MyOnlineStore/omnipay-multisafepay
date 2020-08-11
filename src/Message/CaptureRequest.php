<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Capture an order
 *
 * @link https://docs.multisafepay.com/api/#update-an-order
 * @link https://docs.multisafepay.com/payment-methods/billing-suite/klarna/#activate-an-order
 */
final class CaptureRequest extends Request
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
            \sprintf('/orders/%s', $data['transactionId']),
            '{"status":"shipped"}'
        );

        $this->response = new CaptureResponse(
            $this,
            \json_decode($httpResponse->getBody()->getContents(), true)
        );

        return $this->response;
    }
}
