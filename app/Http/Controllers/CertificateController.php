<?php

namespace App\Http\Controllers;

use App\Models\Painter;

class CertificateController extends Controller
{
    public function show()
    {
        $painter = Painter::query()->firstOrFail();

        return view('painter.certificate', compact('painter'));
    }
}
