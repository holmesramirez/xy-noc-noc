<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function upload(Request $request) {
        // Validar datos de entrada
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Max size 2MB
            'task_id' => 'required|exists:tasks,id', // Ensure task exists
        ]);

        // Subir el archivo adjunto
        $file = $request->file('file');
        $path = $file->store('attachments'); // Almacenar el archivo en la carpeta "storage/app/attachments"

        // Crear registro en la base de datos para el archivo adjunto
        $attachment = new Attachment();
        $attachment->task_id = $validatedData['task_id'];
        $attachment->file_path = $path;
        $attachment->save();

        return response()->json(['message' => 'File uploaded successfully'], 200);
    }

    public function delete($id) {
        // Verificar si el archivo adjunto existe
        $attachment = Attachment::find($id);
        if (!$attachment) {
            return response()->json(['error' => 'Attachment not found'], 404);
        }
    
        // Verificar permisos para eliminar el archivo adjunto
        $user = auth()->user(); // Obtener el usuario autenticado
    
        // Verificar si el usuario es super admin o tiene permisos para eliminar el archivo adjunto
        if ($user->isSuperAdmin() || $attachment->isOwner($user)) {
            // Eliminar el archivo del almacenamiento
            Storage::delete($attachment->file_path);
    
            // Eliminar el registro de la base de datos
            $attachment->delete();
    
            return response()->json(['message' => 'Attachment deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
