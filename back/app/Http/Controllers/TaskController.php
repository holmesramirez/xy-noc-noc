<?php

namespace App\Http\Controllers; // Cambio en el namespace según la nueva ubicación

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function create(Request $request) {

        // Validar datos de entrada
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'assigned_to' => 'required|exists:users,id',
        ]);

        // Crear la tarea
        $task = new Task();
        $task->title = $validatedData['title'];
        $task->description = $validatedData['description'];
        $task->assigned_to = $validatedData['assigned_to'];
        $task->status = 'Pendiente'; // Estado inicial de la tarea
        $task->save();

        return response()->json(['message' => 'Task created successfully'], 201);

    }

    public function read($id) {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        return response()->json(['task' => $task], 200);
    }

    public function update(Request $request, $id) {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->save();
        return response()->json(['message' => 'Task updated successfully', 'task' => $task], 200);
    }

    public function delete($id) {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    public function updateStatus(Request $request, $id) {
        // Buscar la tarea por su ID
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
    
        // Validar el nuevo estado de la tarea
        $validatedData = $request->validate([
            'status' => 'required|in:Pendiente,En proceso,Bloqueado,Completado',
        ]);
    
        // Actualizar el estado de la tarea
        $task->status = $validatedData['status'];
        $task->save();
    
        return response()->json(['message' => 'Task status updated successfully'], 200);
    }
    
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el usuario es super admin
        if ($user->is_super_admin) {
            // Si es super admin, obtener todas las tareas
            $tasks = Task::all();
        } else {
            // Si no es super admin, obtener solo las tareas asignadas al usuario
            $tasks = Task::where('assigned_to', $user->id)->get();
        }

        // Retornar las tareas como respuesta JSON
        return response()->json(['tasks' => $tasks]);
    }
    
    
}
