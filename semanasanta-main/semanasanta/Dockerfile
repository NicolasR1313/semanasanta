FROM php:8.1-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Configurar Apache para que escuche en el puerto 8080
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf && \
    sed -i 's/:80>/:8080>/g' /etc/apache2/sites-available/000-default.conf

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html/

# Crear carpeta de uploads y configurar permisos
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 775 /var/www/html/uploads

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 8080
EXPOSE 8080

# Usar script de arranque si tienes uno
# COPY entrypoint.sh /entrypoint.sh
# RUN chmod +x /entrypoint.sh
# CMD ["/entrypoint.sh"]

# Si no usas entrypoint.sh, deja solo esto:
CMD ["apache2-foreground"]
