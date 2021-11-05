<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Inertia\Inertia;

class AddressController extends Controller
{

    /**
     * All.
     *
     * @return mixed
     */
    public function store()
    {
        $address = Address::all();
        return Inertia::render('Admin/Address/View', [
            'data' => $address
        ]);
    }

    public function addView() {
        return Inertia::render('Admin/Address/Add');
    }

    public function editView($id) {
        $address = Address::where(['id' => $id])->first();

        return Inertia::render('Admin/Address/Edit', [
            'data' => $address
        ]);
    }

    /**
     * Get all.
     *
     * @return mixed
     */
    public function get()
    {
        return Address::all();
    }

    /**
     * Add new.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array[]|\Illuminate\Http\RedirectResponse|string[]
     */
    public function add(\Illuminate\Http\Request $request) {
        Address::create([
            'name' => $request->name,
            'lat' => $request->lat,
            'work' => $request->work,
            'lon' => $request->lon,
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);

        return redirect()->route('admin.address.view')->with('status', 'Запись добавлена');
    }

    /**
     * Edit.
     *
     * @return
     */
    public function edit(\Illuminate\Http\Request $request) {
        Address::where('id', $request->id)->update([
            'name' => $request->name,
            'lat' => $request->lat,
            'work' => $request->work,
            'lon' => $request->lon,
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        return redirect()->route('admin.address.view')->with('status', 'Запись добавлена');
    }

    /**
     * Delete.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        try {
            return Address::where(['id' => $id])->delete();
        } catch (\Illuminate\Database\QueryException $exception) {
            return $exception->errorInfo;
        }
    }
}
