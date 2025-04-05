FROM php:8.1-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli

# Habilitar mod_rewrite (por si usas URLs limpias)
RUN a2enmod rewrite

# Configurar Apache para que escuche en el puerto 8080
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf && \
    sed -i 's/:80>/:8080>/g' /etc/apache2/sites-available/000-default.conf

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html/

# Dar permisos a los archivos
RUN chown -R www-data:www-data /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 8080
EXPOSE 8080

# Iniciar Apache
CMD ["apache2-foreground"]
