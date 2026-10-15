<?php


namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
 public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'priority' => 'required|string',
    ]);

    Task::create([
        'title' => $request->title,
        'description' => $request->description,
        'priority' => $request->priority,
        'status' => 'Pending', // Naya task hamesha Pending se shuru hoga
    ]);

    return redirect()->back()->with('success', 'Task added successfully!');
}

public function update(Request $request, Task $task)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'priority' => 'required|string',
        'status' => 'required|string', // Status validate karein
    ]);

    $task->update($request->all());

    return redirect()->back()->with('success', 'Task updated successfully!');
}
}

 