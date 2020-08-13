<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests;

use MyOnlineStore\Omnipay\MultiSafepay\Item;
use PHPUnit\Framework\TestCase;

final class ItemTest extends TestCase
{
    public function testTaxRate(): void
    {
        $item = new Item();
        self::assertNull($item->getTaxRate());

        $item->setTaxRate('0.21');
        self::assertSame('0.21', $item->getTaxRate());

        self::assertSame('0.06', (new Item(['taxRate' => '0.06']))->getTaxRate());
    }

    public function testId(): void
    {
        $item = new Item();
        self::assertNull($item->getId());

        $item->setId('uuid-123-546');
        self::assertSame('uuid-123-546', $item->getId());

        self::assertSame('uuid-654-321', (new Item(['id' => 'uuid-654-321']))->getId());
    }
}
