<?php
require 'config.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') ir('pedidos.php');
verificar_csrf();

$id     = (int)($_POST['id'] ?? 0);
$estado = $_POST['estado'] ?? '';

if (!in_array($estado, ESTADOS, true)) ir("pedido_ver.php?id=$id", 'Estado no válido.');

$pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?")->execute([$estado, $id]);
ir("pedido_ver.php?id=$id", "El pedido #$id ahora está: $estado");
