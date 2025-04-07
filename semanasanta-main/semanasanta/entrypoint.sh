#!/bin/bash

# Crear carpeta dentro del volumen montado
mkdir -p /mnt/volume_town/uploads

# Dar permisos para que PHP pueda escribir
chown -R www-data:www-data /mnt/volume_town/uploads
chmod -R 755 /mnt/volume_town/uploads

# Iniciar Apache
exec apache2-foreground
