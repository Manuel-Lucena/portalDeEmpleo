window.addEventListener("load", function () {
    const botonesSolicitar = document.querySelectorAll(".btn-solicitar");

    botonesSolicitar.forEach(boton => {
        boton.addEventListener("click", function (e) {

            const ofertaDiv = boton.closest(".oferta");
            const inputId = ofertaDiv.querySelector("input[type='hidden']");
            const idOferta = inputId ? inputId.value : null;

            console.log("Solicitud pulsada para la oferta con ID:", idOferta);
        });
    });
});
