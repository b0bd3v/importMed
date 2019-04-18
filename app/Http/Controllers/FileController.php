<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Processamento do arquivo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        $path = $request->file('file')->store('local');
    }

}
