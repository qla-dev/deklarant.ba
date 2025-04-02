<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    public function uploadFile(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:5120|mimes:png,jpg,jpeg,pdf,webp,xlsx',
        'folder' => 'nullable|string'
    ]);

    $file = $request->file('file');
    $folder = trim($request->input('folder', ''), '/');

    $originalName = $file->getClientOriginalName();
    $fileName = pathinfo($originalName, PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();

    $disk = Storage::disk('public');

    $directory = $folder ? "uploads/{$folder}" : 'uploads';
    $fullPath = $directory . '/' . $originalName;
    $finalName = $originalName;
    $counter = 1;

    while ($disk->exists($fullPath)) {
        $finalName = $fileName . "({$counter})." . $extension;
        $fullPath = $directory . '/' . $finalName;
        $counter++;
    }

    $storedPath = $file->storeAs($directory, $finalName, 'public');

    $message = ($finalName !== $originalName)
        ? "There is already a file with that name, the new file has been stored as {$finalName}"
        : 'File uploaded successfully!';

    return response()->json([
        'message' => $message,
        'original_name' => $originalName,
        'stored_as' => $finalName,
        'path' => $storedPath,
        'url' => asset('storage/' . $storedPath)
    ]);
}



public function createFolder(Request $request)
{
    $request->validate([
        'folder' => 'required|string'
    ]);

    $baseFolder = trim($request->input('folder'), '/'); // remove trailing slash if any
    $folderName = $baseFolder;
    $path = "uploads/{$folderName}";
    $disk = Storage::disk('public');
    $counter = 1;

    // Check if folder exists and generate new name if needed
    while ($disk->exists($path)) {
        $folderName = $baseFolder . "({$counter})";
        $path = "uploads/{$folderName}";
        $counter++;
    }

    $disk->makeDirectory($path);

    $message = ($folderName !== $baseFolder)
        ? "There is already a folder with that name, the new folder has been created as '{$folderName}'"
        : 'Folder created successfully.';

    return response()->json([
        'message' => $message,
        'folder_name' => $folderName,
        'path' => $path
    ]);
}


    public function showFolder($path = '')
    {
        $disk = Storage::disk('public');

        $fullPath = 'uploads/' . trim($path, '/');

        if (!$disk->exists($fullPath)) {
            return response()->json(['message' => 'Folder not found'], 404);
        }

        return response()->json([
            'path' => $fullPath,
            'folders' => $disk->directories($fullPath),
            'files' => $disk->files($fullPath),
        ]);
    }

}

