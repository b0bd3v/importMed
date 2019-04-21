# Importação de dados de vendas





## Instalação do sistema

Clone o projeto:  
`git clone https://github.com/robertomartins/importMed.git`  

Acessa a pasta do projeto:  
`cd importMed`  

Instale as depêndencias com o composer:  
`composer install`

Gere a key da aplicação:  
`php artisan key:generate`   

## Executar o sistema  

Execute o servidor embutido:  
`php artisan serve`  



Para executar a tarefa que processa os arquivos:   
`php artisan expermed:process`  
Obs.: A mesma deve ser adicionada na cron para que roda constantemente.



