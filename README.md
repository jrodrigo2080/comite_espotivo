Configuração do projeto:
1. Retirar o exemple do arquivo .env-exemple
2. criar uma base de dados chamada comite
3. rodar o comando php atisan migrate
4. rodar o comando php artisan serve


vou deixar um dump da base teste e os endpoint para vocês testarem.


Listar todos os clubes 
http://localhost:8000/api/clubes

Listar todos recursos
http://localhost:8000/api/recursos

inserir clubes
http://localhost:8000/api/inserir-clube
{
    "clube":"sao paulo 4",
    "saldo_disponivel":"2500"
}

consumir recursos
http://localhost:8000/api/consumir-recurso
{
    "clube_id":"1",
    "recurso_id":"2",
    "valor_consumo":"500,00"
}


inserir recurso
http://localhost:8000/api/inserir-recurso
{
    "recurso": "Recurso para passagens",
    "saldo_disponivel": "1000"
}
