<?php
include "/var/www/inc/dbinfo.inc";

$name = $price = $quantity = $release_date = '';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name         = trim($_POST['name'] ?? '');
  $price        = trim($_POST['price'] ?? '');
  $quantity     = trim($_POST['quantity'] ?? '');
  $release_date = trim($_POST['release_date'] ?? '');

  if ($name === '' || $price === '' || $quantity === '') {
    $msg = 'Preencha name, price e quantity.';
  } else {
    $db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($db->connect_errno) { die('Conexão falhou: '.$db->connect_error); }

    $stmt = $db->prepare('INSERT INTO products (name,price,quantity,release_date) VALUES (?,?,?,?)');
    $stmt->bind_param('sdis', $name, $price, $quantity, $release_date);
    $ok = $stmt->execute();
    $msg = $ok ? 'Produto adicionado!' : ('Erro: '.$stmt->error);

    $stmt->close();
    $db->close();

    if ($ok) { $name = $price = $quantity = $release_date = ''; }
  }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Novo Produto</title>
<style>
  body{font-family:Arial,sans-serif;margin:2rem;background:#f4f4f4}
  form{max-width:420px;background:#fff;padding:1rem;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,.08)}
  input,button{padding:.6rem;margin:.35rem 0;width:100%}
  a{display:inline-block;margin-top:1rem;color:#0366d6;text-decoration:none}
</style>
</head>
<body>
<h1>Novo Produto</h1>

<?php if ($msg) echo '<p><strong>'.htmlspecialchars($msg).'</strong></p>'; ?>

<form method="post" action="">
  <label>Nome *</label>
  <input name="name" value="<?=htmlspecialchars($name)?>" required>

  <label>Preço (ex.: 9.99) *</label>
  <input type="number" step="0.01" name="price" value="<?=htmlspecialchars($price)?>" required>

  <label>Quantidade *</label>
  <input type="number" name="quantity" value="<?=htmlspecialchars($quantity)?>" required>

  <label>Data de lançamento</label>
  <input type="date" name="release_date" value="<?=htmlspecialchars($release_date)?>">

  <button type="submit">Adicionar</button>
</form>

<a href="list_products.php">Ver lista de produtos</a>
</body>
</html>
