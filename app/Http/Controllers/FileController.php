<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload (Request $request)
    {
        $validated = $request->validate([
            'files.*' => 'required|file',
        ]);

        $folderName = uniqid();
        $folderPath = 'uploads/' . $folderName;

        $folder = Folder::create([
            'name' => $folderName,
        ]);

        $uploadedFiles = $request->file('files');
        $uploadedPaths = [];
        $totalSize = 0;

        foreach ($uploadedFiles as $file) {
            $path = $file->storeAs($folderPath, $file->getClientOriginalName(), 'public');

            $fileRecord = File::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'folder_id' => $folder->id,
            ]);

            $uploadedPaths[] = [
                'id' => $fileRecord->id,
                'name' => $file->getClientOriginalName(),
                'path' => Storage::url($path),
                'size' => $file->getSize(),
            ];
            $totalSize += $file->getSize();
        }

        return response()->json([
            'message' => 'Files uploaded successfully!',
            'uploaded_files' => $uploadedPaths,
            'total_size' => $totalSize,
            'folder' => [
                'id' => $folder->id,
                'name' => $folder->name,
                'created_at' => $folder->created_at,
            ],
            'folder_url' => Storage::url($folderName),
        ]);
    }
}
