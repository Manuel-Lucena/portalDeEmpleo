document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');

    form.addEventListener('submit', e => {
        e.preventDefault();

        const username = form.querySelector('input[name="username"]').value;
        const password = form.querySelector('input[name="password"]').value;

        fetch('../api/ApiToken.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({username,password})
        })
        .then(res => res.json())
        .then(data => {
            if (data.token) {
                 localStorage.setItem('token', data.token);
                form.submit();
            } else {
                alert('Usuario o contraseña incorrecta');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error de conexión');
        });
    });
});
