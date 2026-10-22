<?php
require 'config.php';
requiere_login();

$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde)) $desde = date('Y-m-01');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta)) $hasta = date('Y-m-d');

$base = "FROM pedido_detalle d
         JOIN pedidos p  ON p.id = d.pedido_id
         JOIN catalogo c ON c.id = d.catalogo_id
         WHERE p.estado <> 'Cancelado' AND DATE(p.fecha) BETWEEN ? AND ?";

// Total por área
$stmt = $pdo->prepare("SELECT c.area, SUM(d.subtotal) $base GROUP BY c.area");
$stmt->execute([$desde, $hasta]);
$porArea = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$total = array_sum($porArea);

// Número de pedidos
$stmt = $pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE estado <> 'Cancelado' AND DATE(fecha) BETWEEN ? AND ?");
$stmt->execute([$desde, $hasta]);
$numPedidos = (int)$stmt->fetchColumn();

// Lo más vendido
$stmt = $pdo->prepare("SELECT c.nombre, c.area, SUM(d.cantidad) AS cant, SUM(d.subtotal) AS dinero
                       $base GROUP BY c.id ORDER BY dinero DESC LIMIT 10");
$stmt->execute([$desde, $hasta]);
$top = $stmt->fetchAll();

// Ventas por día
$stmt = $pdo->prepare("SELECT DATE(p.fecha) AS dia,
                              SUM(CASE WHEN c.area='Impresiones' THEN d.subtotal ELSE 0 END) AS imp,
                              SUM(CASE WHEN c.area='Comida' THEN d.subtotal ELSE 0 END) AS com,
                              SUM(d.subtotal) AS total
                       $base GROUP BY DATE(p.fecha) ORDER BY dia DESC");
$stmt->execute([$desde, $hasta]);
$dias = $stmt->fetchAll();

require 'header.php';
?>
<h1>Reportes de ventas</h1>

<form method="get" class="filtros no-imprimir">
  <label>Desde <input type="date" name="desde" value="<?= e($desde) ?>"></label>
  <label>Hasta <input type="date" name="hasta" value="<?= e($hasta) ?>"></label>
  <button type="submit">Ver reporte</button>
  <button type="button" class="btn-sec" onclick="window.print()">🖨️ Imprimir</button>
</form>

<p>Del <strong><?= date('d/m/Y', strtotime($desde)) ?></strong> al <strong><?= date('d/m/Y', strtotime($hasta)) ?></strong></p>

<div class="stats">
  <div class="stat"><strong><?= dinero($total) ?></strong><span>Total vendido</span></div>
  <div class="stat imp"><strong><?= dinero($porArea['Impresiones'] ?? 0) ?></strong><span>🖨️ Impresiones</span></div>
  <div class="stat com"><strong><?= dinero($porArea['Comida'] ?? 0) ?></strong><span>🍽️ Comida</span></div>
  <div class="stat"><strong><?= $numPedidos ?></strong><span>Pedidos</span></div>
</div>

<div class="dos-col">
  <div class="tarjeta">
    <h2>🏆 Lo más vendido</h2>
    <table>
      <thead><tr><th>Producto / servicio</th><th>Cant.</th><th>Dinero</th></tr></thead>
      <tbody>
      <?php if (!$top): ?><tr><td colspan="3" class="vacio">Sin ventas en este periodo.</td></tr><?php endif; ?>
      <?php foreach ($top as $t): ?>
        <tr><td><?= $t['area'] === 'Impresiones' ? '🖨️' : '🍽️' ?> <?= e($t['nombre']) ?></td><td><?= (int)$t['cant'] ?></td><td><?= dinero($t['dinero']) ?></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="tarjeta">
    <h2>📅 Ventas por día</h2>
    <table>
      <thead><tr><th>Día</th><th>🖨️</th><th>🍽️</th><th>Total</th></tr></thead>
      <tbody>
      <?php if (!$dias): ?><tr><td colspan="4" class="vacio">Sin ventas en este periodo.</td></tr><?php endif; ?>
      <?php foreach ($dias as $d): ?>
        <tr><td><?= date('d/m/Y', strtotime($d['dia'])) ?></td><td><?= dinero($d['imp']) ?></td><td><?= dinero($d['com']) ?></td><td><strong><?= dinero($d['total']) ?></strong></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require 'footer.php'; ?>
