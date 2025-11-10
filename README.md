# OtorrinoNet

OtorrinoNet es una aplicación web para agendar citas médicas en un consultorio de otorrinolaringología. Permite a los pacientes reservar citas y proporciona un panel de administración para gestionar tanto las citas como los mensajes de contacto.

## Características

- **Agendamiento de Citas:** Los pacientes pueden agendar citas a través de un formulario sencillo.
- **Panel de Administración:** Un panel para gestionar citas y mensajes de contacto.
- **Formulario de Contacto:** Un formulario para que los pacientes envíen mensajes al consultorio.
- **Horarios Dinámicos:** La aplicación calcula y muestra dinámicamente los horarios disponibles.

---

## Instalación para Desarrollo Local

Sigue estos pasos para configurar el proyecto en tu máquina local para desarrollo y pruebas.

### Prerrequisitos

- PHP 8.2 o superior
- PostgreSQL
- Composer
- Node.js & npm

### Pasos

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/soreviv/otorrinonet.git
    cd otorrinonet
    ```

2.  **Instalar dependencias:**
    ```bash
    composer install
    npm install
    ```

3.  **Configurar el entorno:**
    - Copia `.env.example` a `.env`:
      ```bash
      cp .env.example .env
      ```
    - Genera la clave de la aplicación:
        ```bash
        php artisan key:generate
        ```
    - Actualiza el archivo `.env` con las credenciales de tu base de datos local.

4.  **Configurar la base de datos:**
    - Crea una base de datos en PostgreSQL.
    - Ejecuta las migraciones para crear las tablas:
        ```bash
        php artisan migrate
        ```

5.  **Compilar los assets:**
    ```bash
    npm run dev
    ```

6.  **Iniciar el servidor de desarrollo:**
    ```bash
    php artisan serve
    ```

## Contribuciones

Las pull requests son bienvenidas. Para cambios importantes, por favor abre un issue primero para discutir lo que te gustaría cambiar.
