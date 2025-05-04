# E-Commerce Filament

## Instalação

### Clone Repositório
```sh
git clone https://github.com/gustavogordoni/filament_ecommerce.git filament_ecommerce
```

### Acesse o diretório
```sh
cd filament_ecommerce
```

### Crie o Arquivo .env

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

### Rode as migrations

```sh
php artisan migrate
```

<!-- 
### Rode as seeds
```sh
php artisan db:seed 
```

### Instale as dependências do frontend

```sh
npm install
```

### Compile os assets com Vite

```sh
npm run build
```

> Se estiver desenvolvendo, use `npm run dev` para recompilar automaticamente ao salvar os arquivos.
-->
---

## Acesse o projeto

Abra no navegador: [http://localhost:9000](http://localhost:9000)
