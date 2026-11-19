# php-mysql-shop

A small product catalog in PHP + MySQL — the 5AM Cookies lineup served from
a database, with a list view, a category filter, and a detail page. Every
query is a prepared statement, and every bit of output is escaped.

## Setup

1. Create the database and load the sample products:

   ```bash
   mysql -u root -p < schema.sql
   ```

2. Serve the folder. For local development, PHP's built-in server is enough:

   ```bash
   php -S localhost:8000
   ```

   Then open <http://localhost:8000>.

3. If your MySQL isn't on `127.0.0.1` with database `shop`, set environment
   variables instead of editing the code:

   ```bash
   DB_HOST=localhost DB_NAME=shop DB_USER=shopuser DB_PASS='secret' php -S localhost:8000
   ```

## How it's put together

| File | What it does |
|---|---|
| `schema.sql` | `products` table + six sample products from the real lineup |
| `config.php` | PDO connection: env-based credentials, exceptions on, real prepared statements |
| `index.php` | List view (with `?category=` filter) and detail view (`?id=`), both via prepared statements |

## Security points this demonstrates

- **Prepared statements everywhere** — the `id` and `category` from the URL
  are bound parameters, never interpolated into SQL.
- **Input validation** — `id` must be an integer (`FILTER_VALIDATE_INT`);
  anything else falls back to the list view.
- **Output escaping** — `htmlspecialchars()` on every product field.
- **No credentials in code** — connection details come from the environment.
- **No error leakage** — database errors are logged server-side; visitors see
  a generic message.

## License

MIT — see `LICENSE`.
