/* ===========================
   Admin Panel JavaScript
   =========================== */

// Toggle Sidebar
const toggleBtn = document.querySelector('.toggle-sidebar-btn');
const sidebar = document.querySelector('.sidebar');
const mainContent = document.querySelector('.main-content');
const topbar = document.querySelector('.topbar');

if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('no-sidebar');
        topbar.classList.toggle('no-sidebar');
    });
}

// Set Active Navigation Link
function setActiveNav() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.html')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

setActiveNav();

// Show Toast Notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast alert alert-${type}`;
    toast.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <span>${message}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Confirm Delete
function confirmDelete(itemName = 'this item') {
    return confirm(`Are you sure you want to delete ${itemName}? This action cannot be undone.`);
}

// Format Date
function formatDate(date) {
    if (typeof date === 'string') {
        date = new Date(date);
    }
    
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return date.toLocaleDateString('en-GB', options);
}

// Filter Table
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    const tr = table.getElementsByTagName('tr');
    
    if (input) {
        input.addEventListener('keyup', function() {
            const filter = input.value.toUpperCase();
            
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < td.length; j++) {
                    if (td[j].innerText.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
                
                tr[i].style.display = found ? '' : 'none';
            }
        });
    }
}

// Sort Table
function sortTable(columnIndex, tableId) {
    const table = document.getElementById(tableId);
    let rows = Array.from(table.getElementsByTagName('tr'));
    const headerRow = rows.shift();
    let ascending = true;
    
    rows.sort((a, b) => {
        const aValue = a.getElementsByTagName('td')[columnIndex].innerText;
        const bValue = b.getElementsByTagName('td')[columnIndex].innerText;
        
        if (!isNaN(aValue) && !isNaN(bValue)) {
            return ascending ? aValue - bValue : bValue - aValue;
        }
        
        if (ascending) {
            return aValue.localeCompare(bValue);
        }
        return bValue.localeCompare(aValue);
    });
    
    const tbody = table.getElementsByTagName('tbody')[0];
    rows.forEach(row => tbody.appendChild(row));
    
    return ascending;
}

// Pagination
function setupPagination(itemsPerPage = 10) {
    const tables = document.querySelectorAll('table');
    
    tables.forEach(table => {
        const rows = table.querySelectorAll('tbody tr');
        const pageCount = Math.ceil(rows.length / itemsPerPage);
        
        if (pageCount > 1) {
            let currentPage = 1;
            
            function showPage(page) {
                rows.forEach((row, index) => {
                    const start = (page - 1) * itemsPerPage;
                    const end = start + itemsPerPage;
                    row.style.display = (index >= start && index < end) ? '' : 'none';
                });
            }
            
            showPage(currentPage);
            
            // Create pagination controls if needed
            const pagination = document.createElement('nav');
            pagination.className = 'mt-4';
            pagination.innerHTML = `
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link" href="#" data-page="prev">Previous</a></li>
            `;
            
            for (let i = 1; i <= pageCount; i++) {
                pagination.innerHTML += `<li class="page-item" data-page="${i}"><a class="page-link" href="#">${i}</a></li>`;
            }
            
            pagination.innerHTML += `
                    <li class="page-item"><a class="page-link" href="#" data-page="next">Next</a></li>
                </ul>
            `;
            
            table.parentElement.appendChild(pagination);
            
            pagination.addEventListener('click', (e) => {
                if (e.target.className.includes('page-link')) {
                    e.preventDefault();
                    const page = e.target.getAttribute('data-page');
                    
                    if (page === 'next' && currentPage < pageCount) currentPage++;
                    else if (page === 'prev' && currentPage > 1) currentPage--;
                    else if (!isNaN(page)) currentPage = parseInt(page);
                    
                    showPage(currentPage);
                    
                    // Update active page
                    pagination.querySelectorAll('.page-item').forEach(item => {
                        item.classList.remove('active');
                        if (item.getAttribute('data-page') === currentPage.toString()) {
                            item.classList.add('active');
                        }
                    });
                }
            });
        }
    });
}

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    
    if (form) {
        form.addEventListener('submit', (e) => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    }
}

// Modal Helper
function openModal(modalId) {
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
}

function closeModal(modalId) {
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    if (modal) modal.hide();
}

// Logout
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('adminToken');
        window.location.href = 'login.html';
    }
}

// Initialize on Page Load
document.addEventListener('DOMContentLoaded', () => {
    // Add any page-specific initialization here
    setupPagination(10);
});

// Format Currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount);
}

// Get Status Badge HTML
function getStatusBadge(status) {
    const badges = {
        'approved': '<span class="badge badge-approved"><i class="bi bi-check-circle"></i> Approved</span>',
        'pending': '<span class="badge badge-pending"><i class="bi bi-clock"></i> Pending</span>',
        'cancelled': '<span class="badge badge-cancelled"><i class="bi bi-x-circle"></i> Cancelled</span>',
        'paid': '<span class="badge badge-paid"><i class="bi bi-check-circle"></i> Paid</span>',
        'unpaid': '<span class="badge badge-unpaid"><i class="bi bi-exclamation-circle"></i> Unpaid</span>',
        'active': '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>',
        'inactive': '<span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Inactive</span>'
    };
    
    return badges[status] || `<span class="badge bg-secondary">${status}</span>`;
}

// Notification Center
function updateNotifications(count) {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        badge.textContent = count;
        if (count > 0) {
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
}

// Export Data to CSV
function exportTableToCSV(tableId, filename = 'export.csv') {
    const table = document.getElementById(tableId);
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        let csvRow = [];
        
        cols.forEach(col => {
            csvRow.push('"' + col.innerText.replace(/"/g, '""') + '"');
        });
        
        csv.push(csvRow.join(','));
    });
    
    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.href = URL.createObjectURL(csvFile);
    downloadLink.download = filename;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Initialize Bootstrap Tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

document.addEventListener('DOMContentLoaded', initializeTooltips);
