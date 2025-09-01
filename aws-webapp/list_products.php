<?php
include "/var/www/inc/dbinfo.inc";

$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($db->connect_errno) { die('Conexão falhou: '.$db->connect_error); }

$res = $db->query('SELECT id,name,price,quantity,release_date FROM products ORDER BY id DESC');
if (!$res) { die('Erro ao buscar produtos: '.$db->error); }
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Products List</title>
<style>
  body{font-family:Arial,sans-serif;margin:2rem;background:#f4f4f4}
  table{width:100%;border-collapse:collapse;background:#fff}
  th,td{padding:.7rem;border:1px solid #e5e5e5;text-align:left}
  th{background:#0366d6;color:#fff}
  a{display:inline-block;margin-bottom:1rem;color:#0366d6;text-decoration:none}
</style>
</head>
<body>
<h1>Products List</h1>

<a href="create_product.php">Adicionar novo</a>

<table>
  <thead>
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Release Date</th></tr>
  </thead>
  <tbody>
<?php while($row=$res->fetch_assoc()): ?>
  <tr>
    <td><?=htmlspecialchars($row['id'])?></td>
    <td><?=htmlspecialchars($row['name'])?></td>
    <td><?=htmlspecialchars(number_format($row['price'],2))?></td>
    <td><?=htmlspecialchars($row['quantity'])?></td>
    <td><?=htmlspecialchars($row['release_date'])?></td>
  </tr>
<?php endwhile; ?>
  </tbody>
</table>
</body>
</html>
<?php
$res->free();
$db->close();

