<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Laravel + Filament

## Paquetes

-   Datos de ciudades

    - https://github.com/altwaireb/laravel-world

    -   `composer require altwaireb/laravel-world`

    -   `php artisan world:install`

    -   `php artisan world:seeder`

- Crear un recurso en filament con el crud

    - `php artisan make:filament-resource Country --generate`

    - `php artisan make:filament-resource State --generate`

    - `php artisan make:filament-resource City --generate`

- Agregar una columna a una tabla ya existente

    address seria la columna que se va a gregar a la tabla users

    - `php artisan make:migration add_address_fields_to_users_table`


8.-