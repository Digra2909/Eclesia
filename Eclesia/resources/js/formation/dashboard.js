document.addEventListener('DOMContentLoaded', function () {
    const dataEl = document.getElementById('dashboard-data');
    if (!dataEl || typeof Chart === 'undefined') {
        return;
    }
    const data = JSON.parse(dataEl.textContent);

    const baseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 500 },
        plugins: { legend: { display: false } },
    };

    function renderChart(id, config) {
        const ctx = document.getElementById(id);
        if (!ctx) {
            return;
        }
        new Chart(ctx.getContext('2d'), config);
    }

    renderChart('sparklineChart', {
        type: 'line',
        data: {
            labels: data.sparkline.labels,
            datasets: [{
                label: '% présences',
                data: data.sparkline.values,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.4,
                fill: true,
            }],
        },
        options: {
            ...baseOptions,
            scales: { y: { beginAtZero: true, max: 100 } },
        },
    });

    renderChart('sectorChart', {
        type: 'pie',
        data: data.sector,
        options: {
            ...baseOptions,
            plugins: { legend: { position: 'bottom' } },
        },
    });

    renderChart('doughnutChart', {
        type: 'doughnut',
        data: data.doughnut,
        options: {
            ...baseOptions,
            cutout: '75%',
            plugins: { legend: { position: 'bottom' } },
        },
    });
});