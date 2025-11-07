# Configuración del Directorio Raíz y Permisos en Ubuntu 24.04 con Nginx y PHP-FPM

Este documento proporciona instrucciones detalladas para configurar el directorio raíz del proyecto y los permisos adecuados en un servidor Ubuntu 24.04 con Nginx y PHP-FPM, siguiendo las mejores prácticas de seguridad para evitar problemas de permisos y acceso.

### 1. Crear el directorio raíz del proyecto

Primero, crea el directorio donde residirán los archivos de tu proyecto. Es una buena práctica usar `/var/www/` para alojar sitios web. Reemplaza `your_project_name` con el nombre real de tu proyecto.

```bash
sudo mkdir -p /var/www/your_project_name/public
```

Este comando crea el directorio principal del proyecto y un subdirectorio `public`, que es donde Nginx apuntará por defecto para servir los archivos web (por ejemplo, `index.php`).

### 2. Configurar la propiedad y los permisos del directorio

Para garantizar la seguridad y el correcto funcionamiento, es crucial establecer la propiedad y los permisos adecuados.

*   **Cambiar la propiedad del directorio:**
    Asigna la propiedad del directorio a tu usuario de sistema (para que puedas editar archivos fácilmente) y al grupo `www-data` (el grupo que usa Nginx y PHP-FPM).

    ```bash
    sudo chown -R $USER:www-data /var/www/your_project_name
    ```

    Reemplaza `$USER` con tu nombre de usuario de Ubuntu. Por ejemplo, si tu usuario es `admin`, sería `sudo chown -R admin:www-data /var/www/your_project_name`.

*   **Establecer permisos de directorio:**
    Configura los permisos para que los directorios tengan `rwxr-x---` (750) y los archivos `rw-r-----` (640). Esto permite que tu usuario tenga control total, el grupo `www-data` pueda leer y ejecutar (necesario para PHP-FPM y Nginx), y otros usuarios no tengan acceso.

    ```bash
    sudo find /var/www/otorrinonet -type d -exec chmod 750 {} \;
    sudo find /var/www/otorrinonet -type f -exec chmod 640 {} \;
    ```

    **Explicación de los permisos:**
    *   `750` para directorios:
        *   `7` (rwx): Tu usuario puede leer, escribir y ejecutar (acceder a directorios).
        *   `5` (r-x): El grupo `www-data` puede leer y ejecutar (acceder a directorios).
        *   `0` (---): Otros no tienen permisos.
    *   `640` para archivos:
        *   `6` (rw-): Tu usuario puede leer y escribir.
        *   `4` (r--): El grupo `www-data` puede leer.
        *   `0` (---): Otros no tienen permisos.

### 3. Configurar el bloque del servidor Nginx

Crea un nuevo archivo de configuración de Nginx para tu proyecto.

```bash
sudo nano /etc/nginx/sites-available/your_project_name.conf
```

Pega la siguiente configuración, ajustando `your_project_name` a tu dominio y `your_project_name` al nombre de tu directorio:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name otorrinonet.com www.otorrinonet.com;
    root /var/www/otorrinonet/public;

    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock; # Asegúrate de que la versión de PHP-FPM sea correcta (ej. php8.3-fpm.sock)
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Opcional: Bloquear el acceso a archivos .htaccess si existen
    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/otorrinonet_error.log;
    access_log /var/log/nginx/otorrinonet_access.log;

    # Directiva Content-Security-Policy (CSP) para mejorar la seguridad
    # Esta política utiliza un "nonce" para permitir la ejecución de scripts y estilos en línea de forma segura.
    # Se han eliminado las directivas 'unsafe-eval' y 'unsafe-inline' para una mayor seguridad.
    add_header Content-Security-Policy "script-src 'self' 'nonce-{{NONCE}}' https://js.hcaptcha.com https://*.hcaptcha.com https://cdn.jsdelivr.net; style-src 'self' 'nonce-{{NONCE}}' https://cdn.jsdelivr.net; frame-src 'self' https://*.hcaptcha.com; connect-src 'self' https://*.hcaptcha.com; font-src 'self' data:; object-src 'none';";
}
```

Guarda y cierra el archivo (Ctrl+X, Y, Enter).

**Habilitar el bloque del servidor:**
Crea un enlace simbólico desde `sites-available` a `sites-enabled` para activar la configuración.

```bash
sudo ln -s /etc/nginx/sites-available/otorrinonet.conf /etc/nginx/sites-enabled/
```

**Probar la configuración de Nginx y reiniciar:**
Verifica que no haya errores de sintaxis y luego reinicia Nginx.

```bash
sudo nginx -t
sudo systemctl restart nginx
```

### 4. Configurar PHP-FPM

Asegúrate de que PHP-FPM esté instalado y funcionando. Si no lo está, puedes instalarlo:

```bash
sudo apt update
sudo apt install php8.3-fpm # Ajusta la versión de PHP si es necesario
```

**Verificar el usuario y grupo de PHP-FPM:**
Por defecto, PHP-FPM se ejecuta como el usuario `www-data` y el grupo `www-data`. Puedes verificar esto en el archivo de configuración de PHP-FPM:

```bash
sudo nano /etc/php/8.3/fpm/pool.d/www.conf # Ajusta la versión de PHP
```

Busca las líneas `user = www-data` y `group = www-data`. Si no están configuradas así, cámbialas.

**Reiniciar PHP-FPM:**
Después de cualquier cambio en la configuración de PHP-FPM, reinícialo.

```bash
sudo systemctl restart php8.3-fpm # Ajusta la versión de PHP
```

### 5. Probar la configuración

Crea un archivo de prueba `index.php` en tu directorio `public` para verificar que Nginx y PHP-FPM estén funcionando correctamente.

```bash
sudo nano /var/www/otorrinonet/public/index.php
```

Pega el siguiente contenido:

```php
<?php
phpinfo();
?>
```

Guarda y cierra el archivo.

Ahora, abre tu navegador web y navega a `http://your_project_name`. Deberías ver la página de información de PHP. Si la ves, la configuración es correcta.

**¡Importante!** Después de verificar, elimina el archivo `index.php` por razones de seguridad.

```bash
sudo rm /var/www/otorrinonet/public/index.php
```