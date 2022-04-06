<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartmentAgent;
use App\Models\User;
use App\Models\Department;
use Illuminate\Validation\Rule;

class DepartmentAgentController extends Controller
{
    public function list($department_id)
    {
        //list data
        $departmentAgents = DepartmentAgent::where('department_id',$department_id)->get();

        //department data
        $department = Department::whereId($department_id)->first() ?? abort(404, 'Department not found');

        //selectbox data
        $agents = User::where('type','agent')->get();

        return view('department/agent/list', compact('department','departmentAgents','agents'));
    }

    public function store($department_id, Request $request)
    {
        //check department exist?
        Department::whereId($department_id)->first() ?? abort(404, 'Department not found');

        //validate
        $request->validate([
            'user_id' => Rule::unique('department_agents')->where(function ($query) use ($department_id, $request) {
                return $query
                    ->where('department_id', $department_id)
                    ->where('user_id', $request->user_id);
            }),
        ]);

        //store
        $request['department_id']   = $department_id;
        DepartmentAgent::create($request->all());

        //redirect
        return Redirect()->route('admin.department.agent.list', ['department_id' => $department_id])
            ->withSuccess(__('messages.agent_added_successfully'));
    }

    public function delete($department_id, $user_id)
    {
        //check exist?
        $departmenAgent = DepartmentAgent::where('department_id',$department_id)->where('user_id',$user_id)->first() ?? abort(404, 'Agent not found');

        //delete
        $departmenAgent->delete();

        //redirect
        return Redirect()->route('admin.department.agent.list', ['department_id' => $department_id])->withSuccess(__('messages.agent_deleted_successfully'));
    }
}
