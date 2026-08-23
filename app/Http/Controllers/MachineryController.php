<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machinery;
use Illuminate\Support\Str;

class MachineryController extends Controller
{
    public function index()
    {
        $machineries = Machinery::all();

        return view('admin.machinery-management', compact('machineries'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'machinery_name' => 'required|string|max:255',
            'model'          => 'required|string|max:255',
            'serial_number'  => 'required|string|max:255|unique:machineries,serial_number',
            'price'          => 'required|numeric|min:0',
            'image_path'     => 'required|image|mimes:jpeg,png,jpg|max:2048', // Matches your form name
            'status'         => 'required|in:Available,Reserved,In Use,Under Maintenance,Unavailable',
        ]);

        // 1. Look for 'image_path' from your form
        if ($request->hasFile('image_path')) {
            $machineryName = $request->input('machinery_name');
            $safeName = Str::slug($machineryName);
            $uniqueNumber = time() . '-' . rand(1000, 9999);

            // 2. Use 'image_path' to grab the file extension
            $extension = $request->file('image_path')->getClientOriginalExtension();
            $fileName = $safeName . '_' . $uniqueNumber . '.' . $extension;

            // 3. Store the file using 'image_path'
            $path = $request->file('image_path')->storeAs('machinery', $fileName, 'public');

            // 4. Overwrite the temporary file object with the clean string path ("machinery/filename.jpg")
            $validatedData['image_path'] = $path;
        }

        // 5. Save everything to the database safely
        Machinery::create($validatedData);

        return redirect()->route('machinery.index')->with('success', 'Machinery added successfully.');
    }

}
