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
     * Executa o comando de terminal.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = \Storage::files(self::DISK_DATA_IN);

        foreach ($files as $key => $file) {
            $contentFile = \Storage::get($file);
            echo "Processando arquivo: ". $file . "\n";
            $newFileName = pathinfo($file, PATHINFO_FILENAME);
            if($this->saveFile($this->processDataFile($contentFile), $newFileName . self::FILE_EXTENSION)){
                $this->removeEntrada(self::DISK_DATA_IN . $newFileName . self::FILE_EXTENSION);
            }            
        }
    }

    /**
     * Processa os dados obtidos nos arquivo de entrada.
     */
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


    /**
     * Retorna a venda que tem maior valor.
     */
    private function compareVendaValor($vendaMaisCara, $vendaAtual) {
        if(count($vendaMaisCara) > 0){
            return $this->getSomaVenda($vendaAtual) > $this->getSomaVenda($vendaMaisCara) ? $vendaAtual : $vendaMaisCara;
        } else {
            return $vendaAtual;
        }
    }

    /**
     * Obtém a soma de todos os produtos levando em consideração a quatidade.
     */
    private function getSomaVenda($venda) {
        $vendas = explode(',', preg_replace('/\]*\[* */', '', $venda[2]));
        $soma = 0;
        foreach ($vendas as $key => $venda) {
            $produto = explode('-', $venda);
            $produto = array_filter($produto, function($info){ return trim($info); });
            $soma = ($produto[1] * $produto[2]) + $soma;
        }
        return $soma;
    }

    /**
     * Obtém o nome do pior vendedor.
     */
    private function getNomePior($data) {
        return array_search(max($data), $data);
    }

    /**
     * Armazena o arquivo com o resultado.
     */
    private function saveFile($data, $fileName) {
        \Storage::put(self::DISK_DATA_OUT . $this->nomeSaidaDat($fileName), $this->templateSaida($data));
        return true;
    }

    /**
     * Remove o arquivo de entrada.
     */
    private function removeEntrada($filePath){
        \Storage::delete($filePath);
    }

    /**
     * Monta a string que será salva no arquivo de saída.
     */
    private function templateSaida($data) {
        $text = "Quantidade de cliente: " . $data['quantidadeCliente'] . "\n";
        $text.= "Quantidade de vendedores: " . $data['quantidadeVendedor'] . "\n";
        $text.= "ID venda mais cara: " . $data['idVendaMaisCara'] . "\n";
        $text.= "Pior vendedor: " . $data['piorVendedor'] . "\n";
        return $text;
    }

    /**
     * Retorna novo no do arquivo que será salvo no diretório de saída.
     */
    private function nomeSaidaDat($fileName) {
        $nome = explode('.', $fileName);
        $datExtension = end($nome);
        $novoNome = $nome[0] . '.done.' . $datExtension; 
        return $novoNome;
    }
}

