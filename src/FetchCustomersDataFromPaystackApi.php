<?php

namespace Digikraaft\PaystackCustomersTile;

use Digikraaft\Paystack\Customer;
use Digikraaft\Paystack\Paystack;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FetchCustomersDataFromPaystackApi extends Command
{
    protected $signature = 'dashboard:fetch-customers-data-from-paystack-api';

    protected $description = 'Fetch data for paystack Customer tile';

    public function handle()
    {
        Paystack::setApiKey(config('dashboard.tiles.paystack.secret_key', env('PAYSTACK_SECRET')));

        $this->info('Fetching Paystack customers ...');

        $customers = Customer::list(
            config('dashboard.tiles.paystack.customers.params') ?? [
                'perPage' => 5,
            ]
        );

        $customers = collect($customers->data)
            ->map(function ($customer) {
                return [
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'customer_code' => $customer->customer_code,
                    'email' => $customer->email,
                    'id' => $customer->id,
                    'createdAt' => Carbon::parse($customer->createdAt)
                        ->setTimezone('UTC')
                        ->format("d.m.Y"),
                ];
            })->toArray();

        PaystackCustomersStore::make()->setData($customers);

        $this->info('All done!');

        return 0;
    }
}
