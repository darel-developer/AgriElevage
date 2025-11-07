<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('created_at','desc')->get();
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'species' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'file' => 'required|file|max:51200|mimes:pdf,ppt,pptx,mp4,mov,avi,mkv,doc,docx'
        ]);

        $file = $request->file('file');
        $fileType = $file->getClientOriginalExtension();
        $path = $file->storeAs('courses', time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$fileType, 'public');

        $course = Course::create([
            'title' => $request->input('title'),
            'species' => $request->input('species'),
            'level' => $request->input('level'),
            'description' => $request->input('description'),
            'file_path' => $path,
            'file_type' => $fileType,
        ]);

        return response()->json(['success' => true, 'course' => $course]);
    }
}
