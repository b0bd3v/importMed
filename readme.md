# Importação de dados de vendas

Para acesso de demonstração, acesse o link: http://expermedimport.herokuapp.com
## Instalação do sistema

Clone o projeto:  
`git clone https://github.com/robertomartins/importMed.git`  

Acessa a pasta do projeto:  
`cd importMed`  

Instale as depêndencias com o composer:  
`composer install`

Gere o arquivo de variáveis de ambiente:  
`cp .env.example .env`  

Gere a key da aplicação:  
`php artisan key:generate`   

## Executar o sistema  

Execute o servidor embutido:  
`php artisan serve`  

Acesse a url:  
`http://127.0.0.1:8000`  

## Processamento dos arquivos de importação  

Para executar a tarefa que processa os arquivos:  
`php artisan expermed:process`  
Obs.: A mesma deve ser adicionada na cron para que roda constantemente.



