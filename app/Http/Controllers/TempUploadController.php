<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TempUploadController extends Controller
{
    /**
     * Store a temporary uploaded file using Spatie Media Library
     */
    public function store(Request $request)
    {
        // FilePond sends files with the name matching the 'name' prop or 'filepond' by default
        $file = $request->file('filepond') ?? $request->file($request->input('name', 'file'));

        if (!$file) {
            // Try to get any uploaded file
            $allFiles = $request->allFiles();
            $file = !empty($allFiles) ? reset($allFiles) : null;
        }

        if (!$file) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $request->validate([
            'filepond' => 'sometimes|file|max:2048', // 2MB max
        ]);

        $media = Auth::user()->addMedia($file)->toMediaCollection('temp');

        return response()->json(['media_id' => (string) $media->id]);
    }

    /**
     * Delete a temporary uploaded file using Spatie Media Library
     */
    public function revert()
    {
        $tempMedia = Media::findOrFail(request()->getContent());

        if (!$tempMedia) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        $tempMedia->delete();

        return response()->json(['success' => true]);
    }

    public function load($id)
    {
        $Media = Media::findOrFail($id);

        if (!$Media) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        return response()->file($Media->getPath(), [
            'Content-Disposition' => 'inline; filename="' . $Media->file_name . '"',
        ]);
    }


    public function remove($id)
    {
        $Media = Media::findOrFail($id);

        if (!$Media) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        $Media->delete();

        return response()->json(['success' => true]);
    }
}

