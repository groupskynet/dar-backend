<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function getFile(Request $request)
    {
        if (!Storage::exists($request->path))
            return "";
        $file = Storage::get($request->path);
        $mimeType = Storage::mimeType($request->path);
        return "data:$mimeType;base64," . base64_encode($file);
    }
}
