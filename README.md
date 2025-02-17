# Microblogging Demo - Laravel 11

Aplicación de **microblogging** con funcionalidades similares a las de Twitter, que permite a los usuarios seguir a otros, crear y publicar tweets, así como visualizar los tweets de los usuarios a los que siguen. **Optimizada** para manejar grandes volumenes de datos, a travez del diseño de **normalización de base de datos**, almacenamiento de **registros en cache**, y el uso de queu colas de laravel. para la **insercion de datos en segundo plano**.

## REQUERIMIENTOS
- PHP 8.2+
- Docker y Docker Compose

## DESPLIEGUE

- Clonar el repositorio:
´´´git clone https://github.com/Edcodigo01/microblogging.git´´´

- Ingresar al directorio descargado: 
´´´cd microblogging´´´

- Crear .env a partir del archivo env.example, ya tiene las configuraciones necesarias.

´´´cp .env.example .env´´´

- Levantar contendores 

´´´docker-compose up -d´´´

- Instalar dependencias laravel
´´´docker-compose exec app composer install´´´

-Ejecutar las migraciones
´´´docker-compose exec app php artisan migrate --seed´´´

Para consumir la API, puedes utilizar la colección de Postman y las variables de entorno incluidas en la raíz de este repositorio. Esta configuración permite gestionar los tokens de sesión de forma automática.

## ENDPOINTS

En caso de no utilizar la colección de Postman que está en la raíz de este repositorio, deberá preparar cada solicitud con la siguiente cabecera:

```json
    {
        "headers": { 
            "Authorization": "Bearer tu_token_aqui", "Accept": "application/json" 
            }
    }
```

### AUTENTICACIÓN

#### Registro
- **Petición POST:**

```
api/auth/register
```

- **Cuerpo de la solicitud:**

```json
{
    "name": "", "email": "", "password": ""
}
```

#### Iniciar sesión

- **Petición POST:**

```
api/auth/login
```

- **Cuerpo de la solicitud:**

```json
{
    "email": "", "password": ""
}
```

#### Cerrar sesión (Requiere autenticación)
- **Petición POST:**

```
api/auth/logout
```

### USUARIOS

#### Listar todos los usuarios
- **Petición GET:**

```
api/users
```

- **Parámetro opcional:**

```
page=1
```

#### Obtener datos de un usuario
- **Petición GET:**

```
api/users/{id}
```

#### Actualizar datos de usuario (Requiere autenticación)
- **Petición PUT:**

```
api/users/{id}
```

- **Cuerpo de la solicitud:**

```json
{
    "name": ""
}
```

### SEGUIR (ACCIONES)

Estas funciones permiten solicitar seguir, dejar de seguir y ver la lista de a quiénes sigue un usuario.

#### Lista de usuarios que sigue
- **Petición GET:**

```
api/users-following/{id}
```

- **Parámetro opcional:**

```
page=1
```

#### Seguir a un usuario (Requiere autenticación)
- **Petición POST:**

```
api/users-following/{id}
```

El `{id}` debe ser el del usuario al que se desea seguir.

#### Dejar de seguir a un usuario (Requiere autenticación)
- **Petición DELETE:**

```
api/users-following/{id}
```

El `{id}` debe ser el del usuario al que se desea dejar de seguir.

### TWEETS

#### Tweets de un usuario
- **Petición GET:**

```
api/users-tweets
```

- **Parámetro opcional:**

```
page=1
```

#### Tweets de usuarios que sigue (Requiere autenticación)
- **Petición GET:**

```
api/users-tweets-following 
```

- **Parámetro opcional:**

```
page=1
```

#### Crear tweet (Requiere autenticación)
- **Petición POST:**

```
users-tweets 
```

- **Cuerpo de la solicitud:**

```json
{ 
    "content": "" 
}
```

#### Actualizar tweet (Requiere autenticación)
- **Petición PUT:**

```
users-tweets/61
```

- **Cuerpo de la solicitud:**

```json
{
    "content": ""
}
```

#### Eliminar tweet (Requiere autenticación)
- **Petición DELETE:**

```
users-tweets/61
```

## Tecnologías y metodologías aplicadas

### Framework de Desarrollo Laravel (PHP)
Se escogió Laravel principalmente porque incluye herramientas integradas para la gestión de bases de datos, validaciones, queues (colas) y mucho más, lo que acelera el proceso de desarrollo.

### Patrón de diseño MVC y Repository
El diseño implementado combina el patrón **MVC (Modelo-Vista-Controlador)**, ampliamente utilizado en Laravel, con el patrón **Repository**, el cual abstrae y centraliza la interacción con la base de datos. Esta combinación mejora la organización del código, facilita la reutilización y promueve una arquitectura más mantenible y escalable.

### Desnormalización como diseño de base de datos
Se aplica la desnormalización para optimizar el rendimiento en las consultas de lectura, reduciendo la necesidad de múltiples **JOINs** y priorizando la velocidad. Para mitigar posibles inconsistencias, se implementan mecanismos como **eventos en Laravel, tareas en segundo plano con Redis y Supervisor**, garantizando que la información desnormalizada se mantenga actualizada de manera eficiente.

### Bases de Datos PostgreSQL y Redis
Se combinan **PostgreSQL** y **Redis** para aprovechar lo mejor de ambos mundos: la **persistencia y consistencia** de PostgreSQL junto con la **velocidad y eficiencia** de Redis en tareas de alta demanda o temporales. Aunque existen bases de datos más especializadas para manejar grandes volúmenes de datos, la **desnormalización** optimiza significativamente las consultas, mientras que **Redis** reduce la carga en la base de datos al agilizar las lecturas.

### Infraestructura

#### Docker
Se emplea un contenedor que incluye todas las tecnologías necesarias para complementar y aislar la aplicación.

#### Nginx + PHP-FPM
Servidor.

#### Redis
Almacenamiento en memoria para mejorar el rendimiento.

#### Supervisord
Es un supervisor de procesos utilizado para gestionar y controlar procesos en segundo plano. En este caso, se emplea principalmente para mantener activos los **workers** de cola de Laravel.
