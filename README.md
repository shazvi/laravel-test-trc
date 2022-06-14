## Getting Started

### Dependencies

- PHP 8.1
- Composer
- PostgreSQL 14
- Node.js v16

### Local setup

- Create a new database in your local postgres server
- Copy `.env.example` file to a new `.env` file and update its content to include your DB connection details
- Make sure your php, composer and nodejs executables are included in `PATH` and can be accessed via command line
- Make sure your local php installation has `pgsql`/`pdo_pgsql` extensions enabled.
- Execute the following commands in a terminal in the root of the repo:

```bash
composer install
npm install
npm run dev
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000) in your browser to see the result.

### Testing

- Rename `cypress.env.sample.json` to `cypress.env.json` and update its values to correspond to your dev environment.
- Make sure the backend server is up, and then open cypress:

```bash
npm run dev
npm run cypress
```

- In cypress's interactive tool, run the tests
