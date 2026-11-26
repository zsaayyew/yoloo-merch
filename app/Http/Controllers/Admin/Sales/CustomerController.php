<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRequest;
use App\Models\Customer;
use App\Support\PhoneNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PRD-05 FIN - Pelanggan (FIN-FR-011).
 * Pelanggan tidak dihapus (relasi order memakai restrictOnDelete); cukup dinonaktifkan.
 */
class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $search = trim((string) ($filters['q'] ?? ''));
        $like = '%'.addcslashes($search, '\\%_').'%';
        $digits = preg_replace('/\D+/', '', $search) ?? '';
        $phoneLike = strlen($digits) >= 3 ? '%'.PhoneNumber::normalize($search).'%' : null;

        $customers = Customer::query()
            ->withCount(['orders', 'orderRequests'])
            ->withSum(['orders as total_spent' => fn ($q) => $q->where('status', '!=', OrderStatus::Cancelled->value)], 'total')
            ->when($search !== '', function ($q) use ($like, $phoneLike) {
                $q->where(function ($q) use ($like, $phoneLike) {
                    $q->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like);

                    if ($phoneLike) {
                        $q->orWhere('whatsapp_number', 'like', $phoneLike);
                    }
                });
            })
            ->when(($filters['status'] ?? null) === 'active', fn ($q) => $q->where('is_active', true))
            ->when(($filters['status'] ?? null) === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', [
            'customers' => $customers,
            'filters' => $filters,
        ]);
    }

    public function show(Customer $customer): View
    {
        $orders = $customer->orders()->latest('ordered_at')->limit(20)->get();
        $requests = $customer->orderRequests()->latest('submitted_at')->limit(20)->get();

        $stats = [
            'orders' => $customer->orders()->count(),
            'requests' => $customer->orderRequests()->count(),
            'spent' => (int) $customer->orders()
                ->where('status', '!=', OrderStatus::Cancelled->value)
                ->sum('total'),
        ];

        return view('admin.customers.show', compact('customer', 'orders', 'requests', 'stats'));
    }

    public function create(): View
    {
        return view('admin.customers.form', [
            'customer' => new Customer(['is_active' => true]),
        ]);
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create($request->validated());

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.form', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }
}
