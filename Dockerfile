# Use uma imagem base oficial do PHP-FPM
FROM php:8.2-fpm

# Defina seus argumentos de usuário e UID
ARG user=caroline
ARG uid=1000

# Instale dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libssl-dev \
    nginx \ # Adicionado Nginx para uma imagem que possa servir o app (se você quiser uma imagem monolítica)
    nodejs \ # Adicionado para compilação de assets frontend
    npm \ # Adicionado para compilação de assets frontend
    build-essential # Adicionado para garantir ferramentas de compilação para certas extensões

# Limpe o cache APT
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instale extensões PHP
# `sockets` pode precisar de `libevent-dev` ou similar dependendo da distro/versão
RUN docker-php-ext-install mbstring exif pcntl bcmath gd sockets pdo_mysql # Adicionado pdo_mysql se você usar MySQL/MariaDB

# Instale a extensão MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Instale a extensão Redis
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Obtenha o Composer mais recente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crie o usuário do sistema para executar comandos Composer e Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Defina o diretório de trabalho
WORKDIR /var/www

# Copie apenas os arquivos de configuração do Composer e Package.json/Lock para otimizar o cache de camadas
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./ # Se você usa npm/yarn

# Instale as dependências Composer e NPM (com --no-dev para produção)
USER $user # Mude para o usuário não-root para instalar dependências
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build # Instala e constrói assets frontend (altere 'build' para 'prod' se for o caso)

# Copie o restante do código da aplicação
USER root # Temporariamente para copiar o resto
COPY . .

# Copie configurações PHP personalizadas
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Defina permissões corretas para os diretórios Laravel
# www-data é o usuário/grupo padrão do PHP-FPM no Debian. Ajuste se o seu Nginx/PHP-FPM usar outro.
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Mude para o usuário não-root para a execução final do container
USER $user

# Exponha a porta do PHP-FPM
EXPOSE 9000

# Comando padrão para iniciar o PHP-FPM
CMD ["php-fpm"]
