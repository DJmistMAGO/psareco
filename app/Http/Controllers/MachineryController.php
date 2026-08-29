<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machinery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MachineryController extends Controller
{
    public function index(Request $request)
    {
        $query = Machinery::query();

        // Search filter (Name, Model, Serial Number)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('machinery_name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $machineries = $query->latest()->paginate(10)->withQueryString();

        // Statistics Metrics for the View Badges
        $totalMachinery   = Machinery::count();
        $availableCount   = Machinery::where('status', 'Available')->count();
        $inUseCount       = Machinery::where('status', 'In Use')->count();
        $maintenanceCount = Machinery::where('status', 'Under Maintenance')->count();
        $overdueCount     = 0; // Replace with dynamic logic if tracking rent dates

        return view('admin.machinery-management', compact(
            'machineries',
            'totalMachinery',
            'availableCount',
            'inUseCount',
            'maintenanceCount',
            'overdueCount'
        ));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'machinery_name' => 'required|string|max:255',
            'model'          => 'required|string|max:255',
            'serial_number'  => 'required|string|max:255|unique:machineries,serial_number',
            'price'          => 'required|numeric|min:0',
            'image_path'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status'         => 'required|in:Available,Reserved,In Use,Under Maintenance,Unavailable',
        ]);

        if ($request->hasFile('image_path')) {
            $safeName = Str::slug($request->input('machinery_name'));
            $uniqueNumber = time() . '-' . rand(1000, 9999);
            $extension = $request->file('image_path')->getClientOriginalExtension();
            $fileName = $safeName . '_' . $uniqueNumber . '.' . $extension;

            $validatedData['image_path'] = $request->file('image_path')->storeAs('machinery', $fileName, 'public');
        }

        Machinery::create($validatedData);

        return redirect()->route('machinery.index')->with('success', 'Machinery added successfully.');
    }

    /**
     * Update the specified machinery item in storage.
     */
    public function update(Request $request, $id)
    {
        $machinery = Machinery::findOrFail($id);

        $validatedData = $request->validate([
            'machinery_name' => 'required|string|max:255',
            'model'          => 'required|string|max:255',
            'serial_number'  => [
                'required',
                'string',
                'max:255',
                Rule::unique('machineries', 'serial_number')->ignore($machinery->id),
            ],
            'price'          => 'required|numeric|min:0',
            'image_path'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'         => 'required|in:Available,Reserved,In Use,Under Maintenance,Unavailable',
        ]);

        // Handle image update and cleanup of old file
        if ($request->hasFile('image_path')) {
            // Delete old image if it exists
            if ($machinery->image_path && Storage::disk('public')->exists($machinery->image_path)) {
                Storage::disk('public')->delete($machinery->image_path);
            }

            $safeName = Str::slug($request->input('machinery_name'));
            $uniqueNumber = time() . '-' . rand(1000, 9999);
            $extension = $request->file('image_path')->getClientOriginalExtension();
            $fileName = $safeName . '_' . $uniqueNumber . '.' . $extension;

            $validatedData['image_path'] = $request->file('image_path')->storeAs('machinery', $fileName, 'public');
        }

        $machinery->update($validatedData);

        return redirect()->route('machinery.index')->with('success', 'Machinery details updated successfully.');
    }

    /**
     * Remove the specified machinery item from storage.
     */
    public function destroy($id)
    {
        $machinery = Machinery::findOrFail($id);

        // Delete associated image file from disk
        if ($machinery->image_path && Storage::disk('public')->exists($machinery->image_path)) {
            Storage::disk('public')->delete($machinery->image_path);
        }

        $machinery->delete();

        return redirect()->route('machinery.index')->with('success', 'Machinery deleted successfully.');
    }
}
