const modalAlumno = Modal.crear("modalAlumno", "html/registroAlumno.html", function () {
    // Aquí el HTML ya está en el DOM, no hace falta setTimeout
const selectFamilia = document.getElementById("selectFamilia"); // el de login
const selectCiclo = document.getElementById("selectCicloRegistro"); // nuevo ID
    const btnGuardar = document.getElementById("btnGuardarNuevo");
    const btnCancelar = document.getElementById("btnCancelarNuevo");
    const botonAgregar = document.getElementById("btnAbrirRegistro");
    const btnAbrirCamara = document.getElementById("btnAbrirCamara");

    // Mostrar el modal al hacer clic en "Registrarse como Alumno"
    if (botonAgregar) botonAgregar.onclick = () => modalAlumno.mostrar();

    // Cerrar modal
    if (btnCancelar) btnCancelar.onclick = () => modalAlumno.ocultar();

    // --- Cargar familias ---
    if (selectFamilia && selectCiclo) {
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

        // Cuando cambia la familia, cargar los ciclos
        selectFamilia.addEventListener("change", () => {
            const idFamilia = selectFamilia.value;

            selectCiclo.disabled = true;

            if (!idFamilia) {
                selectCiclo.innerHTML = "<option value=''>Seleccione primero una familia</option>";
                return;
            }

            fetch(`/portalDeEmpleo/api/apiCiclo.php?idFamilia=${idFamilia}`)
                .then(res => res.json())
                .then(ciclos => {
                    selectCiclo.innerHTML = "<option value=''>Seleccione un ciclo</option>";
                    ciclos.forEach(c => {
                        const option = document.createElement("option");
                        option.value = c.id;
                        option.textContent = c.nombre;
                        selectCiclo.appendChild(option);
                    });
                    selectCiclo.disabled = false;
                })
                .catch(err => console.error("Error cargando ciclos:", err));
        });
    }

    // Guardar alumno
    if (btnGuardar) {
        btnGuardar.onclick = () => {
            const nombre = document.getElementById("nuevoNombre").value;
            const email = document.getElementById("nuevoEmail").value;
            const password = document.getElementById("nuevoContraseña").value;
            const curriculum = document.getElementById("nuevoCurriculum").files[0] || null;
            const fotoPerfil = document.getElementById("nuevoFotoPerfil").value || null;
            const idFamilia = document.getElementById("selectFamilia").value;
            const idCiclo = document.getElementById("selectCicloRegistro").value;
            const fechaInicio = document.getElementById("fechaInicioEstudio").value;
            const fechaFin = document.getElementById("fechaFinEstudio").value;

            if (!nombre || !email || !password) {
                alert("Completa todos los campos obligatorios");
                return;
            }
            
            const formData = new FormData();
            formData.append("nombre", nombre);
            formData.append("email", email);
            formData.append("password", password);
            formData.append("idCiclo", idCiclo);
            formData.append("fechaInicio", fechaInicio);
            formData.append("fechaFin", fechaFin);
            if (curriculum) formData.append("curriculum", curriculum);
            if (fotoPerfil) formData.append("fotoPerfil", fotoPerfil);

            fetch('/portalDeEmpleo/api/ApiAlumno.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    console.log("Alumno creado:", data);
                    modalAlumno.ocultar();
                })
                .catch(err => console.error("Error POST alumno:", err));
        };
    }

    // Modal cámara
    const modalCamara = Modal.crear("modalCamara", "html/camara.html", function () {
        const video = document.getElementById("video");
        const canvas = document.getElementById("canvas");
        const btnTomar = document.getElementById("btnTomarFoto");
        const btnUsar = document.getElementById("btnUsarFoto");
        const btnCerrar = document.getElementById("btnCerrarCamara");

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => video.srcObject = stream)
            .catch(err => console.error("No se puede acceder a la cámara:", err));

        btnTomar.onclick = () => {
            canvas.getContext("2d").drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.style.display = "block";
        };

        btnUsar.onclick = () => {
            const dataUrl = canvas.toDataURL("image/png");
            const inputFoto = document.getElementById("nuevoFotoPerfil");
            if (inputFoto) inputFoto.value = dataUrl;
            modalCamara.ocultar();
        };

        if (btnCerrar) btnCerrar.onclick = () => modalCamara.ocultar();
    });

    if (btnAbrirCamara) btnAbrirCamara.onclick = () => modalCamara.mostrar();
});
