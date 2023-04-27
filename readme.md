# SlimReactAuth

SlimReactAuth is a demo application that showcases user authentication with Slim Framework and ReactJS. The application provides a comprehensive user authentication system with features such as registration, login, dashboard viewing, and password recovery through a "forgot password" option. The application also implements Mailgun for email sending via the Mailer.php class in App\Infrastructure\Mailer, and it features both client-side and server-side validation with guidance from domain-driven design principles.
To try out the demo version of SlimReactAuth, please visit http://demo-auth.ungurean.net/login.

## Getting Started

To get started with the development version of SlimReactAuth, follow these steps:

### Prerequisites

You will need to have the following software installed on your machine:

- Docker
- Docker Compose

### Installation

1. Clone the repository: `git clone https://github.com/your-username/SlimReactAuth.git`
2. Change into the project directory: `cd SlimReactAuth`
3. Start the containers: `docker-compose up -d --build`
4. Access the frontend by visiting `http://localhost:3000` in your browser.
5. Access the backend by visiting `http://localhost` in your browser.
6. To access phpMyAdmin, go to http://localhost:8080 in your browser. The username is `root` and the password is `root_password`.

#### To run the migrations to create the MySQL database structure in your PHP container, follow these steps:

1. Open a terminal and navigate to the root directory of your project.
2. Enter the command `docker exec -it php bash` to open a shell in the PHP container.
3. Once inside the PHP container, navigate to the `/var/www/html` directory by entering `cd /var/www/html`.
4. Finally, run the migration command by entering `./vendor/bin/phinx migrate --configuration app/phinx.php`.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

This uses the Slim-Skeleton package, which provides a basic structure and configuration for developing web applications using the Slim Framework. You can learn more about the Slim-Skeleton package at https://github.com/slimphp/Slim-Skeleton.
