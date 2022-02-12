### 1. To clone denny test auto api:

```bash
git clone git@github.com:hahadenny/denny_test.git
```

### 2. Install vendor dependencies:

```bash
cd denny_test
composer install
```

### 3. update database data in .env file

```bash
vi .env
```

### 4. Import `Vehicle` table:

```bash
php artisan migrate
```

### 5. Fixture testing with PHPUnit:

```bash
./vendor/bin/phpunit
```

### 6. Swagger Documentation

```bash
unzip denny-auto-api-swagger.zip
```
