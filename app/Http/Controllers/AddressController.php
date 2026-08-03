<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $addresses = auth()->user()->addresses()->get();

        return view('account.addresses.index', compact('addresses'));
    }

    public function create(): View
    {
        return view('account.addresses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAddress($request);

        $user = auth()->user();
        $isFirst = $user->addresses()->count() === 0;
        $makeDefault = $isFirst || $request->boolean('is_default');

        DB::transaction(function () use ($user, $validated, $makeDefault) {
            if ($makeDefault) {
                $user->addresses()->update(['is_default' => false]);
            }

            $user->addresses()->create($validated + ['is_default' => $makeDefault]);
        });

        return redirect()->route('account.addresses.index')
            ->with('success', 'Address added successfully.');
    }

    public function edit(Address $address): View
    {
        $this->authorizeAddress($address);

        return view('account.addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address): RedirectResponse
    {
        $this->authorizeAddress($address);

        $validated = $this->validateAddress($request);

        DB::transaction(function () use ($address, $validated, $request) {
            $makeDefault = $request->boolean('is_default');

            if ($makeDefault) {
                $address->user->addresses()
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            } else {
                $makeDefault = $address->user->addresses()
                    ->where('id', '!=', $address->id)
                    ->where('is_default', true)
                    ->doesntExist();
            }

            $address->update($validated + ['is_default' => $makeDefault]);
        });

        return redirect()->route('account.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        $this->authorizeAddress($address);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = auth()->user()->addresses()->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        return redirect()->route('account.addresses.index')
            ->with('success', 'Address deleted successfully.');
    }

    protected function validateAddress(Request $request): array
    {
        return $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'recipient' => ['required', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'is_default' => ['nullable', 'boolean'],
        ]);
    }

    protected function authorizeAddress(Address $address): void
    {
        abort_if($address->user_id !== auth()->id(), 403);
    }
}
