<?php

namespace Digikraaft\PaystackCustomersTile;

use Spatie\Dashboard\Models\Tile;

class PaystackCustomersStore
{
    private Tile $tile;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName("paystackCustomers");
    }

    public function setData(array $data): self
    {
        $this->tile->putData('paystack.customers', $data);

        return $this;
    }

    public function getData(): array
    {
        return $this->tile->getData('paystack.customers') ?? [];
    }
}
