# MVC em PHP!

Projeto em PHP puro aplicando conceitos de arquitetura MVC voltado para um sistema de cálculo de imposto e soma de valores de produtos, desenvolvido em 2020 com algumas adaptações feitas em 2022.

## :wrench: Instalação e Execução :zap:
Vou mostrar baseado em docker, porém, pode ser usado sem também.
- Para gerar os arquivos do docker, eu utilizei o [PHPDocker.io](https://phpdocker.io/ "PHPDocker.io");
- Assim que clonar o projeto, basta acessar a pasta do projeto e executar o comando:
`docker run --rm --interactive --tty --volume ${PWD}:/app composer composer install`
- Quando a instalação for finalizada, executar o comando para subir os containers:
`docker-compose up -d` (o parâmetro -d serve para deixar os containers rodando em background);
- Assim que os containers subiram, será necessário pegar o ip do banco de dados para preencher o arquivo env.json, se for usado o docker-compose do projeto, basta executar o comando: `docker-compose exec postgres ifconfig` e pegar o ip conforme imagem:
![image](https://user-images.githubusercontent.com/8507716/191156640-db201c7f-3a5d-448c-9f50-a7a66f22c6d2.png)

- Assim que localizar o ip do container do banco de dados, basta renomear o arquivo example-env.json para env.json e preencher as informações faltantes, o meu ficou assim:

![image](https://user-images.githubusercontent.com/8507716/191156286-89bbc1e7-a1d1-41c3-942c-2ee543e64238.png)

- Para rodar as migrations, basta executar o comando: `docker-compose exec php-fpm php migrations.php`
- Acredito que com isso já seja possível usar o sisteminha.

### :sparkles: Melhorias
- Ficaram alguns TODO pelo código;
- Poderia ser usado herança nas classes dentro da pasta app para reduzir código;
- Ao deletar não está mostrando erros, uma adaptação seria fazer por ajax o delete.

### :red_circle: Cuidados
- :x: Não possui rotas;
- :x: Não possui higienização de links;
- :heavy_check_mark: Tem bind.