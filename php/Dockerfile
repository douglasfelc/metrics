FROM php:8.2-apache

# Diretório de trabalho
WORKDIR /var/www/html

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    gcc make autoconf \
    libpng-dev libjpeg-dev libfreetype6-dev libxml2-dev \
    libonig-dev libzip-dev unzip git curl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql mbstring zip

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Instalar e habilitar o OpenTelemetry via PECL
RUN pecl install opentelemetry && \
    pecl install grpc && \
    docker-php-ext-enable opentelemetry && \
    docker-php-ext-enable grpc

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar arquivos do projeto para o container
COPY ./app/src/composer.json ./app/src/composer.lock* /var/www/html/

# Rodar o composer install ANTES de copiar o restante (para cache eficiente)
RUN composer install --no-dev --optimize-autoloader

# Agora copia o resto do projeto (src, index.php etc)
COPY ./app/src /var/www/html

# Expor a porta
EXPOSE 80
