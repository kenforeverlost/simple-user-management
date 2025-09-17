# Simple User Management Project

This project allows the user to create, read, update, and delete users in a local SQL database.

---

## Getting Started

### Prerequisites

    - [PHP](https://www.php.net/downloads.php) (version 7.4+ recommended, PDO MySQL extension enabled)
    - [Docker](https://www.docker.com/) (for database container)
    - A web browser (e.g., Chrome, Firefox, Edge)

Verify that PHP is installed:

```bash
php -v
```

Verify that Docker is installed:

```bash
docker --version
```

### Installation

1. Clone the repository:

```bash
git clone https://github.com/kenforeverlost/simple-user-management.git
```

2. Navigate into the project folder:

```bash
cd simple-user-management
```

### 🗄 Database (Docker)

1. Start a MySQL container:

```bash
docker run --name userdb -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=userdb -p 3306:3306 -d mysql:8
```

2. Import schema:

- **Linux/macOS / CMD (Windows):**

```bash
docker exec -i userdb mysql -uroot -proot userdb < /lib/sql/users_table.sql
```

- **PowerShell (Windows):**

```bash
Get-Content .\lib\sql\users_table.sql | docker exec -i userdb mysql -uroot -proot userdb
```

3. Update your `config.php` file with:

```php
$host = "127.0.0.1";
$db   = "userdb";
$user = "root";
$pass = "root";
```

### Running Locally

1. Start the built-in PHP development server:

```bash
php -S localhost:8000
```

2. Then open your browser and go to:

```
http://localhost:8000
```

### Connecting Database in VS Code (Optional)

1. Install **SQLTools** extension and **SQLTools MySQL/MariaDB Driver**.

2. Add a new connection with the following settings:

   - **Connection Name:** `UserDB Local`
   - **Server/Host:** `127.0.0.1`
   - **Port:** `3306`
   - **Database:** `userdb`
   - **Username:** `root`
   - **Password:** `root`
   - **Password Save Mode:** `save` (or `prompt`)

3. Test the connection → If successful, expand tables and run queries directly in VS Code.

---

## Features

- Create new users
- View user list
- Update user details
- Delete users

---

## Tech Stack

- PHP
- MySQL (via Docker)
- HTML
- JavaScript
- CSS
