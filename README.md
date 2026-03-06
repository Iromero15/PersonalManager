# PersonalManager
MVP: Gestor de Gastos Personales

Este es un proyecto de demostración desarrollado en una semana. Se trata de un gestor de finanzas personales que implementa un CRUD completo, autenticación basada en tokens y un panel de administración profesional.
🚀 El Stack Tecnológico

La arquitectura se diseñó pensando en la escalabilidad y el rendimiento, utilizando contenedores para asegurar la consistencia en el despliegue:

    Backend: Laravel 11 (PHP 8.4) con Laravel Sanctum para el manejo de sesiones.

    Frontend: AngularJS 1.8 (Legacy) implementando un patrón MVC puro con interceptores de red.

    Interfaz: Template administrativo SB Admin 2 (basado en Bootstrap 4).

    Base de Datos: MariaDB.

    Infraestructura: Orquestación con Docker Compose y Nginx como Reverse Proxy.

🛠️ Características Implementadas

    Autenticación: Sistema de login con almacenamiento seguro de tokens en localStorage.

    Resumen en tiempo real: Panel con Balance Total, Ingresos y Egresos sincronizados con la API.

    Gestión de Transacciones: Creación, lectura, edición y borrado (CRUD) de movimientos.

    Categorías Dinámicas: Los movimientos se asocian a categorías que el usuario puede gestionar en vivo.

    Seguridad: Interceptores de AngularJS para inyectar headers de autorización en cada petición asíncrona.

📦 Instalación y Despliegue
Requisitos previos

    Docker y Docker Compose.

    Git.

Pasos rápidos

    Clonar el repositorio:
    Bash

    git clone https://github.com/tu-usuario/gestor-gastos.git
    cd gestor-gastos

    Configurar entorno:
    Copiá el archivo .env.example a .env dentro de la carpeta backend y configurá tus credenciales de base de datos.

    Levantar contenedores:
    Bash

    docker-compose up -d --build

    Configurar base de datos y permisos:
    Bash

    docker-compose exec backend php artisan migrate --seed
    docker-compose exec backend chown -R www-data:www-data storage bootstrap/cache

🍓 Despliegue en Raspberry Pi 5

Este proyecto está optimizado para correr en hardware ARM64, específicamente en una Raspberry Pi 5 de 8GB. Al construir las imágenes directamente en la Pi, Docker optimiza los binarios para la arquitectura del procesador.

    Nota para el evaluador: El frontend se sirve como estáticos puros a través de Nginx, minimizando la carga de CPU y memoria en el host.

Desarrollado por: Ignacio - Estudiante de Ciencias de la Computación (UBA) & Técnico Electrónico.
