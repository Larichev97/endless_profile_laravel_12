<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## The project requires PHP 8.2.24

## Start via Docker + Linux:

1. Open a terminal in Linux:
- Run the command: `sudo nano /etc/hosts`
- Add a host for the project: `127.0.0.1 endless-profile-v3.loc`
- Save the changes and exit with the keyboard shortcuts: `CTRL+X` ---> `Y` ---> `Enter`;

2. Install <b>Make</b> for Linux to make the commands from the Makefile work (skip it if you already have it):
- `sudo apt-get update`
- `sudo apt-get -y install make`

3. Open a terminal in PhpStorm (in the root of the project) and execute the following `make` commands:
- `make build-containers`
- `make build-project`

Optional: if `mysql container` <b>not running</b> (error with permissions), provide `chmod 777 permissions` for the <b>/docker/volumes/mysql</b> directory via terminal from this directory `mysql`:
- `sudo chmod -R 777 .` (terminal must be opened in the `your_project_root/docker/volumes/mysql` directory)

4. Create `endless_profile_v3` Data Base (`uft8mb4` / `uft8mb4_general_ci`) in MySQL and execute the following `make` commands via PhpStorm terminal (in the root of the project):
- `make migrate`
- `make seed`

5. Go to admin panel: http://endless-profile-v3.loc/login via default User:
 - Email: `admin@endless-profile.com`
 - Password: `secret`

Or create account via http://endless-profile-v3.loc/register 

6. Configure X-Debug в PhpStorm:
- Go to `File` ---> `Settings` ---> `PHP`>`Debug`;
- Under `Max.simultaneous connections` specify ‘1’ (default is ‘3’);
- Under `Xdebug` at the Debug port field, enter port `9001` with ‘,’ (if it was not there);
- Press `Apply` and `OK` button;

## Usefully commands in PHP container:

- `composer install`
- `php artisan key:generate`
- `php artisan migrate:fresh --seed`
- `php artisan storage:link`
- `php artisan l5-swagger:generate` (if you need to update the API documentation)

<b>Work with Cache:</b>

- `php artisan cache:clear`
- `php artisan config:clear`
- `php artisan route:clear`

<b>File Optimisation:</b>

- `php artisan optimize`

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
