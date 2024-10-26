
# Tasks API

## Requisitos

Necesitas tener instalados los siguientes componentes:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Configuración del proyecto

1. Clona el repositorio
2. Asegúrate de tener tu archivo `.env` configurado correctamente. Puedes basarte en el archivo `.env.dist`.

## Pasos para iniciar el proyecto

Ejecuta los siguientes comandos en el siguiente orden:

```bash
make build               # Construye la imagen Docker y prepara el entorno
make up                  # Inicia el contenedor
```

Crea la base de datos (en caso de ser necesario):

```bash
make create_db
```

Importa una copia de seguridad en la base de datos:

```bash
docker-compose -p tasks_api -f docker-compose.yml exec mysql mysql -u{user_name} -p{user_password} {db_name} < {backup_filename}.sql
```

Desplegar:

```bash
make deploy
```

Para detener el contenedor:
  ```bash
  make down
  ```

## Acceder a la API

Una vez que los contenedores estén en funcionamiento, podrás acceder a la API desde este host: [http://localhost:8181](http://localhost:8181).

Si importas la base de datos de prueba, el usuario que hay creado es:

email: ``user@tasks.com``

password: ``task``



