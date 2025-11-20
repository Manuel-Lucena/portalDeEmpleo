document.addEventListener("DOMContentLoaded", () => {
    // Si existe contenedor de solicitudes, cargamos solicitudes
    const contenedorSolicitudes = document.querySelector(".lista-solicitudes");
    if (contenedorSolicitudes) {
        cargarSolicitudes();
    }

    // Asignamos eventos a botones de ofertas
    asignarEventosOfertas();
});


// =====================
// FUNCIONES PRINCIPALES
// =====================
function cargarSolicitudes() {
    fetch('/api/ApiSolicitud.php', {
        headers: {
            "Authorization": "Bearer " + localStorage.getItem("token")
        }
    })
        .then(res => res.json())
        .then(solicitudes => {
            const contenedor = document.querySelector(".lista-solicitudes");
            contenedor.innerHTML = "";

            solicitudes.forEach(sol => {
                const div = document.createElement("div");
                div.classList.add("solicitud");
                const claseFavorito = sol.favorito == 1 ? "activo" : "";

                const botonesHTML = (sol.rolUsuario === 1 || sol.rolUsuario === 2) ? `
             ${sol.estado === "pendiente" ? `
                <button class="btn-verde btn-aprobar">Aprobar</button>
                <button class="btn-rojo btn-rechazar">Rechazar</button>
                ` : ''}

                <button class="btn-azul btn-cv">Ver CV</button>
                <button class="btn btn-corazon ${claseFavorito}">&#10084;</button>
              ` : '';

                div.innerHTML = `
                <h2>${sol.oferta}</h2>
                <h2>(${sol.empresa})</h2>
               <p><strong>Alumno:</strong> ${sol.alumno}</p>
                <p><strong>Estado:</strong> ${sol.estado}</p>
        <p><strong>Fecha:</strong> ${sol.fechaSolicitud}</p>
                <input type="hidden" class="id-solicitud" value="${sol.id}">
                <input type="hidden" class="id-alumno" value="${sol.idAlumno}">
                <div class="botones-solicitud">
                    ${botonesHTML}
                </div>
            `;
                contenedor.appendChild(div);
            });

            asignarEventosSolicitudes();
        })
        .catch(err => console.error("Error cargando solicitudes:", err));
}


function asignarEventosSolicitudes() {

    document.querySelectorAll(".btn-aprobar, .btn-rechazar").forEach(boton => {
        boton.onclick = () => {
            const solicitudDiv = boton.closest(".solicitud");
            const idSolicitud = solicitudDiv.querySelector(".id-solicitud").value;
            let nuevoEstado = "";

            if (boton.classList.contains("btn-aprobar")) {
                nuevoEstado = "aceptada";
            } else if (boton.classList.contains("btn-rechazar")) {
                nuevoEstado = "rechazada";
            }

            fetch("/api/ApiSolicitud.php", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + localStorage.getItem("token")
                },
                body: JSON.stringify({ idSolicitud: idSolicitud, estado: nuevoEstado })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.mensaje) {
                        alert(data.mensaje);
                        cargarSolicitudes();
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(err => console.error("Error al actualizar solicitud:", err));
        };
    });

    // Botones CV

    document.querySelectorAll(".btn-cv").forEach(boton => {
        boton.onclick = () => {
            const solicitudDiv = boton.closest(".solicitud");
            const idAlumno = solicitudDiv.querySelector(".id-alumno").value;

            const modalCv = Modal.crear("modalCv", "html/cv.html", function () {
                modalCv.mostrar();
                const iframe = document.getElementById("iframeCurriculum");
                if (!iframe) return;

                fetch("/api/ApiAlumno.php?id=" + idAlumno, {
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
                })
                    .then(res => res.json())
                    .then(alumno => {
                        if (!alumno || alumno.error) {
                            iframe.srcdoc = "<p>No se pudo cargar el CV</p>";
                        } else if (alumno.curriculum && alumno.curriculum.length > 0) {
                            iframe.src = "data:application/pdf;base64," + alumno.curriculum;
                        } else {
                            iframe.srcdoc = "<p>No hay CV disponible</p>";
                        }
                    })
                    .catch(err => {
                        console.error("Error al cargar el CV:", err);
                        iframe.srcdoc = "<p>No se pudo cargar el CV</p>";
                    });

                const btnCerrarCv = document.getElementById("btnCancelarCv");
                if (btnCerrarCv) btnCerrarCv.onclick = () => modalCv.ocultar();
            });
        };
    });



    // Botones corazón
    document.querySelectorAll(".btn-corazon").forEach(boton => {
        boton.onclick = () => {
            const solicitudDiv = boton.closest(".solicitud");
            const idSolicitud = solicitudDiv.querySelector(".id-solicitud").value;
            const nuevoFavorito = boton.classList.contains("activo") ? 0 : 1;

            fetch("/api/ApiSolicitud.php", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + localStorage.getItem("token")
                },
                body: JSON.stringify({ idSolicitud: idSolicitud, favorito: nuevoFavorito })
            })

                .then(res => res.json())
                .then(data => {
                    if (data.mensaje) {
                        boton.classList.toggle("activo", nuevoFavorito === 1);
                    } else {
                        alert("No se pudo actualizar el favorito");
                    }
                })
                .catch(err => console.error("Error al actualizar favorito:", err));
        };
    });
}


// Asigna eventos a botones de ofertas (Solicitar)
function asignarEventosOfertas() {
    document.querySelectorAll(".btn-solicitar").forEach(boton => {
        boton.onclick = () => {
            const ofertaCard = boton.closest(".oferta-card");
            const idAlumno = ofertaCard.querySelector(".id-alumno").value;
            const idOferta = ofertaCard.querySelector(".id-oferta").value;

            console.log("Click en Solicitar:", idAlumno, idOferta);

            fetch("/api/ApiSolicitud.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + localStorage.getItem("token")
                },
                body: JSON.stringify({ idAlumno: idAlumno, idOferta: idOferta })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.mensaje) {
                        alert(data.mensaje);
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(err => console.error("Error POST solicitud:", err));
        };
    });
}
