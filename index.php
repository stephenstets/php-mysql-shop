<?php
// index.php — product catalog: list view, and a detail view via ?id=.
//
// Every query uses a prepared statement with bound parameters — user input
// never goes into the SQL string. Output is escaped with htmlspecialchars()
// so product text can't break the page (or inject markup).

declare(strict_types=1);

require __DIR__ . '/config.php';

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// --- Detail view: /index.php?id=3 -----------------------------------------
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

try {
    $pdo = db();

    if ($id !== null && $id !== false) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
    } else {
        // --- List view: optional ?category= filter -------------------------
        $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($category) {
            $stmt = $pdo->prepare(
                'SELECT * FROM products WHERE category = :category ORDER BY name'
            );
            $stmt->execute(['category' => $category]);
        } else {
            $stmt = $pdo->query('SELECT * FROM products ORDER BY name');
        }
        $products = $stmt->fetchAll();
    }
} catch (PDOException $ex) {
    http_response_code(500);
    // Log the real error; show the visitor a generic message, not the
    // connection details.
    error_log('Database error: ' . $ex->getMessage());
    exit('Sorry — the catalog is unavailable right now. Please try again later.');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>5AM Cookies — Product Catalog</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 760px; margin: 2rem auto; padding: 0 1rem; line-height: 1.6; }
    .product { border: 1px solid #ccc; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
    .price { font-weight: bold; }
    .out { color: #b00; }
    .in-stock { color: #080; }
  </style>
</head>
<body>
  <h1>5AM Cookies — Product Catalog</h1>

<?php if (isset($product)): ?>
  <?php if ($product): ?>
    <p><a href="index.php">&larr; All products</a></p>
    <div class="product">
      <h2><?= e($product['name']) ?></h2>
      <p><?= e($product['description']) ?></p>
      <p class="price">$<?= number_format((float) $product['price'], 2) ?></p>
      <p class="<?= (int) $product['stock'] > 0 ? 'in-stock' : 'out' ?>">
        <?= (int) $product['stock'] > 0
            ? (int) $product['stock'] . ' in stock'
            : 'Made to order' ?>
      </p>
      <p>Category: <?= e($product['category']) ?></p>
    </div>
  <?php else: ?>
    <p>No product with that ID. <a href="index.php">Back to the catalog</a>.</p>
  <?php endif; ?>

<?php else: ?>
  <p>
    Filter:
    <a href="index.php">All</a> ·
    <a href="index.php?category=pet">Pet</a> ·
    <a href="index.php?category=golf">Golf</a> ·
    <a href="index.php?category=desk">Desk</a> ·
    <a href="index.php?category=home">Home</a> ·
    <a href="index.php?category=made-to-order">Made to order</a>
  </p>
  <?php foreach ($products as $p): ?>
    <div class="product">
      <h2><a href="index.php?id=<?= (int) $p['id'] ?>"><?= e($p['name']) ?></a></h2>
      <p><?= e($p['description']) ?></p>
      <p class="price">$<?= number_format((float) $p['price'], 2) ?></p>
      <p class="<?= (int) $p['stock'] > 0 ? 'in-stock' : 'out' ?>">
        <?= (int) $p['stock'] > 0
            ? (int) $p['stock'] . ' in stock'
            : 'Made to order' ?>
      </p>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
</body>
</html>
