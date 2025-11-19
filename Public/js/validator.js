const Validator = {

    vacio: function(value) {
        return value && value.trim() !== "";
    },

    email: function(value) {
        const regex = /\S+@\S+\.\S+/;
        return regex.test(value);
    },

    telefono: function(value) {
        if (!value) return true;
        const regex = /^\d{9}$/;
        return regex.test(value);
    },

    fecha: function(value) {
        return !!value;
    },

    fechaFinPosterior: function(inicio, fin) {
        if (!inicio || !fin) return true; 
        return new Date(fin) >= new Date(inicio);
    }

};
