# OtorrinoNet

OtorrinoNet es una aplicación web para agendar citas médicas en un consultorio de otorrinolaringología. Permite a los pacientes reservar citas y proporciona un panel de administración para gestionar tanto las citas como los mensajes de contacto.

## Características

- **Agendamiento de Citas:** Los pacientes pueden agendar citas a través de un formulario sencillo.
- **Panel de Administración:** Un panel para gestionar citas y mensajes de contacto.
- **Formulario de Contacto:** Un formulario para que los pacientes envíen mensajes al consultorio.
- **Horarios Dinámicos:** La aplicación calcula y muestra dinámicamente los horarios disponibles.

---

## Despliegue en Producción (Ubuntu 24.04)

Este método utiliza un script para automatizar la configuración en un servidor limpio de Ubuntu 24.04.

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/soreviv/otorrinonet.git
    cd otorrinonet
    ```

2.  **Hacer ejecutable y correr el script de despliegue:**
    El script te pedirá la contraseña para el usuario de la base de datos durante la ejecución.
    ```bash
    chmod +x deploy.sh
    sudo ./deploy.sh
    ```

3.  **Acciones manuales post-despliegue:**
    Una vez que el script finalice, solo quedan dos pasos manuales:

    -   **Añadir la clave de hCaptcha:**
        Edita el archivo de entorno y añade tu clave secreta de hCaptcha.
        ```bash
        sudo nano /var/www/otorrinonet/.env
        ```

    -   **Configurar SSL (HTTPS):**
        El script te proporcionará el comando exacto para instalar un certificado SSL gratuito con Certbot. Se recomienda encarecidamente hacerlo para proteger tu sitio.

---

## Instalación para Desarrollo Local

Sigue estos pasos para configurar el proyecto en tu máquina local para desarrollo y pruebas.

### Prerrequisitos

- PHP 8.0 o superior
- PostgreSQL
- Composer
- Un servidor web como Nginx o Apache

### Pasos

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/soreviv/otorrinonet.git
    cd otorrinonet
    ```

2.  **Instalar dependencias:**
    ```bash
    composer install
    ```

3.  **Configurar la base de datos:**
    - Crea una base de datos en PostgreSQL.
    - Importa el esquema desde `database_schema.sql`.

4.  **Configurar el entorno:**
    - Copia `.env.example` a `.env`:
      ```bash
      cp .env.example .env
      ```
    - Actualiza el archivo `.env` con las credenciales de tu base de datos local y tu clave de hCaptcha.

5.  **Configurar el servidor web:**
    - Apunta el Document Root de tu servidor web al directorio `public/`.
    - Asegúrate de que el servidor esté configurado para procesar archivos PHP.

## Contribuciones

Las pull requests son bienvenidas. Para cambios importantes, por favor abre un issue primero para discutir lo que te gustaría cambiar.
