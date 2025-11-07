#!/bin/bash

# ==============================================================================
# Script de Instalación para OtorrinoNet en Ubuntu 24.04
# ------------------------------------------------------------------------------
# Este script instala y configura el entorno LAMP necesario para el proyecto:
# - Nginx
# - PHP 8.3-FPM y extensiones relevantes
# - PostgreSQL
# - Composer
# - Git
# ==============================================================================

# Detener el script si un comando falla
set -e

echo "--- Iniciando la configuración del servidor para OtorrinoNet ---"

# 1. Actualizar el sistema
echo "--- 1/6: Actualizando paquetes del sistema... ---"
sudo apt update
sudo apt upgrade -y
echo "Sistema actualizado."

# 2. Instalar Nginx
echo "--- 2/6: Instalando Nginx... ---"
sudo apt install nginx -y
sudo systemctl start nginx
sudo systemctl enable nginx
echo "Nginx instalado y habilitado."

# 3. Instalar PostgreSQL
echo "--- 3/6: Instalando PostgreSQL... ---"
sudo apt install postgresql postgresql-contrib -y
sudo systemctl start postgresql
sudo systemctl enable postgresql
echo "PostgreSQL instalado y habilitado."
echo "IMPORTANTE: Recuerda configurar tu usuario y base de datos en PostgreSQL."
echo "Ejemplo:"
echo "  sudo -u postgres psql"
echo "  CREATE DATABASE otorrinonet_db;"
echo "  CREATE USER drviverosorl WITH PASSWORD 'tu_contraseña_segura';"
echo "  GRANT ALL PRIVILEGES ON DATABASE otorrinonet_db TO drviverosorl;"
echo "  \q"

# 4. Instalar PHP y extensiones
# Usamos PHP 8.3, que es el estándar en Ubuntu 24.04
echo "--- 4/6: Instalando PHP 8.3-FPM y extensiones... ---"
sudo apt install php8.3-fpm php8.3-pgsql php8.3-mbstring php8.3-xml php8.3-curl -y
sudo systemctl restart php8.3-fpm
echo "PHP y extensiones (pgsql, mbstring, xml, curl) instalados."

# 5. Instalar Composer (Gestor de dependencias de PHP)
echo "--- 5/6: Instalando Composer... ---"

# Descargar el instalador
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

# Verificar el hash del instalador (opcional pero recomendado)
# HASH=$(php -r "echo hash_file('sha384', 'composer-setup.php');")
# echo "Installer SHA384: $HASH"

# Ejecutar el instalador
php composer-setup.php

# Limpiar
php -r "unlink('composer-setup.php');"

# Mover Composer para que sea accesible globalmente
sudo mv composer.phar /usr/local/bin/composer

# Verificar la instalación
composer --version
echo "Composer instalado correctamente."

# 6. Instalar Git
echo "--- 6/6: Instalando Git... ---"
sudo apt install git -y
git --version
echo "Git instalado."

echo ""
echo "--- ¡Configuración del servidor completada! ---"
echo ""
echo "Próximos pasos recomendados:"
echo "1. Clona tu repositorio: git clone <tu-repositorio> /var/www/otorrinonet.com"
echo "2. Configura los permisos del directorio como se indica en 'configuracion_nginx_php_fpm.md'."
echo "3. Crea el archivo de configuración de Nginx para tu sitio."
echo "4. Crea tu base de datos y usuario en PostgreSQL."
echo "5. Copia el archivo .env.example a .env y rellena las variables de entorno."
echo "6. Ejecuta 'composer install' dentro del directorio de tu proyecto."
echo "7. Importa el esquema de la base de datos: psql -U drviverosorl -d otorrinonet_db < database_schema.sql"

exit 0