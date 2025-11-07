#!/bin/bash

# ==============================================================================
# Script de Despliegue para OtorrinoNet en Ubuntu 24.04
# ------------------------------------------------------------------------------
# Este script automatiza la instalación y configuración completa del proyecto:
# 1. Instala software: Nginx, PHP, PostgreSQL, Composer.
# 2. Clona el repositorio del proyecto (o usa el directorio actual).
# 3. Configura la base de datos PostgreSQL (usuario y base de datos).
# 4. Crea y configura el archivo de entorno .env.
# 5. Configura los permisos de archivos y directorios.
# 6. Instala dependencias de PHP con Composer.
# 7. Crea y habilita el archivo de configuración del sitio en Nginx.
# ==============================================================================

# --- Configuración (ajusta estas variables si es necesario) ---
PROJECT_NAME="otorrinonet"
DOMAIN_NAME="otorrinonet.com"
PROJECT_DIR="/var/www/otorrinonet"
REPO_URL="https://github.com/soreviv/otorrinonet.git" # Cambia esto por tu repositorio

DB_NAME="otorrinonet_db"
DB_USER="otorrinonet_user"
PHP_VERSION="8.3"

# --- Fin de la Configuración ---

# Detener el script si un comando falla
set -e

echo "--- Iniciando el despliegue de OtorrinoNet en $DOMAIN_NAME ---"

# --- 1. Instalación de Dependencias del Servidor ---
echo "--- Paso 1/7: Instalando dependencias del servidor... ---"
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx git postgresql postgresql-contrib unzip \
                    php$PHP_VERSION-fpm php$PHP_VERSION-pgsql php$PHP_VERSION-mbstring \
                    php$PHP_VERSION-xml php$PHP_VERSION-curl php$PHP_VERSION-zip

# Instalar Composer
if ! command -v composer &> /dev/null; then
    echo "Instalando Composer..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    sudo mv composer.phar /usr/local/bin/composer
else
    echo "Composer ya está instalado."
fi
echo "Dependencias del servidor instaladas."

# --- 2. Preparación del Directorio del Proyecto ---
echo "--- Paso 2/7: Preparando el directorio del proyecto en $PROJECT_DIR... ---"
if [ ! -d "$PROJECT_DIR" ]; then
    echo "Clonando el repositorio..."
    sudo git clone "$REPO_URL" "$PROJECT_DIR"
else
    echo "El directorio del proyecto ya existe. Actualizando desde Git..."
    sudo git -C "$PROJECT_DIR" pull
fi
echo "Directorio del proyecto listo."

# --- 3. Configuración de Seguridad de Git ---
echo "--- Paso 3/7: Configurando el directorio como seguro para Git... ---"
sudo git config --global --add safe.directory $PROJECT_DIR
echo "Directorio añadido a la configuración segura de Git."

# --- 4. Configuración de la Base de Datos ---
echo "--- Paso 4/7: Configurando la base de datos PostgreSQL... ---"
read -s -p "Por favor, introduce una contraseña para el usuario de la base de datos ($DB_USER): " DB_PASSWORD
echo

# Crear usuario y base de datos si no existen
if ! sudo -u postgres psql -t -c "\du" | grep -q $DB_USER; then
    sudo -u postgres psql -c "CREATE USER $DB_USER WITH PASSWORD '$DB_PASSWORD';"
else
    echo "El usuario $DB_USER ya existe en PostgreSQL."
fi

if ! sudo -u postgres psql -lqt | cut -d \| -f 1 | grep -qw $DB_NAME; then
    sudo -u postgres psql -c "CREATE DATABASE $DB_NAME OWNER $DB_USER;"
    sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE $DB_NAME TO $DB_USER;"
else
    echo "La base de datos $DB_NAME ya existe."
fi
echo "Base de datos configurada."

# --- 5. Configuración del Entorno (.env) ---
echo "--- Paso 5/7: Configurando el archivo .env... ---"
ENV_FILE="$PROJECT_DIR/.env"
if [ ! -f "$ENV_FILE" ]; then
    sudo cp "$PROJECT_DIR/.env.example" "$ENV_FILE"
    # Reemplazar valores en el .env
    sudo sed -i "s/DB_HOST=localhost/DB_HOST=localhost/" "$ENV_FILE"
    sudo sed -i "s/DB_PORT=5432/DB_PORT=5432/" "$ENV_FILE"
    sudo sed -i "s/DB_NAME=otorrinonet_db/DB_NAME=$DB_NAME/" "$ENV_FILE"
    sudo sed -i "s/DB_USER=drviverosorl/DB_USER=$DB_USER/" "$ENV_FILE"
    sudo sed -i "s/DB_PASSWORD=tu_contraseña_segura/DB_PASSWORD=$DB_PASSWORD/" "$ENV_FILE"
    sudo sed -i "s/HCAPTCHA_SECRET_KEY=/HCAPTCHA_SECRET_KEY=/" "$ENV_FILE" # Dejar vacío para rellenar manualmente
    echo "Archivo .env creado. No olvides añadir tu HCAPTCHA_SECRET_KEY."
else
    echo "El archivo .env ya existe. Omitiendo creación."
fi

# --- 6. Configuración de Permisos ---
echo "--- Paso 6/7: Configurando permisos de archivos y directorios... ---"
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo find "$PROJECT_DIR" -type f -exec chmod 640 {} \;
sudo find "$PROJECT_DIR" -type d -exec chmod 750 {} \;
echo "Permisos configurados."

# --- 7. Instalación de Dependencias de PHP ---
echo "--- Paso 7/7: Instalando dependencias de PHP con Composer... ---"
sudo -u www-data composer install --no-interaction --no-cache --no-dev --optimize-autoloader -d "$PROJECT_DIR"
echo "Dependencias de PHP instaladas."

# --- 8. Configuración de Nginx ---
echo "--- Paso 8/7: Configurando Nginx... ---"
NGINX_CONF="/etc/nginx/sites-available/$DOMAIN_NAME.conf"

# Usar un 'heredoc' para crear el archivo de configuración
sudo tee "$NGINX_CONF" > /dev/null <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name $DOMAIN_NAME www.$DOMAIN_NAME;
    root $PROJECT_DIR/public;

    index index.php index.html index.htm;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php$PHP_VERSION-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/${PROJECT_NAME}_error.log;
    access_log /var/log/nginx/${PROJECT_NAME}_access.log;

    # CSP con nonce para mayor seguridad
    add_header Content-Security-Policy "script-src 'self' 'nonce-{{NONCE}}' https://js.hcaptcha.com https://*.hcaptcha.com https://cdn.jsdelivr.net; style-src 'self' 'nonce-{{NONCE}}' https://cdn.jsdelivr.net; frame-src 'self' https://*.hcaptcha.com; connect-src 'self' https://*.hcaptcha.com; font-src 'self' data:; object-src 'none';";
}
EOF

# Habilitar el sitio y reiniciar Nginx
if [ ! -L "/etc/nginx/sites-enabled/$DOMAIN_NAME.conf" ]; then
    sudo ln -s "$NGINX_CONF" /etc/nginx/sites-enabled/
fi

# Probar configuración y reiniciar
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php$PHP_VERSION-fpm

echo "Nginx configurado."

echo ""
echo "--- ¡Despliegue completado! ---"
echo ""
echo "Resumen:"
echo " - URL del sitio: http://$DOMAIN_NAME"
echo " - Directorio del proyecto: $PROJECT_DIR"
echo " - Base de datos: $DB_NAME"
echo " - Usuario de la base de datos: $DB_USER"
echo ""
echo "Acciones manuales requeridas:"
echo "1. Edita el archivo '$PROJECT_DIR/.env' y añade tu 'HCAPTCHA_SECRET_KEY'."
echo "2. Configura un certificado SSL (HTTPS) para tu dominio (recomendado usando Certbot)."
echo "   sudo apt install certbot python3-certbot-nginx"
echo "   sudo certbot --nginx -d $DOMAIN_NAME -d www.$DOMAIN_NAME"
echo "3. Importa el esquema de la base de datos con el siguiente comando:"
echo "   sudo -u postgres psql -U $DB_USER -d $DB_NAME < \"$PROJECT_DIR/database_schema.sql\""

exit 0
