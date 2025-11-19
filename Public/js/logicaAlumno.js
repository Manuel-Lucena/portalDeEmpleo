window.addEventListener("load", function () {

    // --- CARGAR ALUMNOS AL INICIO ---
    cargarAlumnos();

    // --- BUSCADOR ---
    const inputBuscador = document.getElementById("buscadorAlumno");
    if (inputBuscador) {
        inputBuscador.addEventListener("keydown", function () {
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
            fetch(`/api/ApiAlumno.php?orden=nombre`, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
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
            fetch("/api/apiFamilia.php", {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
                .then(res => res.json())
                .then(familias => {
                      selectFamilia.innerHTML = '<option value="">Seleccione una Familia</option>';
                    familias.forEach(fam => {
                        
                        const option = document.createElement("option");
                        option.value = fam.id;
                        option.textContent = fam.nombre;
                        selectFamilia.appendChild(option);
                    });
                })
                .catch(err => console.error("Error cargando familias:", err));
        }

        // --- Ciclos dependientes ---
        if (selectFamilia && selectCiclo) {
            selectCiclo.disabled = true;
            selectFamilia.addEventListener("change", function () {
                const idFamilia = this.value;
                selectCiclo.innerHTML = '<option value="">Seleccione un ciclo</option>';

                if (!idFamilia) {
                    selectCiclo.disabled = true;
                    return;
                }

                fetch(`/api/apiCiclo.php?idFamilia=${idFamilia}`, {
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
                })
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


                if (!validarAlumno()) {
                    return;
                }
                const nombre = document.getElementById("nuevoNombre").value;
                const email = document.getElementById("nuevoEmail").value;
                const password = document.getElementById("nuevoPassword").value;
                const fechaNacimiento = document.getElementById("nuevaFechaNacimiento").value;
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
                fetch('/api/ApiAlumno.php', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
             
                        if (data.errores) {
                            console.error("Errores PHP:", data.errores);
                     
                            Object.entries(data.errores).forEach(([campo, msg]) => {
                                console.log(`${campo}: ${msg}`);
                            });
                            return; 
                        }

                        // Si todo OK
                        modalAlumno.ocultar();
                        cargarAlumnos();
                    })
                    .catch(err => console.error("Error POST alumno:", err));

            };
        }

        if (btnCancelar) btnCancelar.onclick = () => modalAlumno.ocultar();


    });

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
                fetch("/api/apiFamilia.php", {
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
                })
                    .then(res => res.json())
                    .then(familias => {
                        familias.forEach(fam => {
                            const option = document.createElement("option");
                            option.value = fam.id;
                            option.textContent = fam.nombre;
                            selectFamilia.appendChild(option);
                        });
                    });
            }

            // --- Ciclos dependientes ---
            if (selectFamilia && selectCiclo) {
                selectCiclo.disabled = true;
                selectFamilia.addEventListener("change", function () {
                    const idFamilia = this.value;
                    selectCiclo.innerHTML = '<option value="">Seleccione un ciclo</option>';
                    if (!idFamilia) {
                        selectCiclo.disabled = true;
                        return;
                    }

                    fetch(`/api/apiCiclo.php?idFamilia=${idFamilia}`, {
                        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
                    })
                        .then(res => res.json())
                        .then(ciclos => {
                            ciclos.forEach(c => {
                                const option = document.createElement("option");
                                option.value = c.id;
                                option.textContent = `${c.nombre} (${c.tipo})`;
                                selectCiclo.appendChild(option);
                            });
                            selectCiclo.disabled = false;
                        });
                });
            }

            // --- Leer CSV y mostrar preview ---
            const btnLeerCSV = document.getElementById("btnLeerCSV");
            if (btnLeerCSV) {
                btnLeerCSV.onclick = () => {
                    const cicloId = selectCiclo.value;
                    const archivo = document.getElementById("archivoCSV").files[0];

                    if (!cicloId) { alert("Debe seleccionar un ciclo"); return; }
                    if (!archivo) { alert("Debe seleccionar un archivo CSV"); return; }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const lineas = e.target.result.split("\n").map(l => l.trim()).filter(l => l.length);
                        if (lineas.length <= 1) { alert("CSV vacío"); return; }

                        const cabecera = lineas[0].split(",").map(c => c.trim());
                        const filas = lineas.slice(1).map(l => l.split(",").map(c => c.trim())).filter(cols => cols.length === cabecera.length);

                        let html = `
                        <table class="tablaPreview">
                            <thead>
                                <tr>
                                    <th>✔️</th>${cabecera.map(h => `<th>${h}</th>`).join("")}<th>Errores</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                        filas.forEach((cols, i) => {
                            html += `
                            <tr data-index="${i}">
                                <td><input type="checkbox" class="checkCSV"></td>
                                ${cols.map(c => `<td><input type="text" class="inputCSV" value="${c}"></td>`).join("")}
                                <td class="error"></td>
                            </tr>
                        `;
                        });

                        html += `</tbody></table>
                        <button id="btnCargarSeleccionados">Cargar seleccionados</button>`;

                        document.getElementById("previewCSV").innerHTML = html;

                        document.getElementById("btnCargarSeleccionados").onclick = () => {
                            const filasTabla = document.querySelectorAll(".tablaPreview tbody tr");
                            const seleccionados = [];

                            filasTabla.forEach(tr => {
                                const checkbox = tr.querySelector(".checkCSV");
                                const inputs = Array.from(tr.querySelectorAll(".inputCSV"));
                                const errorTd = tr.querySelector(".error");

                                if (inputs.some(i => !i.value.trim())) {
                                    errorTd.textContent = "Faltan datos";
                                    checkbox.checked = false;
                                } else {
                                    errorTd.textContent = "";
                                    if (checkbox.checked) {
                                        seleccionados.push(inputs.map(i => i.value.trim()));
                                    }
                                }
                            });

                            if (seleccionados.length === 0) { alert("No hay filas seleccionadas"); return; }

                            fetch('/api/apiAlumno.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                                },
                                body: JSON.stringify({ cicloId: selectCiclo.value, alumnos: seleccionados })
                            })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        modalCarga.ocultar();
                                        document.getElementById("previewCSV").innerHTML = "";
                                        cargarAlumnos();
                                    } else if (data.error) {
                                        alert("Error: " + data.error);
                                    }
                                });
                        };
                    };

                    reader.readAsText(archivo);
                };
            }
        });
    }

    // =========================
    // FUNCIONES AUXILIARES
    // =========================

    function asignarEventosFila(fila) {
        const btnDetalles = fila.querySelector(".btn-verde");
        const btnEditar = fila.querySelector(".btn-amarillo");
        const btnEliminar = fila.querySelector(".btn-rojo");


        // --- EDITAR ---
        btnEditar.onclick = async () => {
            const idAlumno = fila.querySelector(".idAlumno").value;

            try {
                const resAlumno = await fetch(`/api/ApiAlumno.php?id=${idAlumno}`, {
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
                });
                const alumno = await resAlumno.json();

                if (!alumno || alumno.error) {
                    alert("No se pudo cargar la información del alumno");
                    return;
                }

                const modalEditar = Modal.crear("modalEditar", "html/editarAlumno.html", function () {
                    modalEditar.mostrar();

                    document.getElementById("editarNombre").value = alumno.nombre || "";
                    document.getElementById("editarEmail").value = alumno.email || "";
                    document.getElementById("editarTelefono").value = alumno.telefono || "";
                    document.getElementById("editarDireccion").value = alumno.direccion || "";
                    document.getElementById("editarFechaNacimiento").value = alumno.fecha_nacimiento || "";

                    const btnGuardarEditar = document.getElementById("btnGuardarEditar");
                    btnGuardarEditar.onclick = async () => {
                        const data = {
                            id: idAlumno,
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

                        const res = await fetch('/api/ApiAlumno.php', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('token')
                            },
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

            } catch (e) {
                console.error(e);
                alert("Error al cargar los datos del alumno");
            }
        };

        // --- DETALLES ---
        btnDetalles.onclick = () => {
            const idAlumno = fila.querySelector(".idAlumno").value;

            fetch(`/api/ApiAlumno.php?id=${idAlumno}`, {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
                .then(res => res.json())
                .then(async alumno => {
                    const modalDetalles = Modal.crear("modalDetalles", "html/detallesAlumno.html", function () {
                        modalDetalles.mostrar();

                        document.getElementById("detalleNombre").textContent = "Nombre: " + alumno.nombre;
                        document.getElementById("detalleEmail").textContent = "Email: " + alumno.email;
                        document.getElementById("detalleFechaNacimiento").textContent = "Fecha nacimiento: " + (alumno.fecha_nacimiento ?? '-');
                        document.getElementById("detalleDireccion").textContent = "Dirección: " + (alumno.direccion ?? '-');
                        document.getElementById("detalleTelefono").textContent = "Teléfono: " + (alumno.telefono ?? '-');

                        const fotoPerfil = document.getElementById("detalleFoto");
                        fotoPerfil.src = alumno.foto && alumno.foto !== '' ? '../fotos/alumno/' + alumno.foto : '../fotos/alumno/predeterminada.png';

                        const btnCerrar = document.getElementById("btnCancelarDetalles");
                        if (btnCerrar) btnCerrar.onclick = () => modalDetalles.ocultar();

                        // --- BOTÓN CV ---
                        document.getElementById("btnAbrirCv").onclick = () => {
                            const modalCurriculum = Modal.crear("modalCv", "html/cv.html", function () {

                                modalCurriculum.mostrar();

                                const iframe = document.getElementById("iframeCurriculum");

                                if (!iframe) {
                                    console.error("No se encontró el iframe para mostrar el CV");
                                    return;
                                }

                                if (!alumno.curriculum) {
                                    iframe.srcdoc = "<p>No hay CV disponible</p>";
                                } else {

                                    iframe.src = "data:application/pdf;base64," + alumno.curriculum;
                                }

                                const btnCerrarCv = document.getElementById("btnCerrarCv");
                                if (btnCerrarCv) btnCerrarCv.onclick = () => modalCurriculum.ocultar();
                            });
                        };
                    });
                })
                .catch(err => console.error("Error cargando detalles del alumno:", err));
        };

        // --- ELIMINAR ---
        btnEliminar.onclick = () => {
            const modalEliminar = Modal.crear("modalEliminar", "html/eliminarAlumno.html", function () {
                modalEliminar.mostrar();

                const btnConfirmar = document.getElementById("btnConfirmarEliminar");
                btnConfirmar.onclick = () => {
                    fetch('/api/ApiAlumno.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
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
    }

    // =========================
    // FUNCIONES DE CARGA
    // =========================
    function cargarAlumnos() {
        fetch('/api/ApiAlumno.php', {
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
        })
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
                            <button class="btn-verde">Detalles</button>
                            <button class="btn-amarillo">Editar</button>
                            <button class="btn-rojo">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                    asignarEventosFila(fila);
                });
            })
            .catch(err => console.error("Error cargando alumnos:", err));
    }

    function buscarAlumnos(nombre) {
        fetch(`/api/ApiAlumno.php?nombre=${nombre}`, {
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
        })
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
                            <button class="btn-verde">Detalles</button>
                            <button class="btn-amarillo">Editar</button>
                            <button class="btn-rojo">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                    asignarEventosFila(fila);
                });
            })
            .catch(err => console.error("Error buscando alumnos:", err));
    }

    // ------------------------
    // VALIDACIÓN
    // ------------------------
    function validarAlumno() {
        let valido = true;

        // --- Campos ---
        const nombre = document.getElementById("nuevoNombre").value;
        const email = document.getElementById("nuevoEmail").value;
        const password = document.getElementById("nuevoPassword").value;
        const fechaNacimiento = document.getElementById("nuevaFechaNacimiento").value;

        const telefono = document.getElementById("nuevoTelefono").value;
        const direccion = document.getElementById("nuevaDireccion").value;
        const familia = document.getElementById("selectFamilia").value;
        const ciclo = document.getElementById("selectCiclo").value;
        const fechaInicio = document.getElementById("fechaInicioEstudio").value;
        const fechaFin = document.getElementById("fechaFinEstudio").value;
        const curriculum = document.getElementById("nuevoCurriculum").files[0];
        const fotoPerfil = document.getElementById("nuevaFotoPerfil").files[0];

        // --- Spans de error ---
        const spanNombre = document.getElementById("errorNombre");
        const spanEmail = document.getElementById("errorEmail");
        const spanPassword = document.getElementById("errorPassword");
        const spanFechaNacimiento = document.getElementById("errorFechaNacimiento");
        const spanTelefono = document.getElementById("errorTelefono");
        const spanDireccion = document.getElementById("errorDireccion");
        const spanFamilia = document.getElementById("errorFamilia");
        const spanCiclo = document.getElementById("errorCiclo");
        const spanFechaInicio = document.getElementById("errorFechaInicio");
        const spanFechaFin = document.getElementById("errorFechaFin");


        [spanNombre, spanEmail, spanPassword, spanFechaNacimiento, spanTelefono,
            spanDireccion, spanFamilia, spanCiclo, spanFechaInicio, spanFechaFin]
            .forEach(span => span.textContent = "");

        // --- Validaciones ---
        if (!Validator.vacio(nombre)) {
            spanNombre.textContent = "Nombre obligatorio";
            valido = false;
        }

        if (!Validator.vacio(email) || !Validator.email(email)) {
            spanEmail.textContent = "Email inválido";
            valido = false;
        }

        if (!Validator.vacio(password) || password.length < 6) {
            spanPassword.textContent = "Contraseña obligatoria (mín. 6 caracteres)";
            valido = false;
        }

        if (fechaNacimiento && !Validator.fecha(fechaNacimiento)) {
            spanFechaNacimiento.textContent = "Fecha inválida";
            valido = false;
        }

        if (telefono && !Validator.telefono(telefono)) {
            spanTelefono.textContent = "Teléfono inválido (9 dígitos)";
            valido = false;
        }

        if (direccion && direccion.length > 100) {
            spanDireccion.textContent = "Dirección demasiado larga";
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
            spanFechaInicio.textContent = "Fecha de inicio obligatoria";
            valido = false;
        }

        if (!Validator.fechaFinPosterior(fechaInicio, fechaFin)) {
            spanFechaFin.textContent = "Fecha fin debe ser posterior a la de inicio";
            valido = false;
        }

        if (curriculum && curriculum.type !== "application/pdf") {
            alert("El curriculum debe ser un PDF");
            valido = false;
        }

        if (fotoPerfil && !["image/png"].includes(fotoPerfil.type)) {
            alert("La foto debe ser PNG");
            valido = false;
        }

        return valido;
    }


});
