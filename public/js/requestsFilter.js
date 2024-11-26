// Store the original rows when the page loads
const originalRows = Array.from(document.querySelectorAll('.view-requests-m-c-r-table-body .view-requests-m-c-r-table-row'));

// Add the filter event listener
document.getElementById('apply-filters-btn').addEventListener('click', function () {
    const dateFilter = document.getElementById('filter-date').value;
    const statusFilter = document.getElementById('filter-status').value;
    const serviceFilter = document.getElementById('filter-service').value;

    const tableBody = document.querySelector('.view-requests-m-c-r-table-body');

    // Start with the original rows
    let filteredRows = originalRows.filter(row => {
        const date = row.children[1].textContent.trim(); // Assuming the date is in the second cell
        const status = row.children[4].textContent.trim().toLowerCase(); // Status in the fifth cell
        const service = row.children[3].textContent.trim().toLowerCase(); // Service in the fourth cell

        let matches = true;

        // Filter by status
        if (statusFilter !== 'all' && status !== statusFilter) {
            matches = false;
        }

        // Filter by service
        if (serviceFilter !== 'all' && service !== serviceFilter) {
            matches = false;
        }

        return matches;
    });

    // Sort rows by date
    if (dateFilter === 'newest') {
        filteredRows.sort((a, b) => {
            const dateA = new Date(a.children[1].textContent.trim());
            const dateB = new Date(b.children[1].textContent.trim());
            return dateB - dateA; // Newest first
        });
    } else if (dateFilter === 'oldest') {
        filteredRows.sort((a, b) => {
            const dateA = new Date(a.children[1].textContent.trim());
            const dateB = new Date(b.children[1].textContent.trim());
            return dateA - dateB; // Oldest first
        });
    }

    // Clear and re-append rows
    tableBody.innerHTML = '';
    filteredRows.forEach(row => {
        tableBody.appendChild(row);
    });

    // Show "No matches found" message if no rows remain
    if (filteredRows.length === 0) {
        const noMatchesMessage = document.createElement('div');
        noMatchesMessage.classList.add('no-matches-message');
        noMatchesMessage.textContent = 'No matches found.';
        tableBody.appendChild(noMatchesMessage);
    }
});
