<?php ?>
<div class="page-header">
    <div>
        <h2 class="page-title">Nuevo Artículo</h2>
        <p class="page-subtitle">Registrar un nuevo artículo en el inventario</p>
    </div>
    <a href="<?= BASE_URL ?>/articles" class="btn btn-secondary">← Volver</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-title">Datos del artículo</div>
    <form method="POST" action="<?= BASE_URL ?>/articles/store">

        <div class="form-grid" style="margin-bottom:16px">
            <div class="form-group">
                <label>Marca *</label>
                <input type="text" name="marca" value="<?= htmlspecialchars($old['marca'] ?? '') ?>"
                       placeholder="Ej: Samsung" required>
            </div>
            <div class="form-group">
                <label>Modelo *</label>
                <input type="text" name="modelo" value="<?= htmlspecialchars($old['modelo'] ?? '') ?>"
                       placeholder="Ej: Galaxy S24" required>
            </div>
        </div>

        <div class="form-group full" style="margin-bottom:16px">
            <label>Descripción *</label>
            <textarea name="descripcion" placeholder="Descripción detallada del artículo..." required><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="form-grid cols-3" style="margin-bottom:16px">
            <div class="form-group">
                <label>Categoría *</label>
                <input type="text" name="categoria" value="<?= htmlspecialchars($old['categoria'] ?? '') ?>"
                       placeholder="Ej: Electrónica" required list="categorias-list">
                <datalist id="categorias-list">
                    <option value="Electrónica">
                    <option value="Calzado">
                    <option value="Ropa">
                    <option value="Computadores">
                    <option value="Hogar">
                    <option value="Deportes">
                    <option value="Alimentos">
                    <option value="Juguetes">
                    <option value="Libros">
                </datalist>
            </div>
            <div class="form-group">
                <label>Proveedor *</label>
                <input type="text" name="proveedor" value="<?= htmlspecialchars($old['proveedor'] ?? '') ?>"
                       placeholder="Nombre del proveedor" required>
            </div>
            <div class="form-group">
                <label>Tienda *</label>
                <input type="text" name="tienda" value="<?= htmlspecialchars($old['tienda'] ?? '') ?>"
                       placeholder="Nombre de la tienda" required>
            </div>
        </div>

        <div class="form-grid cols-3" style="margin-bottom:24px">
            <div class="form-group">
                <label>Precio de Compra (COP) *</label>
                <input type="number" name="precio_compra" step="0.01" min="0"
                       value="<?= htmlspecialchars($old['precio_compra'] ?? '') ?>"
                       placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label>Precio de Venta (COP) *</label>
                <input type="number" name="precio_venta" step="0.01" min="0"
                       value="<?= htmlspecialchars($old['precio_venta'] ?? '') ?>"
                       placeholder="0.00" required id="precio_venta">
            </div>
            <div class="form-group">
                <label>IVA (%) *</label>
                <select name="iva">
                    <option value="0"  <?= ($old['iva'] ?? '19') == '0'  ? 'selected' : '' ?>>0% — Exento</option>
                    <option value="5"  <?= ($old['iva'] ?? '19') == '5'  ? 'selected' : '' ?>>5% — Reducido</option>
                    <option value="19" <?= ($old['iva'] ?? '19') == '19' ? 'selected' : '' ?>>19% — General</option>
                </select>
            </div>
        </div>

        <div class="form-grid" style="margin-bottom:24px">
            <div class="form-group">
                <label>Cantidad en stock *</label>
                <input type="number" name="cantidad" min="0"
                       value="<?= htmlspecialchars($old['cantidad'] ?? '0') ?>"
                       placeholder="0" required>
            </div>
            <div class="form-group" style="justify-content:flex-end">
                <div id="preview-precio" style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:14px;font-size:.85rem;color:var(--muted)">
                    <strong style="display:block;color:var(--text);margin-bottom:6px">Vista previa de precios</strong>
                    <span id="prev-venta">Precio venta: —</span><br>
                    <span id="prev-iva">Precio con IVA: —</span>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-success">&#10003; Guardar Artículo</button>
            <a href="<?= BASE_URL ?>/articles" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
function updatePreview() {
    const pv  = parseFloat(document.querySelector('[name=precio_venta]').value) || 0;
    const iva = parseFloat(document.querySelector('[name=iva]').value) || 0;
    const fmt = v => '$' + v.toLocaleString('es-CO', {minimumFractionDigits:0, maximumFractionDigits:0});
    document.getElementById('prev-venta').textContent = 'Precio venta: ' + fmt(pv);
    document.getElementById('prev-iva').textContent   = 'Precio con IVA (' + iva + '%): ' + fmt(pv * (1 + iva/100));
}
document.querySelectorAll('[name=precio_venta],[name=iva]').forEach(el => el.addEventListener('input', updatePreview));
updatePreview();
</script>
