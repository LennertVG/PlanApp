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
            'task_file' => 'required|file|max:2048|mimes:pdf,doc,docx,jpeg,png', // Ensure this matches the key used in the form
        ]);

        $task = Task::find($request->task_id);
        $user = Auth::user();

        // STORE THE FILE IN UPLOADS PUBLIC DIRECTORY
        // store to he public folder
        $path = $request->file('task_file')->store('uploads', 'public');

        // Check if the file already exists
        if (Storage::disk('public')->exists($path)) {
            // Delete the existing file (optional)
            Storage::disk('public')->delete($path);
        }

        // STORE PATH IN DATABASE
        // Update the pivot table to mark the task as completed
        $task->users()->updateExistingPivot($user->id, ['uploadPath' => $path]);

        return redirect()->back()->with('success', 'Task file uploaded successfully!');
    }
}
