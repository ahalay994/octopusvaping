<?php

namespace App\Http\Controllers;

use App\Models\Specification;
use Inertia\Inertia;
use \Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class SpecificationController extends Controller
{
    /**
     * All.
     *
     * @return mixed
     */
    public function store()
    {
        $specifications = Specification::all();
        $data = [];
        foreach ($specifications as $specification) {
            array_push($data, [
                'id' => $specification->id,
                'name' => $specification->name,
                'state' => 'view',
            ]);
        }
        return Inertia::render('Admin/Specification/View', [
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
        $specifications = Specification::all();
        $data = [];
        foreach ($specifications as $specification) {
            array_push($data, [
                'id' => $specification->id,
                'name' => $specification->name,
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
        $specifications = Specification::all();
        $data = [];
        foreach ($specifications as $specification) {
            array_push($data, [
                'value' => $specification->id,
                'label' => $specification->name
            ]);
        }
        return $data;
    }

    public function save(Request $request) {
        $specifications = $request->specifications;
        foreach ($specifications as $specification) {
            if ($specification['name'] === null || $specification['name'] === '') {
                continue;
            }

            if ($specification['id'] === null) {
                Specification::insert(['name' => $specification['name']]);
            } else {
                if ($specification['state'] === 'edit') {
                    Specification::where(['id' => $specification['id']])->update(['name' => $specification['name']]);
                }
            }
        }

        return redirect()->route('admin.specification.view');
    }

    /**
     * Delete.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        try {
            return Specification::where(['id' => $id])->delete();
        } catch (QueryException $exception) {
            return $exception->errorInfo;
        }
    }
}
