<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Redirect;

class DepartmentController extends Controller
{
    public function list()
    {
        $departments = Department::all();

        return view('department/list', compact('departments'));
    }

    public function add()
    {
        return view('department/add');
    }

    public function store(Request $request)
    {
        //validation
        $request->validate([
            'name' => 'required|unique:departments'
        ]);

        //store
        Department::create($request->all());

        //redirect
        return Redirect()->route('admin.department.list')->withSuccess('Department added successfully');
    }

    public function edit($id)
    {
        $department = Department::whereId($id)->first() ?? abort(404, 'Department not found');
        return view('department/edit', compact('department'));
    }

    public function patch($id, Request $request)
    {
        //check exist?
        $department = Department::whereId($id)->first() ?? abort(404, 'Department not found');

        //validation
        $request->validate([
            'name' => 'required|unique:departments,name,'.$department->id
        ]);

        //patch
        $department->fill($request->all())->save();

        //redirect
        return Redirect()->route('admin.department.list')->withSuccess('Department updated successfully');
    }

    public function delete($id)
    {
        //check exist?
        $department = Department::whereId($id)->first() ?? abort(404, 'Department not found');

        //delete
        $department->delete();

        //redirect
        return Redirect()->route('admin.department.list')->withSuccess('Department deleted successfully');
    }
}
