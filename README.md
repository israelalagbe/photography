# Photography API

Photography API to connect pro-photographers to people who need good photography for their products. Written in laravel and php.

## Installation

1. Clone the repository
   
```sh
git clone https://github.com/israelalagbe/photography.git
cd photography
```

2. Install dependencies

```sh
composer install
```

3. Set up environment variables
```sh
cp .env.example .env
php artisan key:generate
```
Edit the .env file with your database configuration

4. Run database migrations and seeders
```sh
php artisan migrate --seed
```

5. Run tests to ensure everything works correctly
```sh
php artisan test
```

## Usage
After installation, you can access the application locally by running:
```sh
php artisan serve
```




