document.addEventListener('DOMContentLoaded', () => {

    fetch('/api/ApiEstadistica.php', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(data => {

        // ── 1. Gráfico Circular: Solicitudes por Estado ──
        if (data.solicitudesPorEstado) {
            const estados = ['pendiente', 'aceptada', 'rechazada'];
            const estadoMap = {};
            data.solicitudesPorEstado.labels.forEach((label, i) => {
                estadoMap[label] = data.solicitudesPorEstado.data[i];
            });
            const dataEstado = estados.map(e => estadoMap[e] ?? 0);

            const canvasEstado = document.getElementById('graficoEstado');
            if (canvasEstado) {
                new Chart(canvasEstado.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: estados,
                        datasets: [{
                            data: dataEstado,
                            backgroundColor: [
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 99, 132, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 206, 86, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Solicitudes por Estado' }
                        }
                    }
                });
            }
        }

        // ── 2. Gráfico de Barras: Solicitudes por Oferta ──
        if (data.solicitudesPorOferta) {
            const canvasOferta = document.getElementById('graficoOferta');
            if (canvasOferta) {
                new Chart(canvasOferta.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.solicitudesPorOferta.labels,
                        datasets: [{
                            label: 'Solicitudes por Oferta',
                            data: data.solicitudesPorOferta.data,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: 'Solicitudes por Oferta' }
                        },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        }

        // ── 3. Gráfico Doughnut: Totales de Ofertas y Solicitudes ──
        const canvasTotales = document.getElementById('graficoTotales');
        if (canvasTotales && data.totalOfertas !== undefined && data.totalSolicitudes !== undefined) {
            new Chart(canvasTotales.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Total Ofertas', 'Total Solicitudes'],
                    datasets: [{
                        data: [data.totalOfertas, data.totalSolicitudes],
                        backgroundColor: [
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Totales de la Empresa' }
                    }
                }
            });
        }

    })
    .catch(error => console.error('Error cargando estadísticas:', error));

});
