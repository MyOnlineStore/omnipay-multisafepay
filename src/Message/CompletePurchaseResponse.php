<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

/**
 * MultiSafepay Rest Api Complete Purchase Response.
 *
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
final class CompletePurchaseResponse extends FetchTransactionResponse
{
    public function isSuccessful(): bool
    {
        return 'completed' === $this->getPaymentStatus();
    }
}
