<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    // 1. Read: Saare students ki list dekhne ke liye
    public function index()
    {
        $students = Student::all();
        return response()->json([
            'status' => 'success',
            'data' => $students
        ], 200);
    }

    // 2. Create: Naya student add karne ke liye (With Validation)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'course' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $student = Student::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Student created successfully!',
            'data' => $student
        ], 201);
    }

    // 3. Read Single: Ek specific student ko ID se dekhne ke liye
    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $student
        ], 200);
    }

    // 4. Update: Student ka data update karne ke liye
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'course' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $student->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Student updated successfully!',
            'data' => $student
        ], 200);
    }

    // 5. Delete: Student ko delete karne ke liye
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        $student->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Student deleted successfully!'
        ], 200);
    }
}