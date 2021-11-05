<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Inertia\Inertia;
use \Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class CountryController extends Controller
{
    /**
     * All.
     *
     * @return mixed
     */
    public function store()
    {
        $countries = Country::all();
        $data = [];
        foreach ($countries as $country) {
            array_push($data, [
                'id' => $country->id,
                'name' => $country->name,
                'state' => 'view',
            ]);
        }
        return Inertia::render('Admin/Country/View', [
            'data' => $data
        ]);
    }

    /**
     * Get all.
     *
     * @return mixed
     */
    public function get()
    {
        $countries = Country::all();
        $data = [];
        foreach ($countries as $country) {
            array_push($data, [
                'id' => $country->id,
                'name' => $country->name,
                'state' => 'view',
            ]);
        }
        return $data;
    }

    /**
     * Get all.
     *
     * @return mixed
     */
    public function getNames()
    {
        $countries = Country::orderBy('name', 'ASC')->get();
        $data = [];
        foreach ($countries as $country) {
            array_push($data, [
                'value' => $country->id,
                'label' => $country->name
            ]);
        }
        return $data;
    }

    public function save(Request $request) {
        $countries = $request->countries;
        foreach ($countries as $country) {
            if ($country['name'] === null || $country['name'] === '') {
                continue;
            }

            if ($country['id'] === null) {
                Country::insert([
                    'name' => $country['name'],
                    'created_at' => date('Y-m-d H:i:s', time())
                ]);
            } else {
                if ($country['state'] === 'edit') {
                    Country::where(['id' => $country['id']])->update([
                        'name' => $country['name'],
                        'updated_at' => date('Y-m-d H:i:s', time())
                    ]);
                }
            }
        }

        return redirect()->route('admin.country.view');
    }

    /**
     * Delete.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        try {
            return Country::where(['id' => $id])->delete();
        } catch (QueryException $exception) {
            return $exception->errorInfo;
        }
    }
}
