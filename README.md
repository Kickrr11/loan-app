# Loans app

Simple loans app, This project utilizes Laravel Jetstream, Inertia, and Vue.js.

## Getting Started

Follow these steps to get the project up and running on your local machine.

### Prerequisites

- [PHP](https://www.php.net/manual/en/install.php) (>= 8.1)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/)
- [NPM](https://www.npmjs.com/)

### Installation

1. Clone the repository to your local machine:

    ```bash
    git clone git@github.com:Kickrr11/loan-app.git
    ```

2. Navigate to the project directory:

    ```bash
    cd your-project
    ```

3. Install PHP dependencies:

    ```bash
    composer install
    ```

4. Install Node.js dependencies:

    ```bash
    npm install
    ```

5. Copy the `.env.example` file to `.env` and configure your database settings.

6. Generate an application key:

    ```bash
    php artisan key:generate
    ```

7. Run the database migrations and seed the database:

    ```bash
    php artisan migrate --seed
    ```

8. Compile assets:

    ```bash
    npm run dev
    ```

### Usage

1. Start the development server:

    ```bash
    php artisan serve
    ```

2. Visit [http://localhost:8000](http://localhost:8000) in your browser.

3. Login using the following credentials:
    - **Email:** test_user@gmail.com
    - **Password:** secret

### Running Tests

To run the PHPUnit tests, use the following command:

```bash
vendor/bin/phpunit