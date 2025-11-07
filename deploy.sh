#!/bin/bash

# ==============================================================================
# Script de Despliegue para OtorrinoNet en Ubuntu 24.04
# ------------------------------------------------------------------------------
# Este script automatiza la instalación y configuración parcial del proyecto.
# La configuración de la base de datos debe realizarse manualmente.
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
echo "--- Paso 1/6: Instalando dependencias del servidor... ---"
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx git postgresql postgresql-contrib unzip \
                                        php$PHP_VERSION-fpm php$PHP_VERSION-pgsql php$PHP_VERSION-mbstring \
                                        php$PHP_VERSION-xml php$PHP_VERSION-curl php$PHP_VERSION-zip php$PHP_VERSION-gd
                    
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
echo "--- Paso 2/6: Preparando el directorio del proyecto en $PROJECT_DIR... ---"
if [ ! -d "$PROJECT_DIR" ]; then
    echo "Clonando el repositorio..."
    sudo git clone "$REPO_URL" "$PROJECT_DIR"
else
    echo "El directorio del proyecto ya existe. Actualizando desde Git..."
    sudo git -C "$PROJECT_DIR" pull
fi
echo "Directorio del proyecto listo."

# --- 3. Configuración de Seguridad de Git ---
echo "--- Paso 3/6: Configurando el directorio como seguro para Git... ---"
sudo git config --global --add safe.directory $PROJECT_DIR
echo "Directorio añadido a la configuración segura de Git."

# --- 4. Configuración del Entorno (.env) ---
echo "--- Paso 4/6: Configurando el archivo .env... ---"
ENV_FILE="$PROJECT_DIR/.env"
if [ ! -f "$ENV_FILE" ]; then
    sudo cp "$PROJECT_DIR/.env.example" "$ENV_FILE"
    # Reemplazar valores en el .env
    sudo sed -i "s/^DB_HOST=.*/DB_HOST=localhost/" "$ENV_FILE"
    sudo sed -i "s/^DB_PORT=.*/DB_PORT=5432/" "$ENV_FILE"
    sudo sed -i "s/^DB_NAME=.*/DB_NAME=$DB_NAME/" "$ENV_FILE"
    sudo sed -i "s/^DB_USER=.*/DB_USER=$DB_USER/" "$ENV_FILE"
    sudo sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=TU_CONTRASEÑA_SEGURA/" "$ENV_FILE"
    sudo sed -i "s/^HCAPTCHA_SECRET_KEY=.*/HCAPTCHA_SECRET_KEY=/" "$ENV_FILE"
    echo "Archivo .env creado. No olvides añadir tu HCAPTCHA_SECRET_KEY y la contraseña de la BD."
else
    echo "El archivo .env ya existe. Omitiendo creación."
fi

# --- 5. Configuración de Permisos e Instalación de Dependencias ---
echo "--- Paso 5/6: Configurando permisos e instalando dependencias... ---"
sudo chown -R www-data:www-data "$PROJECT_DIR"
sudo find "$PROJECT_DIR" -type f -exec chmod 640 {} \;
sudo find "$PROJECT_DIR" -type d -exec chmod 750 {} \;
echo "Permisos configurados."

# Instalar dependencias de PHP
export COMPOSER_HOME="$PROJECT_DIR/.composer"
sudo -u www-data composer install --no-interaction --no-dev --optimize-autoloader -d "$PROJECT_DIR"
echo "Dependencias de PHP instaladas."

# --- 6. Configuración de Nginx ---
echo "--- Paso 6/6: Configurando Nginx... ---"
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
        try_files "$uri" "$uri/" "/index.php?"$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php$PHP_VERSION-fpm.sock;
        fastcgi_param SCRIPT_FILENAME "$document_root"$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/${PROJECT_NAME}_error.log;
    access_log /var/log/nginx/${PROJECT_NAME}_access.log;

    # CSP con nonce para mayor seguridad
    add_header Content-Security-Policy "script-src 'self' 'nonce-{{NONCE}}' https://js.hcaptcha.com https://*.hcaptcha.com https://cdn.jsdelivr.net; style-src 'self' 'nonce-{{NONCE}}' https://cdn.jsdelivr.net; frame-src 'self' https://*.hcaptcha.com; connect-src 'self' https://*.hcaptcha.com; font-src 'self' data:; object-src 'none'";
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

echo "Acciones manuales requeridas:"
echo "1. Edita el archivo '$PROJECT_DIR/.env' y añade tu 'HCAPTCHA_SECRET_KEY' y la contraseña de la BD."
echo "2. Configura un certificado SSL (HTTPS) para tu dominio (recomendado usando Certbot):"
echo "   sudo apt install certbot python3-certbot-nginx"
echo "   sudo certbot --nginx -d $DOMAIN_NAME -d www.$DOMAIN_NAME"

echo ""
echo "3. Configura la base de datos manualmente. Conéctate a PostgreSQL como superusuario (ej. 'sudo -u postgres psql') y ejecuta:"
echo "   CREATE USER otorrinonet_user WITH PASSWORD 'TU_CONTRASEÑA_SEGURA';"
echo "   CREATE DATABASE otorrinonet_db OWNER otorrinonet_user;"
echo "   GRANT ALL PRIVILEGES ON DATABASE otorrinonet_db TO otorrinonet_user;"
echo "   \q"
echo "   Después, importa el esquema:"
echo "   sudo -u postgres psql -d otorrinonet_db < $PROJECT_DIR/database_schema.sql"
echo "   IMPORTANTE: La contraseña que elijas debe coincidir con la variable DB_PASSWORD en tu archivo .env."

exit 0