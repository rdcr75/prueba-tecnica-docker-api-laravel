**Requerimientos**
-Instalar Docker y Docker-Compose [](https://www.docker.com/products/docker-desktop)
-Descargar el repositorio en la carpeta destinada a desarrollo, el ambiente de API-Servicios se instalara

-Configurar los siguientes archivos:
* cambiar nombre del archivo .env.example por .env 
    - en linux: cp .env.example .env
    - en windows: ren .env.example .env

* docker-compose-windows.yml
* docker-compose-qa.yml
* docker-compose-prod.yml

**.env**
Configurar los siguientes parametros (en el caso de que desee personalizar la base de datos, password y/o usuarios):
APP_URL=http://localhost:80
DB_CONNECTION=mysql
DB_PORT=3306
DB_DATABASE=api-servicios
DB_USERNAME=api_servicios_user
DB_ROOT_PASSWORD=Qwerty123456
DB_PASSWORD=Qwerty123456

IMPORTANTE: el puerto asignado al parametro APP_URL debe coincidir con los puertos a configurar en el archivo docker-composer correspondiente (docker-compser-windows.yml, docker-composer-qa.yml o docker-composer-prod.yml)

**docker-compose-windows.yml**
Configurar los siguientes parametros:
db:
  ports:
    - 3306:3306 # en el caso de que se desee utilizar otro puerto en el servidor o ya exista un servicio usando ese puerto. Ej.: 3333:3306

nginx:
    ports:
      - 80:80 # en el caso de que se desee utilizar otro puerto en el servidor o ya exista un servicio usando ese puerto. Ej.: 8080:80

**docker-compose-qa.yml**
Configurar los siguientes parametros:
db:
  ports:
    - 3306:3306 # en el caso de que se desee utilizar otro puerto en el servidor o ya exista un servicio usando ese puerto. Ej.: 3333:3306

nginx:
    ports:
      - 80:80 # en el caso de que se desee utilizar otro puerto en el servidor o ya exista un servicio usando ese puerto. Ej.: 8080:80

**docker-compose-prod.yml**
Configurar los siguientes parametros:
db:
  ports:
    - 3306:3306 # en el caso de que se desee utilizar otro puerto en el servidor o ya exista un servicio usando ese puerto. Ej.: 3333:3306

nginx:
    ports:
      - 80:80 # en el caso de que se desee utilizar otro puerto en el servidor o ya exista un servicio usando ese puerto. Ej.: 8080:80

- Procedimiento de instalacion en Windows (local)
    1. Ubicarse en el directorio donde desea instalar y configurar la API de Servicios.
    2. Clonar repositorio (git clone http://gitlab.esign-la.com/e-sign/api-servicios.git)
    3. Acceda a la carpeta api-servicios: 
        Ej.: cd api-servicios
    4. Configurar los archivo .env y docker-compose (docker-compose-windows.yml)
    5. Renombrar el docker-compose a utilizar.
        Ej.: ren docker-composer-windows.yml docker-composer.yml
    6. Ejecutar el docker-compose correspondiente, para crear las imagenes de los contenedores.
        Ej.: docker-compose build app
    7. Ejecutar el docker-compose correspondiente, para crear los contenedores.
        Ej.: docker-compose up -d
    8. Validar que se crearon los contenedores correctamente.
        Ej.1: docker=compose ps (muestra contenedores del proyecto)
        Ej.2: docker ps (muestra todos los contenedores existentes en el servidor)
    9. Ver el contenido de la aplicacion (opcional)
        Ej.: docker-compose exec app ls -l
    10. Ejecutar el composer install para instalar las dependencias de la aplicacion:
        Ej.: docker-compose exec app composer install
    11. Generar una clave de aplicacion unica con la herramienta de comandos laravel artisan. Esta clave se utiliza para cifrar las sesiones de los usuarios y otros datos confidenciales:
        Ej.: docker-compose exec app php artisan key:generate

- Procedimiento de instalacion en QA/Produccion (linux)
    1. Ubicarse en el directorio donde desea instalar y configurar la API de Servicios.
    2. Clonar repositorio (git clone http://gitlab.esign-la.com/e-sign/api-servicios.git)
    3. Acceda a la carpeta api-servicios: 
        Ej.: cd api-servicios
    4. Configurar los archivo .env y docker-compose correspondiente (docker-compose-qa.yml o docker-compose-prod.yml)
    5. Renombrar el docker-compose a utilizar.
        Ej.: sudo cp docker-composer-qa.yml docker-composer.yml
    6. Ejecutar el docker-compose correspondiente, para crear las imagenes de los contenedores.
        Ej.: sudo docker-compose build app
    7. Ejecutar el docker-compose correspondiente, para crear los contenedores.
        Ej.: sudo docker-compose up -d
    8. Validar que se crearon los contenedores correctamente.
        Ej.1: sudo docker-compose ps (muestra contenedores del proyecto)
        Ej.2: sudo docker ps (muestra todos los contenedores existentes en el servidor)
    9. Ver el contenido de la aplicacion (opcional)
        Ej.: sudo docker-compose exec app ls -l
    10. Ejecutar el composer install para instalar las dependencias de la aplicacion:
        Ej.: sudo docker-compose exec app composer install
    11. Generar una clave de aplicacion unica con la herramienta de comandos laravel artisan. Esta clave se utiliza para cifrar las sesiones de los usuarios y otros datos confidenciales:
        Ej.: sudo docker-compose exec app php artisan key:generate