<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Task;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function create(Request $request) {
        // Lógica para crear un comentario en una tarea
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string',
        ]);

        $comment = Comment::create([
            'task_id' => $request->task_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Comentario creado con éxito', 'data' => $comment], 201);
    }

    public function delete($id) {
        // Lógica para eliminar un comentario por el autor o un super admin
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['error' => 'Comentario no encontrado'], 404);
        }

        if ($comment->user_id !== auth()->id()) {
            // Si el usuario autenticado no es el autor del comentario ni un super admin, no puede eliminarlo
            return response()->json(['error' => 'No tiene permiso para eliminar este comentario'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comentario eliminado con éxito'], 200);
    }
}
