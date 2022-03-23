FROM php:7.4-fpm

# Arguments will be defined in docker-compose.yml  Set default values. Args available only in "build"-time 
ARG arg_user=bob
ARG arg_uid=1000
ARG arg_gid=1000

# ENV always available
ENV env_app_workdir = /var/www

RUN useradd -G www-data,root -u $arg_uid -d /home/$arg_user $arg_user
RUN mkdir -p /home/$arg_user/ && \
    chown -R $arg_user:$arg_user /home/$arg_user 

# Install system dependencies   -y=autoYES
RUN apt-get update && apt-get install -y \    
    curl \   
    zip \
    unzip

# Install PHP extensions

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR ${env_app_workdir}

USER ${arg_user}