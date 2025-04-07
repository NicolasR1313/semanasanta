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

# Crear la carpeta de volumen si no existe y darle permisos
RUN mkdir -p /mnt/volume_town/uploads && \
    chown -R www-data:www-data /mnt/volume_town/uploads && \
    chmod -R 755 /mnt/volume_town/uploads

# Crear un alias simbólico para acceder desde el navegador
RUN ln -s /mnt/volume_town/uploads /var/www/html/semanasanta/uploads

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 8080
EXPOSE 8080

# Declarar el volumen
VOLUME /mnt/volume_town/uploads

# Iniciar Apache
CMD ["apache2-foreground"]
