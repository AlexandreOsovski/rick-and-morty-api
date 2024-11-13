# Rick And Morty Sistema

## Clonar o repositório do Rick And Morty API

- git clone https://github.com/AlexandreOsovski/rick-and-morty-api.git

obs: Leia todo o readme pois você poderá importar automaticamente todos os dados da api do rick and morty diretamente pela linha de comando para dentro do seu banco MYSQL.

### entre na pasta do projeto

- Entre na pasta do projeto: <strong> cd rick-and-morty-api</strong>

## Configurar o ambiente do Laravel

No repositório clonado, você verá um arquivo chamado .env.example. Vamos usá-lo como base para configurar o ambiente de desenvolvimento local.

Copie o arquivo .env.example e renomeie-o para .env:

- cp .env.example .env

Agora, abra o arquivo .env com seu editor de texto favorito, como o VSCode, Sublime, ou até o nano no terminal:

- nano .env

Dentro do arquivo .env, você verá uma seção relacionada ao banco de dados:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=root
DB_PASSWORD=
```

Aqui, você deve configurar os dados do banco de dados. Se você está usando MySQL, por exemplo:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rick_and_morty
DB_USERNAME=root
DB_PASSWORD=

```

### Gerar a chave de aplicativo Laravel

- php artisan key:generate

Isso vai configurar a chave no arquivo .env automaticamente.

### Configurar o Banco de Dados

Certifique-se de que você tenha um banco de dados configurado. Vamos usar o MySQL como exemplo:

Entre no MySQL e crie o banco de dados:

- mysql -u root -p

No prompt do MySQL, crie o banco de dados:

- CREATE DATABASE rick_and_morty;

Com o banco de dados criado, você pode rodar o comando abaixo para verificar se as configurações no arquivo .env estão corretas:

- php artisan migrate

Se tudo estiver correto, o comando irá rodar as migrações do Laravel no banco de dados.

## Instalar Dependências

Agora que o banco de dados está configurado, é hora de instalar as dependências do Laravel (se ainda não fez isso). Execute:

- composer install

Isso irá instalar todos os pacotes necessários para rodar o projeto.

Agora, você pode rodar o servidor local do Laravel usando o comando abaixo:

- php artisan serve

Isso iniciará o servidor local, normalmente acessível em http://127.0.0.1:8000.

 <hr>

## Configurar o ambiente do NextJs

Agora, você precisa instalar todas as dependências necessárias.

(Necessário NODE 20+, eu utilizei o node 23.)

Execute o seguinte comando no terminal para instalar todas as dependências do projeto:

- npm i

Verificar se a URL está correta

Dentro do projeto existe um arquivo chamado de enviroments.ts, verifique se a URL da api esta igual a que foi gerada ao rodar o comando <strong>php artisan serve</strong>, caso não esteja correto, será necessário mudar

## DENTRO DO PROJETO EXISTE UMA PASTA CHAMADA DE 'POSTMAN_COLLECTION' ONDE TEM A COLLECTION DAS APIS CONSTRUIDAS

## PUXAR TODOS OS DADOS DA API DO RICK AND MORTY PELA LINHA DE COMANDO

dentro da pasta do projeto backend digite os seguintes comandos no terminal

- php artisan import:rickandmorty

Isso salvara todos os 836 personagens no banco de dados, evitando de realizar consultas a todo momento na api do rick and morty.

Voçê também pode usar a flag <b>--limit=</b> no final:

- php artisan import:rickandmorty --limit=20
  S
  pra puxar apenas a quantidade que você quiser.

Para os Episodios segue a mesma premicia,

- php artisan fetch:episodes --limit=20
