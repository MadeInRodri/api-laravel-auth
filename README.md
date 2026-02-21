# API de Gestión de Empleados (Laravel + JWT)

Este proyecto consiste en una API RESTful desarrollada con Laravel 12 para la gestión de empleados. Incluye autenticación basada en tokens, control de acceso por roles y está preparada para despliegue en contenedores Docker y orquestación con Kubernetes.

## Tecnologías y Herramientas

- Framework: Laravel 12
- Base de Datos: SQLite
- Autenticación: JWT
- Contenerización: Docker & Docker Compose
- Orquestación: Kubernetes (2 réplicas distribuidas)

---

## Instalación y Despliegue Local

1. Clonar el repositorio:
   git clone https://github.com/MadeInRodri/api-laravel-auth

---

## Documentación de Endpoints

Nota: Todas las solicitudes deben incluir el Header "Accept: application/json".

### Autenticación

- POST /api/registro : Registra un nuevo usuario (Rol default: employee).
- POST /api/login : Inicia sesión y devuelve el Token de acceso.

### Gestión de Usuarios (CRUD)

- GET /api/usuarios : Lista de usuarios (Filtrable con ?role=admin o ?role=employee).
- GET /api/usuarios/{id} : Obtiene datos de un usuario específico.
- PATCH /api/usuarios/{id} : Actualización parcial de datos.
- DELETE /api/usuarios/{id} : Elimina un usuario de la base de datos.

---

## Estructura de Docker


---

## Configuración de Kubernetes


---

## Equipo

- Rodrigo Alexis Mejía Rivas
- Bryan Josué Fuentes Molina
- Valeria Liseth Paredes Lara
- Leonardo Enrique Flores Coto
- Andre Emanuel Preza Deras
- Joaquín Eduardo Morán Mejía
- Estudiantes de Ingeniería en Ciencias de la Computación
