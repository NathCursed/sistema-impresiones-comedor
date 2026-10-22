<?php
require 'config.php';
requiere_login();

$area = $_GET['area'] ?? '';
$q    = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM catalogo WHERE 1=1";
$par = [];
if (in_array($area, AREAS, true)) { $sql .= " AND area = ?"; $par[] = $area; }
if ($q !== '') { $sql .= " AND (nombre LIKE ? OR categoria LIKE ?)"; $par[] = "%$q%"; $par[] = "%$q%"; }
$sql .= " ORDER BY area, categoria, nombre";

$stmt = $pdo->prepare($sql);
$stmt->execute($par);
$items = $stmt->fetchAll();

require 'header.php';
?>
<div class="fila-titulo">
  <h1>Catálogo</h1>
  <a class="btn" href="catalogo_form.php">+ Agregar al catálogo</a>
</div>

<form method="get" class="filtros">
  <select name="area">
    <option value="">Todas las áreas</option>
    <?php foreach (AREAS as $a): ?>
      <option <?= $area === $a ? 'selected' : '' ?>><?= $a ?></option>
    <?php endforeach; ?>
  </select>
  <input type="text" name="q" value="<?= e($q) ?>" placeholder="Buscar por nombre o categoría">
  <button type="submit">Filtrar</button>
  <?php if ($area || $q): ?><a class="btn-sec" href="catalogo.php">Limpiar</a><?php endif; ?>
</form>

<table>
  <thead><tr><th>Área</th><th>Categoría</th><th>Nombre</th><th>Precio</th><th>Unidad</th><th>Estado</th><th>Acciones</th></tr></thead>
  <tbody>
  <?php if (!$items): ?>
    <tr><td colspan="7" class="vacio">No hay elementos.</td></tr>
  <?php endif; ?>
  <?php foreach ($items as $i): ?>
    <tr class="<?= $i['activo'] ? '' : 'inactivo' ?>">
      <td><?= $i['area'] === 'Impresiones' ? '🖨️' : '🍽️' ?> <?= e($i['area']) ?></td>
      <td><?= e($i['categoria']) ?></td>
      <td><strong><?= e($i['nombre']) ?></strong></td>
      <td><?= dinero($i['precio']) ?></td>
      <td>por <?= e($i['unidad']) ?></td>
      <td><?= $i['activo'] ? '✅ Disponible' : '⛔ No disponible' ?></td>
      <td class="acciones">
        <a href="catalogo_form.php?id=<?= $i['id'] ?>">Editar</a>
        <form method="post" action="catalogo_eliminar.php" onsubmit="return confirm('¿Eliminar «<?= e($i['nombre']) ?>»?');">
          <?= csrf_campo() ?>
          <input type="hidden" name="id" value="<?= $i['id'] ?>">
          <button type="submit" class="peligro">Eliminar</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<p class="nota">Si un elemento ya fue usado en pedidos no se puede borrar; se marca como «No disponible» para conservar el historial.</p>
<?php require 'footer.php'; ?>
