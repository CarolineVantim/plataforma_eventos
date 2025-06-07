# 📦 Plataforma de Eventos

Projeto Laravel + MongoDB usando Docker.

## 🧰 Requisitos

- Docker
- Docker Compose

## 🚀 Subindo o Projeto

```bash
git clone https://github.com/CarolineVantim/plataforma_eventos.git
cd seu-repo
cp .env.example .env
```

### 1. Configure seu `.env`

Edite o arquivo `.env` com as seguintes configurações de MongoDB:

```
DB_CONNECTION=mongodb
DB_HOST=mongo
DB_PORT=27017
DB_DATABASE=meubanco
DB_USERNAME=meuusuario
DB_PASSWORD=senhasecreta
```

### 2. Levante os containers

```bash
docker-compose up -d --build
```

Esse comando irá subir:
- 🐘 Laravel (PHP + Nginx)
- 🍃 MongoDB

### 3. Acesse o container Laravel

```bash
docker exec -it eventos-plataforma-app-1 bash
```

E então execute:

```bash
composer install
php artisan key:generate
php artisan config:clear
php artisan migrate
```

### 4. Teste a conexão com o MongoDB

Dentro do container Laravel:

```bash
php artisan tinker
```

E então:

```php
DB::connection()->getMongoClient()->listDatabases();
```

Se retornar as databases: ✅ está conectado!

## 🐳 Containers

| Serviço         | Porta Local | Descrição                    |
|----------------|-------------|------------------------------|
| Laravel App     | 8989        | http://localhost:8989        |
| MongoDB         | 27017       | Banco de dados               |

## 🧪 Testes

Para rodar os testes da aplicação:

```bash
php artisan test
```

## ✍️ Autor

Desenvolvido por Caroline 💻🚀
Desenvolvido por Melissa 💻🚀
