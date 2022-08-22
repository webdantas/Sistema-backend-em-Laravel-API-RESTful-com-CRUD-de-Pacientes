<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Salary;
use App\Http\Resources\SalaryResource;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Salary::latest()->get();
        return response()->json([SalaryResource::collection($data), 'Salaries fetched.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|max:255',
            'salary' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $salary = Salary::create([
            'user_id' => $request->user_id,
            'salary' => $request->salary
        ]);

        return response()->json(['Salary created successfully.', new SalaryResource($salary)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $salary = Salary::find($id);
        if (is_null($salary)) {
            return response()->json('Data not found', 404);
        }
        return response()->json([new SalaryResource($salary)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|max:255',
            'salary' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $salary->user_id = $request->user_id;
        $salary->salary = $request->salary;
        $salary->save();

        return response()->json(['Salary updated successfully.', new SalaryResource($salary)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();

        return response()->json('Salary deleted successfully');
    }
}
