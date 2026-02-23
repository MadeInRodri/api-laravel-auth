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

- POST /api/register : Registra un nuevo usuario (Rol default: employee).
- POST /api/login : Inicia sesión y devuelve el Token de acceso.

### Gestión de Usuarios (CRUD)

- GET /api/users : Lista de usuarios (Filtrable con ?role=admin o ?role=employee).
- GET /api/users/{id} : Obtiene datos de un usuario específico.
- POST /api/users : Crea un nuevo usuario/empleado.
- PATCH /api/users/{id} : Actualización parcial de datos.
- DELETE /api/users/{id} : Elimina un usuario de la base de datos.

---
## DESPLIEGUE EN KUBERNETES + DOCKER
## Antes de esto necesitas tener -> Docker Desktop, Kubectl y Chocolatey.
1. Arrancar Minikube
   Primero inicia tu entorno Kubernetes: minikube start (Acá podés agregarle una
   flag para que te cree más nodos)
   Luego asegurate que existe un nodo de Minikube con: kubectl get nodes.
   deberia de salirte el nodo principal de minikube o más si quisiste agregar más
2. Crear las imágenes con DOCKER en Minikube
   En este caso vamos a crear una imagen para PHP, que es tu app de laravel →
   minikube image build -t laravel-php:v2 -f dockerfile/php.dockerfile .
   Y vamos a crear una para el servidor nginx
   minikube image build -t laravel-nginx:v3 -f dockerfile/nginx.dockerfile
3. Crear los deployment, service y hpa.
   Luego deployear los .yaml → kubectl apply -f php-service.yaml.
   Vas a hacer este paso con todos los .yaml, no importa el orden.
4. Crear el secrete para JWT
   kubectl create secret generic laravel-secrets —fromliteral=JWT_SECRET=ljsadfgkjlsadjksad
5. Crear el tunnel y obtener la IP del external IP
   minikube tunnel -> NO VAYAS A CERRAR ESTE TERMINAL.
   minikube service laravel-service -> NO VAYAS A CERRAR ESTE TERMINAL
---

## Equipo

- Rodrigo Alexis Mejía Rivas
- Bryan Josué Fuentes Molina
- Valeria Liseth Paredes Lara
- Leonardo Enrique Flores Coto
- Andre Emanuel Preza Deras
- Joaquín Eduardo Morán Mejía
- Estudiantes de Ingeniería en Ciencias de la Computación
