<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

/**
 * MultiSafepay Rest Api Fetch Transaction Response.
 *
 * To get information about a previous processed transaction, MultiSafepay provides
 * the /orders/{order_id} resource. This resource can be used to query the details
 * about a specific transaction.
 *
 * <code>
 *   // Fetch the transaction.
 *   $transaction = $gateway->fetchTransaction();
 *   $transaction->setTransactionId($transactionId);
 *   $response = $transaction->send();
 *   print_r($response->getData());
 * </code>
 *
 * @link https://www.multisafepay.com/documentation/doc/API-Reference
 */
class FetchTransactionResponse extends Response
{
    /**
     * Is the response successful?
     */
    public function isSuccessful(): bool
    {
        /** @psalm-suppress RedundantConditionGivenDocblockType */

        return parent::isSuccessful() && null !== $this->getTransactionId();
    }

    /**
     * Is the payment created, but uncompleted?
     */
    public function isInitialized(): bool
    {
        return 'initialized' === $this->getPaymentStatus();
    }

    /**
     * Is the payment created, but not yet exempted (credit cards)?
     */
    public function isUncleared(): bool
    {
        return 'uncleared' === $this->getPaymentStatus();
    }

    /**
     * Is the payment cancelled?
     */
    public function isCancelled(): bool
    {
        return 'canceled' === $this->getPaymentStatus();
    }

    /**
     * Is the payment rejected?
     */
    public function isDeclined(): bool
    {
        return 'declined' === $this->getPaymentStatus();
    }

    /**
     * Is the payment refunded?
     */
    public function isRefunded(): bool
    {
        return 'refunded' === $this->getPaymentStatus() ||
            'chargedback' === $this->getPaymentStatus();
    }

    /**
     * Is the payment expired?
     */
    public function isExpired(): bool
    {
        return 'expired' === $this->getPaymentStatus();
    }

    /**
     * Get raw payment status.
     */
    public function getPaymentStatus(): ?string
    {
        return $this->data['data']['status'] ?? null;
    }
}
