<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay;

use Omnipay\Common\Item as CommonItem;

final class Item extends CommonItem
{
    /**
     * Unique ID for the provided item
     */
    public function getId(): ?string
    {
        return $this->getParameter('id');
    }

    public function setId(string $id): self
    {
        return $this->setParameter('id', $id);
    }

    /**
     * Tax rate eg: 0.21
     */
    public function getTaxRate(): ?string
    {
        return $this->getParameter('taxRate');
    }

    public function setTaxRate(string $taxRate): self
    {
        return $this->setParameter('taxRate', $taxRate);
    }
}
