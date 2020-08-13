<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * MultiSafepay Rest Api Complete Purchase Request.
 *
 * ### Example
 *
 * <code>
 *   $transaction = $gateway->completePurchase();
 *   $transaction->setTransactionId($transactionId);
 *   $response = $transaction->send();
 *   print_r($response->getData());
 * </code>
 */
class CompletePurchaseRequest extends Request
{
    /**
     * Get the required data from the API request.
     *
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
     * Send the API request.
     *
     * @param mixed $data
     *
     * @throws InvalidRequestException
     */
    public function sendData($data): CompletePurchaseResponse
    {
        $httpResponse = $this->sendRequest(
            'get',
            '/orders/'.$data['transactionId']
        );

        $this->response = new CompletePurchaseResponse(
            $this,
            \json_decode($httpResponse->getBody()->getContents(), true)
        );

        return $this->response;
    }
}
