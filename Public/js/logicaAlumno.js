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
                            return;
                        }


                        modalAlumno.ocultar();
                        cargarAlumnos();
                    })
                    .catch(err => console.error("Error POST alumno:", err));

            };
        }

        if (btnCancelar) btnCancelar.onclick = () => {
            modalAlumno.ocultar();
            limpiarModalAlumno();
        }

    });


    // CARGA MASIVA DE ALUMNOS

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
                        ${cols.map((c, idx) => `<td><input type="text" class="inputCSV" data-campo="${cabecera[idx]}" value="${c}"></td>`).join("")}
                        <td class="error"></td>
                    </tr>
                `;
                        });

                        html += `</tbody></table>
                     <button id="btnCargarSeleccionados">Cargar seleccionados</button>`;
                        document.getElementById("previewCSV").innerHTML = html;



                        // --- Leer CSV y mostrar preview ---
                        const btnCargar = document.getElementById("btnCargarSeleccionados");
                        if (btnCargar) {
                            const btnCargar = document.getElementById("btnCargarSeleccionados");
                            if (btnCargar) {
                                btnCargar.onclick = () => {
                                    const filas = document.querySelectorAll(".tablaPreview tbody tr");
                                    const filasSeleccionadas = Array.from(filas).filter(tr => tr.querySelector(".checkCSV").checked);
                                    const listaSeleccionados = [];

                                    // Limpiar errores previos
                                    filasSeleccionadas.forEach(tr => {
                                        tr.querySelector(".error").textContent = "";
                                        tr.dataset.errores = "";
                                    });

                                    // Validación local mínima
                                    filasSeleccionadas.forEach((tr, i) => {
                                        const datos = {};
                                        tr.querySelectorAll(".inputCSV").forEach(input => {
                                            const campo = input.dataset.campo;
                                            if (campo) datos[campo] = input.value.trim();
                                        });

                                        let erroresFila = [];
                                        if (!datos.nombre || !datos.email) erroresFila.push("Faltan datos obligatorios");

                                        tr.dataset.errores = erroresFila.join(" | ");

                                        if (erroresFila.length === 0) {
                                            listaSeleccionados.push({
                                                filaIndex: i,
                                                nombre: datos.nombre,
                                                email: datos.email,
                                                password: datos.password || "123456",
                                                fechaNacimiento: datos.fechaNacimiento || null
                                            });
                                        }
                                    });

                                    // Si no hay alumnos válidos localmente, mostramos errores y paramos
                                    if (listaSeleccionados.length === 0) {
                                        filasSeleccionadas.forEach(tr => {
                                            const tdError = tr.querySelector(".error");
                                            tdError.textContent = tr.dataset.errores || "";
                                        });
                                        return;
                                    }

                                    // Enviar al servidor solo los alumnos válidos
                                    fetch("/api/ApiAlumno.php", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "Authorization": "Bearer " + localStorage.getItem("token")
                                        },
                                        body: JSON.stringify({
                                            cicloId: selectCiclo.value,
                                            alumnos: listaSeleccionados.map(a => [a.nombre, a.email, a.password, a.fechaNacimiento])
                                        })
                                    })
                                        .then(res => res.json())
                                        .then(data => {
                                            const totalSeleccionados = listaSeleccionados.length;
                                            const cargados = data.insertados || 0;


                                            if (data.errores && data.errores.length > 0) {
                                                data.errores.forEach(err => {
                                                    const fila = Array.from(filasSeleccionadas).find(tr =>
                                                        tr.querySelector(".inputCSV[data-campo='email']").value &&
                                                        err.includes(tr.querySelector(".inputCSV[data-campo='email']").value)
                                                    );
                                                    if (fila) {
                                                        const tdError = fila.querySelector(".error");
                                                        tdError.textContent = fila.dataset.errores
                                                            ? fila.dataset.errores + " | " + err
                                                            : err;
                                                    }
                                                });
                                            }


                                            filasSeleccionadas.forEach(tr => {
                                                const email = tr.querySelector(".inputCSV[data-campo='email']").value;
                                                const tieneError = Array.from(data.errores || []).some(err => err.includes(email));
                                                const tdError = tr.querySelector(".error");

                                                if (!tieneError && tr.dataset.errores === "") {

                                                    tr.querySelectorAll("input, button").forEach(el => el.disabled = true);
                                                    tdError.textContent = "Cargado correctamente";
                                                    tr.classList.add("fila-cargada");
                                                } else {

                                                    if (!tdError.textContent && tr.dataset.errores) tdError.textContent = tr.dataset.errores;
                                                }
                                            });


                                            alert(`Alumnos cargados correctamente: ${cargados} de ${totalSeleccionados}`);


                                            if (cargados === totalSeleccionados) {
                                                document.getElementById("previewCSV").innerHTML = "";
                                                cargarAlumnos();
                                            }

                                        })
                                        .catch(err => {
                                            console.error("Error carga masiva:", err);
                                            alert("Error en la conexión o en la carga de alumnos");
                                        });
                                };
                            }
                        }

                    };

                    reader.readAsText(archivo);
                };
            }

        });
    }


    // FUNCIONES AUXILIARES


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

                        if (!validarAlumnoEditar()) {
                            return;
                        }


                        const data = {
                            id: idAlumno,
                            nombre: document.getElementById("editarNombre").value,
                            email: document.getElementById("editarEmail").value,
                            fecha_nacimiento: document.getElementById("editarFechaNacimiento").value,
                            direccion: document.getElementById("editarDireccion").value,
                            telefono: document.getElementById("editarTelefono").value,
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


    // FUNCIONES DE CARGA

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
                        <td class="tdBotones">
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


    // VALIDACIÓN
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
        if (!Validador.vacio(nombre)) {
            spanNombre.textContent = "Nombre obligatorio";
            valido = false;
        }

        if (!Validador.vacio(email) || !Validador.email(email)) {
            spanEmail.textContent = "Email inválido";
            valido = false;
        }

        if (!Validador.vacio(password) || password.length < 6) {
            spanPassword.textContent = "Contraseña obligatoria (mín. 6 caracteres)";
            valido = false;
        }

        if (fechaNacimiento && !Validador.fecha(fechaNacimiento)) {
            spanFechaNacimiento.textContent = "Fecha inválida";
            valido = false;
        }

        if (telefono && !Validador.telefono(telefono)) {
            spanTelefono.textContent = "Teléfono inválido (9 dígitos)";
            valido = false;
        }

        if (direccion && direccion.length > 100) {
            spanDireccion.textContent = "Dirección demasiado larga";
            valido = false;
        }

        if (!Validador.vacio(familia)) {
            spanFamilia.textContent = "Selecciona una familia";
            valido = false;
        }

        if (!Validador.vacio(ciclo)) {
            spanCiclo.textContent = "Selecciona un ciclo";
            valido = false;
        }

        if (!Validador.fecha(fechaInicio)) {
            spanFechaInicio.textContent = "Fecha de inicio obligatoria";
            valido = false;
        }

        if (!Validador.fechaFinPosterior(fechaInicio, fechaFin)) {
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


function validarAlumnoEditar() {
    let valido = true;

    // --- Campos ---
    const nombre = document.getElementById("editarNombre").value.trim();
    const email = document.getElementById("editarEmail").value.trim();
    const password = document.getElementById("editarPassword").value;
    const repetirPassword = document.getElementById("editarRepetirPassword").value;
    const fechaNacimiento = document.getElementById("editarFechaNacimiento").value;
    const telefono = document.getElementById("editarTelefono").value.trim();
    const direccion = document.getElementById("editarDireccion").value.trim();
    const curriculum = document.getElementById("editarCurriculum").files[0];
    const fotoPerfil = document.getElementById("editarFotoPerfil").files[0];

    // --- Spans de error ---
    const spanNombre = document.getElementById("errorEditarNombre");
    const spanEmail = document.getElementById("errorEditarEmail");
    const spanPassword = document.getElementById("errorEditarPassword");
    const spanRepetir = document.getElementById("errorEditarRepetirPassword");
    const spanFechaNacimiento = document.getElementById("errorEditarFechaNacimiento");
    const spanTelefono = document.getElementById("errorEditarTelefono");
    const spanDireccion = document.getElementById("errorEditarDireccion");

    [spanNombre, spanEmail, spanPassword, spanRepetir, spanFechaNacimiento, spanTelefono, spanDireccion]
        .forEach(span => span.textContent = "");

    // --- Validaciones ---
    if (!Validador.vacio(nombre)) {
        spanNombre.textContent = "Nombre obligatorio";
        valido = false;
    }

    if (!Validador.vacio(email) || !Validador.email(email)) {
        spanEmail.textContent = "Email inválido";
        valido = false;
    }

    if (password && !Validador.maxLength(password, 50)) {
        spanPassword.textContent = "Contraseña demasiado larga (máx. 50)";
        valido = false;
    }

    if (password && repetirPassword && password !== repetirPassword) {
        spanRepetir.textContent = "Las contraseñas no coinciden";
        valido = false;
    }

    if (fechaNacimiento && !Validador.fecha(fechaNacimiento)) {
        spanFechaNacimiento.textContent = "Fecha inválida";
        valido = false;
    }

    if (telefono && !Validador.telefono(telefono)) {
        spanTelefono.textContent = "Teléfono inválido (9 dígitos)";
        valido = false;
    }

    if (direccion && !Validador.maxLength(direccion, 100)) {
        spanDireccion.textContent = "Dirección demasiado larga";
        valido = false;
    }

    if (curriculum && curriculum.type !== "application/pdf") {
        alert("El currículum debe ser un PDF");
        valido = false;
    }

    if (fotoPerfil && !["image/png"].includes(fotoPerfil.type)) {
        alert("La foto debe ser PNG");
        valido = false;
    }

    return valido;
}


function limpiarModalAlumno() {
    document.getElementById("nuevoNombre").value = "";
    document.getElementById("nuevoEmail").value = "";
    document.getElementById("nuevoPassword").value = "";
    document.getElementById("nuevaFechaNacimiento").value = "";
    document.getElementById("nuevoTelefono").value = "";
    document.getElementById("nuevaDireccion").value = "";
    document.getElementById("selectFamilia").value = "";
    document.getElementById("selectCiclo").innerHTML = '<option value="">Seleccione un ciclo</option>';
    document.getElementById("selectCiclo").disabled = true;
    document.getElementById("nuevoCurriculum").value = "";
    document.getElementById("nuevaFotoPerfil").value = "";
    document.getElementById("fechaInicioEstudio").value = "";
    document.getElementById("fechaFinEstudio").value = "";

    // Limpiar errores
    const spansErrores = [
        "errorNombre", "errorEmail", "errorPassword", "errorFechaNacimiento",
        "errorTelefono", "errorDireccion", "errorFamilia", "errorCiclo",
        "errorFechaInicio", "errorFechaFin"
    ];
    spansErrores.forEach(id => {
        const span = document.getElementById(id);
        if (span) span.textContent = "";
    });
}
