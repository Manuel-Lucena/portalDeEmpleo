window.addEventListener("load", function () {


    cargarAlumnos();
    const inputBuscador = document.getElementById("buscadorAlumno");
    if (inputBuscador) {
        inputBuscador.addEventListener("input", function () {
            const texto = this.value.trim();

            if (texto === "") {
                cargarAlumnos();
            } else {
                buscarAlumnos(texto);
            }
        });
    }

    // --- ORDENAR POR NOMBRE ---
    const thNombre = document.getElementById("thNombre");
    if (thNombre) {
        thNombre.addEventListener("click", function () {
            fetch(`/portalDeEmpleo/api/ApiAlumno.php?orden=nombre`)
                .then(res => res.json())
                .then(alumnos => {
                    const tbody = document.querySelector("#tablaAlumnos tbody");
                    tbody.innerHTML = "";
                    alumnos.forEach(alumno => {
                        const fila = document.createElement("tr");
                        fila.innerHTML = `
                            <td>${alumno.nombre}</td>
                            <td>${alumno.email}</td>
                            <td>
                                <input type="hidden" class="idAlumno" value="${alumno.id}">
                                <button class="detalles btnVerde">Detalles</button>
                                <button class="editar btnAmarillo">Editar</button>
                                <button class="eliminar btnRojo">Eliminar</button>
                            </td>
                        `;
                        tbody.appendChild(fila);
                        asignarEventosFila(fila);
                    });
                })
                .catch(err => console.error("Error ordenando alumnos:", err));
        });
    }



    // --- MODAL: Añadir alumno ---
    const modalAlumno = Modal.crear("modalAlumno", "html/nuevoAlumno.html", function () {


        const botonAgregar = document.getElementById("btnAgregar");
        if (botonAgregar) botonAgregar.onclick = () => modalAlumno.mostrar();


        const btnGuardar = document.getElementById("btnGuardarNuevo");
        const btnCancelar = document.getElementById("btnCancelarNuevo");


        const selectFamilia = document.getElementById("selectFamilia");
        const selectCiclo = document.getElementById("selectCiclo");

        // --- Cargar familias ---
        if (selectFamilia) {
            fetch("/portalDeEmpleo/api/apiFamilia.php")
                .then(res => res.json())
                .then(familias => {
                    familias.forEach(fam => {
                        const option = document.createElement("option");
                        option.value = fam.id;
                        option.textContent = fam.nombre;
                        selectFamilia.appendChild(option);
                    });
                })
                .catch(err => console.error("Error cargando familias:", err));
        }


        if (selectFamilia && selectCiclo) {
            selectCiclo.disabled = true;
            selectFamilia.addEventListener("change", function () {
                const idFamilia = this.value;
                selectCiclo.innerHTML = '<option value="">Seleccione un ciclo</option>';

                if (!idFamilia) {
                    selectCiclo.disabled = true;
                    return;
                }

                fetch(`/portalDeEmpleo/api/apiCiclo.php?idFamilia=${idFamilia}`)
                    .then(res => res.json())
                    .then(ciclos => {
                        ciclos.forEach(c => {
                            const option = document.createElement("option");
                            option.value = c.id;
                            option.textContent = `${c.nombre} (${c.tipo})`;
                            selectCiclo.appendChild(option);
                        });
                        selectCiclo.disabled = false;
                    })
                    .catch(err => console.error("Error cargando ciclos:", err));
            });
        }

        // --- Guardar alumno ---
        if (btnGuardar) {
            btnGuardar.onclick = () => {

                // if (validarAlumno()) {

                const nombre = document.getElementById("nuevoNombre").value;
                const email = document.getElementById("nuevoEmail").value;
                const password = document.getElementById("nuevoPassword").value;
                const fechaNacimiento = document.getElementById("nuevaFecha").value;
                const telefono = document.getElementById("nuevoTelefono").value;
                const direccion = document.getElementById("nuevaDireccion").value;
                const familia = document.getElementById("selectFamilia").value;
                const ciclo = document.getElementById("selectCiclo").value;
                const curriculum = document.getElementById("nuevoCurriculum").files[0];
                const fotoPerfil = document.getElementById("nuevaFotoPerfil").files[0];
                const fechaInicio = document.getElementById("fechaInicioEstudio").value;
                const fechaFin = document.getElementById("fechaFinEstudio").value;




                const formData = new FormData();
                formData.append("nombre", nombre);
                formData.append("email", email);
                formData.append("password", password);
                formData.append("fecha_nacimiento", fechaNacimiento);
                formData.append("direccion", direccion);
                formData.append("telefono", telefono);
                if (curriculum) formData.append("curriculum", curriculum);
                if (fotoPerfil) formData.append("fotoPerfil", fotoPerfil);
                if (familia) formData.append("familia", familia);
                if (ciclo) formData.append("idCiclo", ciclo);
                formData.append("fechaInicio", fechaInicio);
                formData.append("fechaFin", fechaFin);


                fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(() => {
                        modalAlumno.ocultar();
                        cargarAlumnos();
                    })
                    .catch(err => console.error("Error POST alumno:", err));
            };
        }
        //    }
        if (btnCancelar) btnCancelar.onclick = () => modalAlumno.ocultar();
    });


    // --- FUNCIONES AUXILIARES ---
    function asignarEventosFila(fila) {
        const btnEditar = fila.querySelector(".editar");
        const btnEliminar = fila.querySelector(".eliminar");
        const btnDetalles = fila.querySelector(".detalles");

        btnEditar.onclick = () => {
            const modalEditar = Modal.crear("modalEditar", "html/editarAlumno.html", function () {
                modalEditar.mostrar();

                // Rellenar campos con los datos del alumno
                document.getElementById("editarNombre").value = fila.querySelector("td:nth-child(1)").textContent;
                document.getElementById("editarEmail").value = fila.querySelector("td:nth-child(2)").textContent;

                const btnGuardarEditar = document.getElementById("btnGuardarEditar");
                btnGuardarEditar.onclick = async () => {
                    const data = {
                        id: fila.querySelector(".idAlumno").value,
                        nombre: document.getElementById("editarNombre").value,
                        email: document.getElementById("editarEmail").value,
                        fecha_nacimiento: document.getElementById("editarFechaNacimiento").value,
                        direccion: document.getElementById("editarDireccion").value,
                        telefono: document.getElementById("editarTelefono").value
                    };
                    const cvInput = document.getElementById("editarCurriculum");
                    if (cvInput && cvInput.files[0]) {
                        data.curriculum = await fileToBase64(cvInput.files[0]);
                    }

                    const fotoInput = document.getElementById("editarFotoPerfil");
                    if (fotoInput && fotoInput.files[0]) {
                        data.fotoPerfil = await fileToBase64(fotoInput.files[0]);
                    }


                    
                    const res = await fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });

                    const resultado = await res.json();
                    if (resultado.success) {
                        modalEditar.ocultar();
                        cargarAlumnos();
                    } else {
                        alert("Error: " + (resultado.error || "Error desconocido"));
                    }
                };

                function fileToBase64(file) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = () => resolve(reader.result.split(",")[1]);
                        reader.onerror = reject;
                        reader.readAsDataURL(file);
                    });
                }
            });
        };


        // Eliminar
        btnEliminar.onclick = () => {
            const modalEliminar = Modal.crear("modalEliminar", "html/eliminarAlumno.html", function () {
                modalEliminar.mostrar();


                const btnConfirmar = document.getElementById("btnConfirmarEliminar");
                btnConfirmar.onclick = () => {
                    fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: fila.querySelector(".idAlumno").value })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.mensaje) {
                                alert(data.mensaje);
                                modalEliminar.ocultar();
                                fila.remove();
                            } else if (data.error) {
                                alert("Error: " + data.error);
                            }
                        })
                        .catch(err => console.error("Error DELETE alumno:", err));
                };
            });
        };



        btnDetalles.onclick = () => {
            const idAlumno = fila.querySelector(".idAlumno").value;

            fetch(`/portalDeEmpleo/api/ApiAlumno.php?id=${idAlumno}`)
                .then(res => res.json())
                .then(alumno => {
                    const modalDetalles = Modal.crear("modalDetalles", "html/detallesAlumno.html", function () {
                        modalDetalles.mostrar();

                        document.getElementById("detalleNombre").textContent = "Nombre: " + alumno.nombre;
                        document.getElementById("detalleEmail").textContent = "Email: " + alumno.email;
                        document.getElementById("detalleFechaNacimiento").textContent = "Fecha nacimiento: " + (alumno.fecha_nacimiento ?? '-');
                        document.getElementById("detalleDireccion").textContent = "Dirección: " + (alumno.direccion ?? '-');
                        document.getElementById("detalleTelefono").textContent = "Teléfono: " + (alumno.telefono ?? '-');

                        let fotoPerfil = document.getElementById("detalleFoto");
                        let rutaFoto = '';

                        if (alumno.foto && alumno.foto !== '') {
                            rutaFoto = '../fotos/alumno/' + alumno.foto;
                        } else {
                            rutaFoto = '../fotos/alumno/predeterminada.png';
                        }

                        fotoPerfil.src = rutaFoto;

                        const btnCerrar = document.getElementById("btnCancelarDetalles");
                        if (btnCerrar) btnCerrar.onclick = () => modalDetalles.ocultar();
                    });

                })
                .catch(err => console.error("Error cargando detalles del alumno:", err));
        };
    }

    function buscarAlumnos(nombre) {

        fetch(`/portalDeEmpleo/api/ApiAlumno.php?nombre=${nombre}`)
            .then(res => res.json())
            .then(alumnos => {
                const tbody = document.querySelector("#tablaAlumnos tbody");
                tbody.innerHTML = "";

                if (!alumnos || alumnos.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="3">No se encontraron alumnos</td></tr>`;
                    return;
                }

                alumnos.forEach(alumno => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                    <td>${alumno.nombre}</td>
                    <td>${alumno.email}</td>
                    <td>
                        <input type="hidden" class="idAlumno" value="${alumno.id}">
                        <button class="detalles btnVerde">Detalles</button>
                        <button class="editar btnAmarillo">Editar</button>
                        <button class="eliminar btnRojo">Eliminar</button>
                    </td>
                `;
                    tbody.appendChild(fila);
                    asignarEventosFila(fila);
                });
            })
            .catch(err => console.error("Error buscando alumnos:", err));
    }


    function cargarAlumnos() {

        fetch('/portalDeEmpleo/api/ApiAlumno.php')
            .then(res => res.json())
            .then(alumnos => {
                const tbody = document.querySelector("#tablaAlumnos tbody");
                tbody.innerHTML = "";
                alumnos.forEach(alumno => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${alumno.nombre}</td>
                        <td>${alumno.email}</td>
                        <td>
                            <input type="hidden" class="idAlumno" value="${alumno.id}">
                            <button class="detalles btnVerde">Detalles</button>
                            <button class="editar btnAmarillo">Editar</button>
                            <button class="eliminar btnRojo">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                    asignarEventosFila(fila);
                });
            })
            .catch(err => console.error("Error cargando alumnos:", err));
    }

















    // =======================
    // CARGA MASIVA DE ALUMNOS
    // =======================
    const btnAgregarVarios = document.getElementById("btnAgregarVarios");

    if (btnAgregarVarios) {
        const modalCarga = Modal.crear("modalCargaAlumnos", "html/cargaAlumnos.html", function () {

            // --- Mostrar modal ---
            btnAgregarVarios.onclick = () => modalCarga.mostrar();

            // --- Botón cancelar ---
            const btnCancelar = document.getElementById("btnCancelarCarga");
            if (btnCancelar) btnCancelar.onclick = () => modalCarga.ocultar();

            const selectFamilia = document.getElementById("selectFamiliaCarga");
            const selectCiclo = document.getElementById("selectCicloCarga");

            // --- Cargar familias ---
            if (selectFamilia) {
                fetch("/portalDeEmpleo/api/apiFamilia.php")
                    .then(res => res.json())
                    .then(familias => {
                        familias.forEach(fam => {
                            const option = document.createElement("option");
                            option.value = fam.id;
                            option.textContent = fam.nombre;
                            selectFamilia.appendChild(option);
                        });
                    })
                    .catch(err => console.error("Error cargando familias:", err));
            }

            // --- Cargar ciclos dependientes ---
            if (selectFamilia && selectCiclo) {
                selectCiclo.disabled = true;

                selectFamilia.addEventListener("change", function () {
                    const idFamilia = this.value;
                    selectCiclo.innerHTML = '<option value="">Seleccione un ciclo</option>';

                    if (!idFamilia) {
                        selectCiclo.disabled = true;
                        return;
                    }

                    fetch(`/portalDeEmpleo/api/apiCiclo.php?idFamilia=${idFamilia}`)
                        .then(res => res.json())
                        .then(ciclos => {
                            ciclos.forEach(c => {
                                const option = document.createElement("option");
                                option.value = c.id;
                                option.textContent = `${c.nombre} (${c.tipo})`;
                                selectCiclo.appendChild(option);
                            });
                            selectCiclo.disabled = false;
                        })
                        .catch(err => console.error("Error cargando ciclos:", err));
                });
            }

            // ====================
            // Leer CSV y mostrar preview
            // ====================
            const btnLeerCSV = document.getElementById("btnLeerCSV");
            if (btnLeerCSV) {
                btnLeerCSV.onclick = () => {
                    const cicloId = selectCiclo.value;
                    const archivo = document.getElementById("archivoCSV").files[0];

                    if (!cicloId) {
                        alert("Debe seleccionar un ciclo antes de continuar.");
                        return;
                    }
                    if (!archivo) {
                        alert("Debe seleccionar un archivo CSV.");
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {

                        const lineas = e.target.result
                            .split("\n")
                            .map(l => l.trim())
                            .filter(l => l.length);

                        if (lineas.length <= 1) {
                            alert("CSV vacío o sin contenido válido.");
                            return;
                        }

                        // --- Cabecera ---
                        const cabecera = lineas[0].split(",").map(c => c.trim());

                        // --- Filas ---
                        const filas = lineas.slice(1)
                            .map(l => l.split(",").map(c => c.trim()))
                            .filter(cols => cols.length === cabecera.length);

                        // --- Generar tabla preview ---
                        let html = `
                        <div style="max-height:400px; overflow-y:auto;">
                        <table class="tablaPreview">
                          <thead>
                            <tr>
                              <th>✔️</th>
                              ${cabecera.map(h => `<th>${h}</th>`).join("")}
                              <th>Errores</th>
                            </tr>
                          </thead>
                          <tbody>
                    `;

                        filas.forEach((cols, index) => {
                            html += `
                            <tr data-index="${index}">
                                <td><input type="checkbox" class="checkCSV"></td>
                                ${cols.map(c => `<td><input type="text" class="inputCSV" value="${c}"></td>`).join("")}
                                <td class="error"></td>
                            </tr>
                        `;
                        });

                        html += `
                          </tbody>
                        </table>
                        </div>
                        <div class="botones">
                            <button id="btnCargarSeleccionados" class="btnVerde">Cargar seleccionados</button>
                        </div>
                    `;

                        document.getElementById("previewCSV").innerHTML = html;

                        // --- Botón cargar seleccionados ---
                        const btnCargar = document.getElementById("btnCargarSeleccionados");
                        btnCargar.onclick = () => {
                            const filasTabla = document.querySelectorAll(".tablaPreview tbody tr");
                            const seleccionados = [];

                            filasTabla.forEach(tr => {
                                const checkbox = tr.querySelector(".checkCSV");
                                const inputs = Array.from(tr.querySelectorAll(".inputCSV"));
                                const errorTd = tr.querySelector(".error");

                                const faltan = inputs.some(i => !i.value.trim());

                                if (faltan) {
                                    errorTd.innerHTML = "<span class='error'>Faltan datos obligatorios</span>";
                                    checkbox.checked = false;
                                } else {
                                    errorTd.innerHTML = "";
                                    if (checkbox.checked) {
                                        const filaData = inputs.map(i => i.value.trim());
                                        seleccionados.push(filaData);
                                    }
                                }
                            });

                            if (seleccionados.length === 0) {
                                alert("No hay filas seleccionadas para cargar.");
                                return;
                            }

                            fetch('/portalDeEmpleo/api/apiAlumno.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    cicloId: selectCiclo.value,
                                    alumnos: seleccionados
                                })
                            })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        modalCarga.ocultar();
                                        document.getElementById("previewCSV").innerHTML = "";
                                        cargarAlumnos();
                                    }
                                })
                                .catch(err => console.error("Error al enviar alumnos:", err));
                        };

                    };

                    reader.readAsText(archivo);
                };
            }

        });
    }






});






































function validarAlumno() {
    let valido = true;

    // --- Inputs ---
    const nombre = document.getElementById("nuevoNombre").value;
    const email = document.getElementById("nuevoEmail").value;
    const password = document.getElementById("nuevoPassword").value;
    const fechaNacimiento = document.getElementById("nuevaFecha").value;
    const telefono = document.getElementById("nuevoTelefono").value;
    const direccion = document.getElementById("nuevaDireccion").value;
    const familia = document.getElementById("selectFamilia").value;
    const ciclo = document.getElementById("selectCiclo").value;
    const fechaInicio = document.getElementById("fechaInicioEstudio").value;
    const fechaFin = document.getElementById("fechaFinEstudio").value;

    // --- Spans ---
    const spanNombre = document.getElementById("errorNombre");
    const spanEmail = document.getElementById("errorEmail");
    const spanPassword = document.getElementById("errorPassword");
    const spanFecha = document.getElementById("errorFecha");
    const spanTelefono = document.getElementById("errorTelefono");
    const spanDireccion = document.getElementById("errorDireccion");
    const spanFamilia = document.getElementById("errorFamilia");
    const spanCiclo = document.getElementById("errorCiclo");
    const spanFechaInicio = document.getElementById("errorFechaInicio");
    const spanFechaFin = document.getElementById("errorFechaFin");

    // --- Limpiar errores ---
    spanNombre.textContent = "";
    spanEmail.textContent = "";
    spanPassword.textContent = "";
    spanFecha.textContent = "";
    spanTelefono.textContent = "";
    spanDireccion.textContent = "";
    spanFamilia.textContent = "";
    spanCiclo.textContent = "";
    spanFechaInicio.textContent = "";
    spanFechaFin.textContent = "";

    // --- Validación ---
    if (!Validator.vacio(nombre)) {
        spanNombre.textContent = "Nombre obligatorio";
        valido = false;
    }

    if (!Validator.vacio(email) || !Validator.email(email)) {
        spanEmail.textContent = "Email inválido";
        valido = false;
    }

    if (!Validator.vacio(password)) {
        spanPassword.textContent = "Contraseña obligatoria";
        valido = false;
    }

    if (!Validator.vacio(familia)) {
        spanFamilia.textContent = "Selecciona una familia";
        valido = false;
    }

    if (!Validator.vacio(ciclo)) {
        spanCiclo.textContent = "Selecciona un ciclo";
        valido = false;
    }

    if (!Validator.fecha(fechaInicio)) {
        spanFechaInicio.textContent = "Fecha inicio obligatoria";
        valido = false;
    }

    if (!Validator.fechaFinPosterior(fechaInicio, fechaFin)) {
        spanFechaFin.textContent = "Fecha fin debe ser posterior a la inicio";
        valido = false;
    }

    return valido;
};


