window.addEventListener("load", function () {
    const menuBtn = document.getElementById("menuBtn");
    const menuContainer = document.querySelector(".menu-container");
    const cerrarBtn = document.createElement("button");

   
    cerrarBtn.innerText = "✖";
    cerrarBtn.classList.add("cerrar-menu");
    menuContainer.appendChild(cerrarBtn);


    menuBtn.addEventListener("click", function () {
        menuContainer.classList.add("mostrar");
    });


    cerrarBtn.addEventListener("click", function () {
        menuContainer.classList.remove("mostrar");
    });
});
