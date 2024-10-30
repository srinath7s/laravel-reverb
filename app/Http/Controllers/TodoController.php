<?php

namespace App\Http\Controllers;

use App\Events\TodoCreated;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $todo = Todo::create(['content' => $request->input('content')]);
        broadcast(new TodoCreated($todo))->toOthers();

        return response()->json($todo);
    }
}
