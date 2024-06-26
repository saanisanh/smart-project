<?php

namespace App\Http\Controllers;

use App\Models\TeacherHomeroomRelationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherHomeroomRelationshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher_homerooms = TeacherHomeroomRelationship::whereNotNull('teacher_id')->get();

        $data = [
            'title' => 'Teacher Homerooms',
            'teacher_homerooms' => $teacher_homerooms->isEmpty() ? [] : $teacher_homerooms,
        ];

        return view('teacher.teacher-homeroom.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $teacher_homerooms = TeacherHomeroomRelationship::select('teacher_homeroom_relationships.*', 'users.name', 'classrooms.name as classroom_name')
        //     ->join('users', 'users.id', '=', 'teacher_homeroom_relationships.teacher_id')
        //     ->join('classrooms', 'classrooms.id', '=', 'teacher_homeroom_relationships.classroom_id')
        //     ->where('teacher_homeroom_relationships.curriculum_id', 2)
        //     ->get();

        return view('teacher.teacher-homeroom.form', [
            'title' => 'Add Teacher Homeroom',
            'teacher_homerooms' => $teacher_homerooms
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'classroom' => 'required',
            'curriculum' => 'required',
        ]);

        TeacherHomeroomRelationship::create($data);

        return redirect()->route('teacher-homeroom.index')->with('successMessage', 'Data successfully added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacher_homeroom = TeacherHomeroomRelationship::findOrFail($id);

        return view('teacher.teacher-homeroom.detail', [
            'title' => 'Teacher Detail',
            'teacher_homeroom' => $teacher_homeroom,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher_homeroom = TeacherHomeroomRelationship::findOrFail($id);

        return view('teacher.teacher-homeroom.form', [
            'title' => 'Edit Teacher',
            'teacher_homeroom' => $teacher_homeroom,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'username' => 'required|alpha_num|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:3',
            'gender' => 'required',
            'born_date' => 'required|date',
            'phone' => 'required',
            'nik' => 'required|unique:users,nik,' . $id,
            'address' => 'required',
            'role' => 'required',
        ]);

        try {
            $teacher_homeroom = TeacherHomeroomRelationship::findOrFail($id);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $teacher_homeroom->update($data);

            return redirect()->route('teacher-homeroom.index')->with('successMessage', 'Data successfully updated');
        } catch (\Throwable $th) {
            return redirect()->route('teacher-homeroom.edit', $id)->with('errorMessage', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $teacher_homeroom = TeacherHomeroomRelationship::findOrFail($id);
            $teacher_homeroom->delete();

            return redirect()->route('teacher-homeroom.index')->with('successMessage', 'Data successfully deleted');
        } catch (\Throwable $th) {
            return redirect()->route('teacher-homeroom.index')->with('errorMessage', $th->getMessage());
        }
    }
}
