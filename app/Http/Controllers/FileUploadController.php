<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    public function uploadTaskFile(Request $request)
    {

        $request->validate([
            'task_file' => 'required|file|max:2048|mimes:pdf,doc,docx,jpeg,png',
        ]);

        $task = Task::find($request->task_id);
        $user = Auth::user();

        $path = $request->file('task_file')->store('uploads', 'public');

        $task->users()->updateExistingPivot($user->id, ['uploadPath' => $path]);

        return redirect()->back()->with('success', 'Task file uploaded successfully!');
    }
}
