<?php

namespace Digikraaft\PaystackCustomersTile;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class PaystackCustomersTileComponent extends Component
{
    use WithPagination;

    /** @var string */
    public string $position;

    /** @var string|null */
    public ?string $title;

    public $perPage;

    /** @var int|null */
    public ?int $refreshInSeconds;

    public function mount(string $position, $perPage = 5, ?string $title = null, int $refreshInSeconds = null)
    {
        $this->position = $position;
        $this->perPage = $perPage;
        $this->title = $title;
        $this->refreshInSeconds = $refreshInSeconds;
    }

    public function render()
    {
        $customers = collect(PaystackCustomersStore::make()->getData());
        $paginator = $this->getPaginator($customers);

        return view('dashboard-paystack-customers-tile::tile', [
            'customers' => $customers->skip(($paginator->firstItem() ?? 1) - 1)->take($paginator->perPage()),
            'paginator' => $paginator,
            'refreshIntervalInSeconds' => $this->refreshInSeconds ?? config('dashboard.tiles.paystack.customers.refresh_interval_in_seconds') ?? 1800,
        ]);
    }

    public function getPaginator(Collection $customers): LengthAwarePaginator
    {
        return new LengthAwarePaginator($customers, $customers->count(), $this->perPage, $this->page);
    }

    public function paginationView()
    {
        return 'dashboard-paystack-customers-tile::pagination';
    }
}
