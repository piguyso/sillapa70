<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    public function download(Request $request, $year, $type, $file)
    {
        $type = trim($type, '/');
        if (!in_array($type, ['picture', 'upload'])) {
            abort(404);
        }

        $relativePath = storage_year_path($type, $file, $year);
        if (Storage::disk('public')->exists($relativePath)) {
            return response()->file(Storage::disk('public')->path($relativePath));
        }

        $legacyPath = public_path($type . '/' . basename($file));
        if (file_exists($legacyPath)) {
            return response()->file($legacyPath);
        }

        abort(404);
    }
}
