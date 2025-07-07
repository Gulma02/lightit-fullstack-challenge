# LighIt

Breve descripción del arranque de la aplicación

## Table of Contents

- [Requisitos previos](#prerequisites)
- [Getting Started](#getting-started)
    - [1. Clone the Repository](#1-clone-the-repository)
    - [2. Start Docker Containers](#2-start-docker-containers)
    - [3. Install Frontend Dependencies](#3-install-frontend-dependencies)
    - [4. Compile Frontend Assets](#4-compile-frontend-assets)
- [Usage](#usage)
- [Configuration (Optional)](#configuration-optional)
- [Contributing](#contributing)
- [License](#license)

## Requisitos previos

Antes de comenzar, asegurese de tener las siguientes herramientas instaladas:

* **Git:** Para clonar el repositorio.
    * [Download Git](https://git-scm.com/downloads)
* **Docker Desktop:** Para poder utilizar la instancia local del proyecto.
    * [Download Docker Desktop](https://www.docker.com/products/docker-desktop)
* **Node.js & npm:** Se necesita para poder compilar el frontend y poder utilizar la instancia local del proyecto.
    * [Download Node.js](https://nodejs.org/en/download/) (npm is included)

## Getting Started

Siga los siguientes pasos para inicializar el proyecto en local

### 1. Clone el repositorio

Primero, debe clonar el repositorio a su computadora para poder inicializar el proyecto:

```bash
git clone [https://github.com/Gulma02/lightit-fullstack-challenge.git](https://github.com/Gulma02/lightit-fullstack-challenge.git)
cd lightit-fullstack-challenge
```

### 2. Genere una versión del .env
Es necesario que genere una copia del archivo .env en la ruta del proyecto con la siguiente información:
```dotenv
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=lightit_db
DB_PORT=3306
DB_DATABASE=lightit_app
DB_USERNAME=root
DB_PASSWORD=admin
```
### 3. Inicialice el contenedor de docker

Para poder utilizar este proyecto en su computadora, debe inicializar el contenedor con el siguiente comando:

```bash
docker-compose up -d
```

### 4. Instale las dependencias
Para el correcto funcionamiento del proyecto, es necesario instalar las dependencias del frontend.

```bash
npm install
```

### 5. Ejecute las migraciones y seeders
Para este paso, es necesario ejecutar una serie de comandos para que las migraciones se ejecuten en la conexión correcta
1. Entre a la consola del contenedor de docker
    ```bash
    docker exec -it lighit_app bash
    ```

2. Ejecute los comandos para generar la estructura de la base de datos y sus seeders.
    ```bash
    php artisan migrate --seed
    ```
