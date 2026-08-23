<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $inventories = $query->latest()
            ->paginate(12)
            ->withQueryString();

        $totalProducts = Inventory::count();
        $fertilizerCount = Inventory::where('type', 'Fertilizer')->count();
        $pesticideCount = Inventory::where('type', 'Pesticide')->count();
        $lowStockCount = Inventory::whereColumn(
            'quantity',
            '<=',
            'reorder_level'
        )->count();

        $expiringCount = Inventory::whereNotNull('expiration_date')
            ->whereBetween('expiration_date', [
                now()->startOfDay(),
                now()->addDays(30)->endOfDay(),
            ])
            ->count();

        return view('admin.inventory', compact(
            'inventories',
            'totalProducts',
            'fertilizerCount',
            'pesticideCount',
            'lowStockCount',
            'expiringCount'
        ));
    }


    public function addProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Fertilizer,Pesticide'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'numeric', 'min:0'],
            'expiration_date' => ['nullable', 'date'],
            'image_path'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image_path')) {
            $productName = $request->input('name');
            $safeName = Str::slug($productName);
            $uniqueNumber = time() . '-' . rand(1000, 9999);

            $extension = $request->file('image_path')->getClientOriginalExtension();
            $fileName = $safeName . '_' . $uniqueNumber . '.' . $extension;

            // Ensure the products folder exists in storage/app/public
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }

            $path = $request->file('image_path')->storeAs('products', $fileName, 'public');

            $validated['image_path'] = $path;
        }

        Inventory::create($validated);

        return redirect()->route('inventory.index')->with('success', 'Product added successfully.');
    }


    public function updateProduct(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Fertilizer,Pesticide'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'numeric', 'min:0'],
            'expiration_date' => ['nullable', 'date'],
            'image_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('image_path')) {
            $productName = $request->input('name');
            $safeName = Str::slug($productName);
            $uniqueNumber = time() . '-' . rand(1000, 9999);

            $extension = $request->file('image_path')->getClientOriginalExtension();
            $fileName = $safeName . '_' . $uniqueNumber . '.' . $extension;

            // Ensure the products folder exists in storage/app/public
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }

            // Delete the old image if it exists, before saving the new one
            if ($inventory->image_path && Storage::disk('public')->exists($inventory->image_path)) {
                Storage::disk('public')->delete($inventory->image_path);
            }

            $path = $request->file('image_path')->storeAs('products', $fileName, 'public');

            $validated['image_path'] = $path;
        }

        $inventory->update($validated);

        return redirect()->route('inventory.index')->with('success', 'Product updated successfully.');
    }


    public function deleteProduct(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Product deleted successfully.');
    }

    public function trash()
    {
        $deletedInventories = Inventory::onlyTrashed()
            ->latest('deleted_at')
            ->paginate(12);

        return view('admin.inventory-trash', compact('deletedInventories'));
    }

    public function restoreProduct($id)
    {
        $inventory = Inventory::onlyTrashed()->findOrFail($id);

        $inventory->restore();

        return redirect()
            ->route('inventory.trash')
            ->with('success', 'Product restored successfully.');
    }

    public function forceDeleteProduct($id)
    {
        $inventory = Inventory::onlyTrashed()->findOrFail($id);

        $inventory->forceDelete();

        return redirect()
            ->route('inventory.trash')
            ->with('success', 'Product permanently deleted.');
    }
}
