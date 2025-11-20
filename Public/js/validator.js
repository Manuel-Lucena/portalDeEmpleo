class Validador {

    static vacio(valor) {
        return valor.trim() !== "";
    }

    static email(valor) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor);
    }

    static fecha(valor) {
        return /^\d{4}-\d{2}-\d{2}$/.test(valor);
    }

    static telefono(valor) {
        return /^\d{9}$/.test(valor);
    }

    static fechaFinPosterior(fechaInicio, fechaFin) {
        return new Date(fechaFin) > new Date(fechaInicio);
    }

    static maxLength(valor, max) {
        return valor.length <= max;
    }

}
