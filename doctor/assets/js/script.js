// Wait for DOM to load
document.addEventListener('DOMContentLoaded', () => {
    


    // Sidebar Toggle Logic for Mobile
    const sidebarToggle = document.getElementById('sidebarCollapse');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        });
    }

    // Dummy Chart Initialization (using Chart.js if added)
    // Only initialize if context exists
    const ctx = document.getElementById('activityChart');
    if (ctx && typeof Chart !== 'undefined') {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Patients View',
                    data: [12, 19, 15, 25, 22, 10, 8],
                    borderColor: '#0b5ed7',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(11, 94, 215, 0.1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
