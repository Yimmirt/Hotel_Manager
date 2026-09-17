# Hotel Manager

Sistema web para la administración de un hotel, desarrollado con PHP 8 y PostgreSQL.

## Descripción

Hotel Manager permite administrar los procesos principales de un hotel mediante una aplicación web con autenticación de usuarios y operaciones CRUD.

El sistema permite gestionar:

- Usuarios
- Clientes
- Habitaciones
- Reservaciones
- Servicios
- Pagos

## Tecnologías utilizadas

- PHP 8.4
- PostgreSQL 18
- HTML5
- CSS3
- PDO
- Git
- GitHub

## Base de datos

El proyecto utiliza PostgreSQL.

La base de datos contiene 6 tablas principales:

1. usuarios
2. clientes
3. habitaciones
4. reservaciones
5. servicios
6. pagos

Cada tabla cuenta con al menos 15 registros de prueba.

## Funcionalidades

- Inicio de sesión
- Manejo de sesiones
- Registro de usuarios
- CRUD de clientes
- CRUD de habitaciones
- CRUD de reservaciones
- CRUD de servicios
- CRUD de pagos
- CRUD de usuarios
- Relaciones entre tablas
- Uso de llaves foráneas
- Contraseñas protegidas con password_hash()
- Validación mediante password_verify()

## Estructura del proyecto

```text
Hotel_Manager/
│
├── config/
│   ├── conexion.php
│   ├── conexion.example.php
│   └── prueba.php
│
├── clientes/
├── habitaciones/
├── reservaciones/
├── servicios/
├── pagos/
├── usuarios/
├── css/
│
├── index.php
├── login.php
├── dashboard.php
├── logout.php
├── README.md
└── .gitignore