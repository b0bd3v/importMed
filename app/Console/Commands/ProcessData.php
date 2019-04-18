<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessData extends Command
{
    const DATA_SEPARATOR = "ç";
    const DISK_DATA_IN = 'data.in' . DIRECTORY_SEPARATOR;
    const DISK_DATA_OUT = 'data.out' . DIRECTORY_SEPARATOR;
    const FILE_EXTENSION = '.dat';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expermed:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processar dados de vendas e clientes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = \Storage::files('data.in');

        foreach ($files as $key => $file) {
            $contentFile = \Storage::get($file);
            echo "Processando arquivo: ". $file . "\n";

            $newFileName = pathinfo($file, PATHINFO_FILENAME);
            $this->saveFile($this->processDataFile($contentFile), $newFileName . self::FILE_EXTENSION);
            dd();


        }
    }

    private function processDataFile($data){
        $contentLines = explode("\n", $data);
        $dataToReturn = [
            'quantidadeCliente' => 0,
            'quantidadeVendedor' => 0,
            'idVendaMaisCara' => null,
            'piorVendedor' => null
        ];

        $vendaMaisCara = [];
        $piorVendedor = null;
        $vendedorSomas = [];
        foreach ($contentLines as $key => $line) {
            $fieldsLine = explode(self::DATA_SEPARATOR, $line);

            switch ($fieldsLine[0]) {
                case '001':
                    $dataToReturn['quantidadeVendedor']++;
                    break;
                case '002':
                    $dataToReturn['quantidadeCliente']++;
                    break;
                case '003':
                    $vendaMaisCara = $this->compareVendaValor($vendaMaisCara, $fieldsLine);

                    if(isset($vendedorSomas[$fieldsLine[3]])){
                        $vendedorSomas[$fieldsLine[3]] = $vendedorSomas[$fieldsLine[3]] + $this->getSomaVenda($fieldsLine);
                    } else {
                        $vendedorSomas[$fieldsLine[3]] = $this->getSomaVenda($fieldsLine);
                    }

                    break;
            }
        }

        $dataToReturn['piorVendedor'] = $this->getNomePior($vendedorSomas);
        $dataToReturn['idVendaMaisCara'] = $vendaMaisCara[1];

        return $dataToReturn;
    }


    private function compareVendaValor($vendaMaisCara, $vendaAtual) {
        if(count($vendaMaisCara) > 0){
            return $this->getSomaVenda($vendaAtual) > $this->getSomaVenda($vendaMaisCara) ? $vendaAtual : $vendaMaisCara;
        } else {
            return $vendaAtual;
        }
    }


    private function getSomaVenda($venda) {
        $produtos = explode('-', preg_replace('/\]*\[* */', '', $venda[2]));
        return $produtos[1] * $produtos[2];
    }

    private function getNomePior($data) {
        return array_search(max($data), $data);
    }

    private function saveFile($data, $fileName) {
        $text = "Quantidade de cliente: " . $data['quantidadeCliente'] . "\n";
        $text.= "Quantidade de vendedores: " . $data['quantidadeVendedor'] . "\n";
        $text.= "ID venda mais cara: " . $data['idVendaMaisCara'] . "\n";
        $text.= "Pior vendedor: " . $data['piorVendedor'] . "\n";

        \Storage::put(self::DISK_DATA_OUT . $fileName, $text);

        $this->removeEntrada(self::DISK_DATA_IN . $fileName, $text);
        return true;
    }


    private function removeEntrada($filePath){
        \Storage::delete($filePath);
    }

}

