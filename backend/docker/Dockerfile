# Imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \# Constrói e sobe os containers definidos no docker-compose.yml
    docker-compose up -d --buildProjeto Laravel com Docker
    
    # Acesse o projeto Laravel no navegador
    http://localhost:8000
    
    # Acessa o terminal do container Docker
    docker exec -it greenn_project bash
    git \
    curl \
    && docker-php-ext-configure gd \
    --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia os arquivos do projeto
COPY . .

# Instala as dependências do Laravel
RUN composer install

# Define permissões de pasta
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Habilita módulos necessários do Apache
RUN a2enmod rewrite headers

# Expondo a porta 8000 que Laravel utiliza por padrão
EXPOSE 8000

# Comando para iniciar automaticamente o Laravel usando php artisan serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
