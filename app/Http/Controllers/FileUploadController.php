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

        $task->users()->updateExistingPivot($user->id, ['uploadPath' => '/storage/' .$path]);

        $task->users()->updateExistingPivot($user->id, ['completed' => 1]);

        return redirect()->back()->with('success', 'Task file uploaded successfully!');
    }

    public function deleteTaskFile(Request $request)
    {
        $task = Task::find($request->task_id);
        $user = Auth::user();

        // Delete the file from storage
        $uploadPath = $task->users()->where('user_id', $user->id)->value('uploadPath');
        if ($uploadPath) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $uploadPath));
        }

        // Delete the file path from the database
        $task->users()->updateExistingPivot($user->id, ['uploadPath' => null]);
        $task->users()->updateExistingPivot($user->id, ['completed' => 0]);

        return redirect()->back()->with('success', 'Task file deleted successfully!');
    }
}
