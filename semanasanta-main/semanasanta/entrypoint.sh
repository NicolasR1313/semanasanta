#!/bin/bash

# Crear carpeta si no existe
mkdir -p /mnt/volume_town/uploads

# Forzar permisos cada vez que arranca (por si el volumen lo borra)
chmod -R 777 /mnt/volume_town/uploads

# Iniciar Apache
exec apache2-foreground
