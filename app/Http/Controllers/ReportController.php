<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;


class ReportController extends Controller
{
    public function departmentView(Request $request)
    {
        $departments = Department::orderBy('name', 'asc')->get();

        //calculating report data
        if($request['start_date']){
            return '>request';
        }

        return view('report/department', compact('departments'));
    }
}
