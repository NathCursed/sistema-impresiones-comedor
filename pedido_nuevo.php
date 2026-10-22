<?php
require 'config.php';
requiere_login();

$error = '';
$f = ['cliente' => '', 'telefono' => '', 'tipo' => TIPOS[0], 'notas' => ''];
$cant = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $f['cliente']  = trim($_POST['cliente'] ?? '');
    $f['telefono'] = trim($_POST['telefono'] ?? '');
    $f['tipo']     = $_POST['tipo'] ?? '';
    $f['notas']    = trim($_POST['notas'] ?? '');
    $cant          = array_filter(array_map('intval', $_POST['cant'] ?? []), fn($n) => $n > 0);

    if ($f['cliente'] === '') {
        $error = 'Escriba el nombre del cliente.';
    } elseif (!in_array($f['tipo'], TIPOS, true)) {
        $error = 'Tipo de pedido no válido.';
    } elseif (!$cant) {
        $error = 'Agregue al menos un producto o servicio (cantidad mayor que 0).';
    } else {
        // Los precios se toman de la base de datos, NUNCA del formulario.
        $ids = array_keys($cant);
        $marcas = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT id, precio FROM catalogo WHERE activo = 1 AND id IN ($marcas)");
        $stmt->execute($ids);
        $precios = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        if (count($precios) !== count($ids)) {
            $error = 'Uno de los elementos ya no está disponible. Revise el pedido.';
        } else {
            $total = 0;
            foreach ($cant as $id => $n) $total += $precios[$id] * $n;

            $pdo->beginTransaction();
            try {
                $pdo->prepare("INSERT INTO pedidos (cliente, telefono, tipo, notas, total, usuario_id) VALUES (?,?,?,?,?,?)")
                    ->execute([$f['cliente'], $f['telefono'] ?: null, $f['tipo'], $f['notas'] ?: null, $total, $_SESSION['usuario_id']]);
                $pedidoId = (int)$pdo->lastInsertId();

                $det = $pdo->prepare("INSERT INTO pedido_detalle (pedido_id, catalogo_id, cantidad, precio_unitario, subtotal) VALUES (?,?,?,?,?)");
                foreach ($cant as $id => $n) {
                    $det->execute([$pedidoId, $id, $n, $precios[$id], $precios[$id] * $n]);
                }
                $pdo->commit();
            } catch (Throwable $ex) {
                $pdo->rollBack();
                throw $ex;
            }
            ir("pedido_ver.php?id=$pedidoId", "Pedido #$pedidoId registrado. Total: " . dinero($total));
        }
    }
}

$items = $pdo->query("SELECT * FROM catalogo WHERE activo = 1 ORDER BY area, categoria, nombre")->fetchAll();
$porArea = [];
foreach ($items as $it) $porArea[$it['area']][$it['categoria']][] = $it;

require 'header.php';
?>
<h1>Nuevo pedido</h1>
<?php if ($error): ?><div class="alerta error"><?= e($error) ?></div><?php endif; ?>

<form method="post" id="form-pedido">
  <?= csrf_campo() ?>
  <div class="tarjeta rejilla">
    <label>Cliente *<input type="text" name="cliente" required value="<?= e($f['cliente']) ?>" placeholder="Nombre del cliente"></label>
    <label>Teléfono<input type="text" name="telefono" value="<?= e($f['telefono']) ?>" placeholder="7000-0000"></label>
    <label>Tipo
      <select name="tipo">
        <?php foreach (TIPOS as $t): ?><option <?= $f['tipo'] === $t ? 'selected' : '' ?>><?= $t ?></option><?php endforeach; ?>
      </select>
    </label>
    <label>Notas<input type="text" name="notas" value="<?= e($f['notas']) ?>" placeholder="Ej.: doble cara, sin cebolla..."></label>
  </div>

  <div class="dos-col">
    <?php foreach (AREAS as $area): ?>
      <div class="tarjeta">
        <h2><?= $area === 'Impresiones' ? '🖨️' : '🍽️' ?> <?= $area ?></h2>
        <?php if (empty($porArea[$area])): ?>
          <p class="vacio">No hay elementos disponibles.</p>
        <?php else: foreach ($porArea[$area] as $cat => $lista): ?>
          <h3><?= e($cat) ?></h3>
          <?php foreach ($lista as $it): ?>
            <div class="linea-item">
              <span class="nombre"><?= e($it['nombre']) ?> <small><?= dinero($it['precio']) ?> / <?= e($it['unidad']) ?></small></span>
              <input type="number" min="0" name="cant[<?= $it['id'] ?>]" class="cant"
                     data-precio="<?= $it['precio'] ?>" value="<?= (int)($cant[$it['id']] ?? 0) ?>">
            </div>
          <?php endforeach; ?>
        <?php endforeach; endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="barra-total">
    <span>Total: <strong id="total-pedido">$0.00</strong></span>
    <button type="submit">Guardar pedido</button>
  </div>
</form>
<?php require 'footer.php'; ?>
