<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;


class FileManagerController extends Controller
{
    protected function handleFileUpload(Request $request, string $disk = 'public'): array
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

        $disk = Storage::disk($disk);
        $directory = $folder ? "uploads/{$folder}" : 'uploads';
        $fullPath = $directory . '/' . $originalName;
        $finalName = $originalName;
        $counter = 1;

        while ($disk->exists($fullPath)) {
            $finalName = $fileName . "({$counter})." . $extension;
            $fullPath = $directory . '/' . $finalName;
            $counter++;
        }

        $storedPath = $disk->putFileAs($file, $fullPath);

        return [
            'message' => "File uploaded successfully!",
            'original_name' => $originalName,
            'stored_as' => $finalName,
            'path' => $storedPath,
            'url' => asset('storage/' . $storedPath),
        ];
    }

    public function uploadFile(Request $request)
    {
        $fileData = $this->handleFileUpload($request);

        return response()->json($fileData);
    }

    public function uploadInvoiceFile(Request $request)
    {
        // Handle file upload and get file data
        $fileData = $this->handleFileUpload($request, "local");

        // Extract file information from the response
        $originalName = $fileData['stored_as'];

        // Create a new invoice record in the database
        $invoice = new \App\Models\Invoice();
        $invoice->user_id = auth()->id(); // Assuming user is authenticated
        $invoice->supplier_id = null; // Will be updated later if needed
        $invoice->file_name = $originalName;
        $invoice->total_price = 0.00; // Default value, will be updated after processing
        $invoice->date_of_issue = now()->format('Y-m-d');
        $invoice->country_of_origin = 'default'; // Default value

        $invoice->save();

        return response()->json([
            'message' => 'File uploaded and invoice created successfully',
            'file' => $fileData,
            'invoice_id' => $invoice->id
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
