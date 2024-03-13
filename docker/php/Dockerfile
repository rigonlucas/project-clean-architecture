FROM php:8.3-fpm

#comes from docker-conmpose
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Configura o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
 RUN curl --silent --show-error https://getcomposer.org/installer | php && \
     mv composer.phar /usr/local/bin/composer && \
     chmod +x /usr/local/bin/composer && \
     composer self-update --preview
 RUN command -v composer

RUN chown -R www-data:www-data /var/www/html


# Criação de usuário no container para rodar o Composer e Artisan Commands sem ter problema de permissão no arquivo
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    chown -R $user:$user /var/www/html
    
USER $user

CMD [ "php-fpm" ]
# # Set the working directory
# WORKDIR /var/www/html

# # Create necessary directories
# RUN mkdir -p bootstrap/ \
#     && chmod -R 775 bootstrap/ \
#     && chown -R www-data:www-data bootstrap/

# # Create necessary directories
# RUN mkdir -p storage/framework/views/ \
#     && chmod -R 775 storage/framework/views \
#     && chown -R www-data:www-data storage/framework/views

# # Create necessary directories
# RUN mkdir -p resources/views \
#     && chmod -R 775 resources \
#     && chown -R www-data:www-data resources