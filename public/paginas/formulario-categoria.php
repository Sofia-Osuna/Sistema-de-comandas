<?php
// Cuando exista auth.php, descomentar:
// require_once __DIR__ . '/../../includes/auth.php';
// requerirRol(['admin']);

$id        = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$esEdicion = $id > 0;

// Datos de ejemplo (después vendrán de modelos/Categoria.php con PDO)
$ejemplos = [
    1 => ['categoria' => 'Entradas'],
    2 => ['categoria' => 'Bebidas'],
    3 => ['categoria' => 'Postres'],
];

// Valores por defecto para "Nueva categoría"
$categoria = ['categoria' => ''];

if ($esEdicion && isset($ejemplos[$id])) {
    $categoria = $ejemplos[$id];
}

$titulo = $esEdicion ? 'Editar Categoría' : 'Nueva Categoría';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $titulo ?> | Beanora</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/usuario-form.css">
</head>
<body>
<div class="d-flex flex-column flex-lg-row">

    <?php include '../../includes/sidebar.php'; ?>

    <main class="contenido-panel flex-grow-1 p-4 d-flex">

        <!-- Contenedor centrado (versión angosta: solo hay un campo) -->
        <div class="form-centrado form-centrado-sm">

            <!-- Encabezado -->
            <a href="categoria.php" class="volver d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left"></i> Volver a categorías
            </a>
            <h1 class="titulo-pagina mb-1"><?= $titulo ?></h1>
            <p class="form-subtitulo mb-4">
                <?= $esEdicion ? 'Actualiza el nombre de esta categoría.' : 'Crea una categoría para agrupar los productos del menú.' ?>
            </p>

            <!-- Formulario (solo diseño: todavía no envía nada) -->
            <form id="formCategoria" class="form-card needs-validation" action="#" method="POST" novalidate>
                <input type="hidden" name="id" value="<?= $id ?>">

                <section class="form-seccion">
                    <h2 class="form-seccion-titulo">Datos de la categoría</h2>

                    <div>
                        <label for="categoria" class="form-etiqueta">Nombre de la categoría</label>
                        <input type="text" class="form-control" id="categoria" name="categoria"
                               value="<?= htmlspecialchars($categoria['categoria']) ?>"
                               placeholder="Ej. Bebidas calientes" maxlength="30"
                               autocomplete="off" required>
                        <div class="invalid-feedback">Escribe el nombre de la categoría.</div>
                        <div class="d-flex justify-content-between gap-3">
                            <div class="form-ayuda">Ej. entradas, bebidas, postres. No se puede repetir.</div>
                            <div class="form-ayuda text-nowrap"><span id="contador">0</span>/30</div>
                        </div>
                    </div>
                </section>

                <!-- Acciones -->
                <div class="form-acciones">
                    <a href="categorias.php" class="btn btn-light">Cancelar</a>
                    <button type="submit" class="btn btn-principal d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check-lg"></i> <?= $esEdicion ? 'Guardar cambios' : 'Crear categoría' ?>
                    </button>
                </div>
            </form>

        </div>
    </main>
</div>

<script src="../assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const form     = document.getElementById('formCategoria');
        const input    = document.getElementById('categoria');
        const contador = document.getElementById('contador');

        // Contador de caracteres (límite de la BD: VARCHAR(30))
        function actualizarContador() {
            contador.textContent = input.value.length;
        }
        input.addEventListener('input', actualizarContador);
        actualizarContador();

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Solo diseño: no envía nada todavía

            // Evita que pase un nombre de solo espacios
            input.value = input.value.trim();
            input.setCustomValidity(input.value === '' ? 'Vacío' : '');

            form.classList.add('was-validated');
        });
    })();
</script>
</body>
</html>