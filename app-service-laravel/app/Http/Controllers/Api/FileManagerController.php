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
        'file' => 'required|file|max:5120' // max 5MB
    ]);

    $file = $request->file('file');
    $originalName = $file->getClientOriginalName();
    $fileName = pathinfo($originalName, PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();

    $disk = Storage::disk('public');
    $directory = 'uploads';
    $filePath = $directory . '/' . $originalName;
    $finalName = $originalName;
    $counter = 1;

    // Check if file exists, and if so, rename with suffix (1), (2), etc.
    while ($disk->exists($filePath)) {
        $finalName = $fileName . "({$counter})." . $extension;
        $filePath = $directory . '/' . $finalName;
        $counter++;
    }

    // Store file
    $path = $file->storeAs($directory, $finalName, 'public');

    // Check if it was renamed or not
    $message = ($finalName !== $originalName)
        ? "There is already a file with that name, the new file has been stored as {$finalName}"
        : 'File uploaded successfully!';

    return response()->json([
        'message' => $message,
        'original_name' => $originalName,
        'stored_as' => $finalName,
        'path' => $path,
        'url' => asset('storage/' . $path)
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

