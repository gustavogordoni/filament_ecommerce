# E-Commerce - Laravel, Livewire & Filament

## Sobre o Projeto

Esse projeto é um e-commerce construído com **Laravel 12**, **Livewire 3**, **Filament 3** e **Tailwind CSS**. A ideia é criar tanto um **painel administrativo** com Filament quanto **páginas** para o usuário final usando Livewire e componentes.

Trata-se de um sistema desenvolvido acompanhando a playlist **[E-Commerce Project Using Laravel 10, Livewire 3, Filament 3 & Tailwind CSS](https://youtube.com/playlist?list=PL6u82dzQtlfv8fJF3gm42TDHJdtA2NDWT&si=o_tJXmaz2pTkRmhe)** do canal [**DCodeMania**](https://www.youtube.com/@DCodeMania).

## Funcionalidades

Algumas das principais funcionalidades:

* Cadastro e login de usuários
* Sistema de carrinho de compras
* Checkout para preenchimento de endereço
* Listagem e detalhes de pedidos
* Painel administrativo com Filament (produtos, categorias, marcas, pedidos)

## Passo a passo para a Instalação

### Clone o repositório

```sh
git clone https://github.com/gustavogordoni/filament-ecommerce.git
```

### Acesse o diretório do projeto

```sh
cd filament-ecommerce
```

### Copie o arquivo de variáveis de ambiente

```sh
cp .env.example .env
```

### Suba os containers com Docker

```sh
docker compose up -d
```

### Acesse o container da aplicação

```sh
docker compose exec app bash
```

### Instale as dependências do Laravel

```sh
composer install
```

### Gere a chave da aplicação

```sh
php artisan key:generate
```

### Rode as migrations e seeds

```sh
php artisan migrate --seed
```

### Instale as dependências do frontend

```sh
npm install
```

### Compile os assets com Vite

```sh
npm run build
```

<!--
### (Opcional) Para desenvolvimento com hot reload

```sh
npm run dev
```
-->

## Acesse o Projeto

* Área pública: [http://localhost:7000](http://localhost:7000)
* Painel administrativo (Filament): [http://localhost:7000/admin](http://localhost:7000/admin)
