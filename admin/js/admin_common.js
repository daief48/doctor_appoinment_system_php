/**
 * Admin Panel Common Script
 * Handles table searching, filtering, and dynamic UI updates
 */

document.addEventListener('DOMContentLoaded', function() {
    // 1. Table Search Logic
    const searchInput = document.getElementById('doctorSearch') || 
                        document.getElementById('appointmentSearch') || 
                        document.getElementById('patientSearch') ||
                        document.getElementById('paymentSearch');
                        
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // 2. Status Filter Logic
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const filterValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            
            rows.forEach(row => {
                if (!filterValue) {
                    row.style.display = '';
                    return;
                }
                const statusBadge = row.querySelector('.badge');
                if (statusBadge) {
                    const statusText = statusBadge.textContent.toLowerCase();
                    row.style.display = statusText.includes(filterValue) ? '' : 'none';
                }
            });
        });
    }

    // 3. Date Filter (for appointments)
    const dateFilter = document.getElementById('dateFilter');
    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            const filterDate = this.value;
            const rows = document.querySelectorAll('table tbody tr');
            
            rows.forEach(row => {
                if (!filterDate) {
                    row.style.display = '';
                    return;
                }
                const rowContent = row.textContent;
                row.style.display = rowContent.includes(filterDate) ? '' : 'none';
            });
        });
    }
});

/**
 * Reset all filters
 */
function resetFilters() {
    window.location.reload();
}
