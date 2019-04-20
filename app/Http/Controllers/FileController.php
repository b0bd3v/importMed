<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    const DISK_DATA_IN = 'data.in' . DIRECTORY_SEPARATOR;
    const DISK_DATA_OUT = 'data.out' . DIRECTORY_SEPARATOR;
    const FILE_EXTENSION = '.dat';

    /**
     * Processamento do arquivo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        $nomeOriginal = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
        $extensao = $request->file('file')->getClientOriginalExtension();

        $novoFileName = sprintf("%s.dat", $nomeOriginal);
        $path = $request->file('file')->storeAs(self::DISK_DATA_IN, $novoFileName);

        ob_start();        
        $exit = \Artisan::call('expermed:process');
        ob_get_clean();
         
        if($path){
            return view('file.success');
        } else {
            return view('file.error');
        }

    }


    /**
     * Lista de arquivos processados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processedData(Request $request)
    {
        $files = \Storage::files(self::DISK_DATA_OUT);
        $filesNames = [];
        foreach ($files as $key => $file) {
            $filesNames[] = pathinfo($file, PATHINFO_FILENAME);
        }
        return view('file.processedData', [
            'files' => $filesNames
        ]);
    }

    /**
     * Exibe o arquivo processado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function viewFile($name){
        $file = \Storage::get(self::DISK_DATA_OUT . $name . self::FILE_EXTENSION);
        return \Response::make($file, 200, [
            'Content-Type' => 'application/txt',
            'Content-Disposition' => 'inline; filename="' . $name . self::FILE_EXTENSION . '"'
        ]);
    }

    /**
     * Limpa a pasta de arquivos processados.
     */
    public function cleanFiles() {
    
        $files = \Storage::files(self::DISK_DATA_OUT);
        
        foreach ($files as $key => $file) {
            \Storage::delete($files);
        }
        return \Redirect::away('processed-data');
    }
}
