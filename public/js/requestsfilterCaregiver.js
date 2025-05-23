document.addEventListener('DOMContentLoaded', function () {
    const originalRequests = Array.from(document.querySelectorAll('.request-item'));
    const requestListContainer = document.querySelector('.request-list');

    document.getElementById('apply-filters-btn').addEventListener('click', function () {
        const dateFilter = document.getElementById('filter-date').value;
        const statusFilter = document.getElementById('filter-status').value;

        let filteredRequests = originalRequests.filter(item => {
            const status = item.querySelector('.req-action span').textContent.trim().toLowerCase();

            let matches = true;

            // Filter by status
            if (statusFilter !== 'all' && status !== statusFilter) {
                matches = false;
            }

            return matches;
        });

        // Sort by date
        if (dateFilter === 'newest' || dateFilter === 'oldest') {
            filteredRequests.sort((a, b) => {
                const dateA = new Date(a.querySelector('.req-date').textContent.trim());
                const dateB = new Date(b.querySelector('.req-date').textContent.trim());

                return dateFilter === 'newest' ? dateB - dateA : dateA - dateB;
            });
        }

        // Clear and update the DOM
        requestListContainer.innerHTML = '';

        if (filteredRequests.length > 0) {
            filteredRequests.forEach(item => {
                requestListContainer.appendChild(item);
            });
        } else {
            const noResults = document.createElement('p');
            noResults.classList.add('no-requests');
            noResults.textContent = 'No requests found.';
            requestListContainer.appendChild(noResults);
        }
    });
});
