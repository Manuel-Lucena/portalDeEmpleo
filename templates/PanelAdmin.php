<?php $this->layout('layout', ['titulo' => 'Gestión de Empresas y Alumnos']) ?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="../Public/css/Paginas.css">
<link rel="stylesheet" href="../Public/css/Modal.css">
<?php $this->stop() ?>

<?php $this->start('contenido') ?>
<main>
    <!-- Sección Alumnos -->
    <section class="gestion-alumnos">
        <h1>Gestión de Alumnos</h1>
        <button id="btnAgregar">Añadir alumno</button>
        <button id="btnAgregarVarios">Añadir varios alumnos</button>
        <button> <a href="index.php?menu=GenerarPDFAlumnos" class="btn btn-primary">Generar PDF de Alumnos</a></button>
        <input type="text" id="buscadorAlumno" placeholder="Buscar alumno por nombre...">

        <table id="tablaAlumnos">
            <thead>
                <tr>
                    <th id="thNombre">Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </section>

    <div id="modalAlumno"></div>
    <div id="modalEditar"></div>
    <div id="modalEliminar"></div>
    <div id="modalDetalles"></div>
    <div id="modalCargaAlumnos"></div>
    <div id="modalCv"></div>

    <!-- Sección Empresas -->
    <section class="gestion-empresas">
        <h1>Empresas Aprobadas</h1>
        <div class="contenedor-acciones-empresa">

            <button><a href="index.php?menu=RegistroEmpresa">Añadir Empresa</a></button>

            <form method="POST" action="../Public/index.php?menu=PanelAdmin">
                <input type="text" name="busqueda" placeholder="Buscar empresa...">
                <button type="submit" class="btn btn-azul">Buscar</button>
            </form>

        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($empresas)): ?>
                    <tr>
                        <td colspan="3">No se ha encontrado ninguna empresa.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($empresas as $empresa): ?>
                        <tr>
                            <td><?= $empresa->getNombreEmpresa() ?></td>
                            <td><?= $empresa->getEmail() ?></td>
                            <td id='tdBotones'>
                                <form method="post" action="../public/index.php?menu=DetallesEmpresa">
                                    <input type="hidden" name="idEmpresa" value="<?= $empresa->getIdUser() ?>">
                                    <button type="submit" name="accion" value="detalles" class="btn-verde">Detalles</button>
                                </form>
                                <form method="post" action="../public/index.php?menu=EditarEmpresa">
                                    <input type="hidden" name="idEmpresa" value="<?= $empresa->getIdUser() ?>">
                                    <button type="submit" name="accion" value="editar" class="btn-amarillo">Editar</button>
                                </form>
                                <form method="post" action="../public/index.php?menu=EliminarEmpresa">
                                    <input type="hidden" name="idEmpresa" value="<?= $empresa->getIdUser() ?>">
                                    <button type="submit" name="accion" value="eliminar" class="btn-rojo">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if ($totalPages > 1): ?>
            <div class="paginacion">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="index.php?menu=PanelAdmin&page=<?= $i ?>&size=<?= $size ?>"
                        class="<?= $i == $page ? 'activo' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>




        <?php if ($empresasC) { ?>
            <h1>Empresas Pendientes de Aprobación</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empresasC as $empresaC): ?>
                        <tr>
                            <td><?= $empresaC->getNombreEmpresa() ?></td>
                            <td><?= $empresaC->getEmail() ?></td>
                            <td>
                                <form method="post" action="../public/index.php?menu=RegistroEmpresa">
                                    <input type="hidden" name="idEmpresa" value="<?= $empresaC->getIdUser() ?>">
                                    <button type="submit" name="accion" value="aprobar" class="btn-verde">Aprobar</button>
                                    <button type="submit" name="accion" value="rechazar" class="btn-rojo">Rechazar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } ?>
    </section>
</main>
<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="../Public/js/Modal.js"></script>
<script src="../Public/js/logicaAlumno.js"></script>
<script src="../Public/js/validator.js"></script>
<?php $this->stop() ?>