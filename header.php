<?php $pagina = basename($_SERVER['SCRIPT_NAME']); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(NEGOCIO) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="barra">
  <div class="marca">🖨️🍽️ <?= e(NEGOCIO) ?></div>
  <nav>
    <a href="index.php"         class="<?= $pagina === 'index.php' ? 'activo' : '' ?>">Inicio</a>
    <a href="pedido_nuevo.php"  class="<?= $pagina === 'pedido_nuevo.php' ? 'activo' : '' ?>">+ Nuevo pedido</a>
    <a href="pedidos.php"       class="<?= in_array($pagina, ['pedidos.php', 'pedido_ver.php']) ? 'activo' : '' ?>">Pedidos</a>
    <a href="catalogo.php"      class="<?= in_array($pagina, ['catalogo.php', 'catalogo_form.php']) ? 'activo' : '' ?>">Catálogo</a>
    <a href="reportes.php"      class="<?= $pagina === 'reportes.php' ? 'activo' : '' ?>">Reportes</a>
  </nav>
  <div class="usuario">
    👤 <?= e($_SESSION['nombre'] ?? '') ?> ·
    <a href="clave.php">Contraseña</a> ·
    <a href="logout.php">Salir</a>
  </div>
</header>
<main>
<?php if (!empty($_GET['msg'])): ?><div class="alerta ok"><?= e($_GET['msg']) ?></div><?php endif; ?>
