<?php
require 'config.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') ir('catalogo.php');
verificar_csrf();

$id = (int)($_POST['id'] ?? 0);

// ¿Ya se usó en algún pedido? Entonces solo se desactiva (para no perder el historial).
$stmt = $pdo->prepare("SELECT COUNT(*) FROM pedido_detalle WHERE catalogo_id = ?");
$stmt->execute([$id]);

if ($stmt->fetchColumn() > 0) {
    $pdo->prepare("UPDATE catalogo SET activo = 0 WHERE id = ?")->execute([$id]);
    ir('catalogo.php', 'Este elemento ya tiene pedidos, así que se marcó como «No disponible».');
}

$pdo->prepare("DELETE FROM catalogo WHERE id = ?")->execute([$id]);
ir('catalogo.php', 'Elemento eliminado.');
