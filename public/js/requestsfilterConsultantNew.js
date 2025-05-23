document.addEventListener('DOMContentLoaded', function () {
    // Get the form element to prevent default submission
    const filterForm = document.querySelector('.view-requests-m-c-r-filter-section');
    
    // Store original requests when page loads
    const originalRequests = Array.from(document.querySelectorAll('.view-requests-m-c-r-table-row'));
    const requestListContainer = document.querySelector('.view-requests-m-c-r-table-body');
    
    // Prevent form submission and handle filtering with JavaScript
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting
        
        const dateFilter = document.getElementById('filter-date').value;
        const statusFilter = document.getElementById('filter-status').value;
        
        let filteredRequests = originalRequests.filter(item => {
            // Get the status text from the status span
            const statusElement = item.querySelector('.tag');
            const status = statusElement ? statusElement.textContent.trim().toLowerCase() : '';
            
            // Check if this request matches the selected status filter
            if (statusFilter !== 'all' && status !== statusFilter) {
                return false;
            }
            
            return true;
        });
        
        // Sort by date
        if (dateFilter === 'newest' || dateFilter === 'oldest') {
            filteredRequests.sort((a, b) => {
                // Extract date from the formatted date text
                const dateTextA = a.querySelector('.req-date').textContent.replace(/[^\d/]/g, '').trim();
                const dateTextB = b.querySelector('.req-date').textContent.replace(/[^\d/]/g, '').trim();
                
                // Convert to Date objects for comparison
                const dateA = new Date(dateTextA);
                const dateB = new Date(dateTextB);
                
                return dateFilter === 'newest' ? dateB - dateA : dateA - dateB;
            });
        }
        
        // Clear and update the DOM
        requestListContainer.innerHTML = '';
        
        if (filteredRequests.length > 0) {
            filteredRequests.forEach(item => {
                requestListContainer.appendChild(item.cloneNode(true));
            });
        } else {
            // Show no results message
            requestListContainer.innerHTML = `
                <div class="no-requests">
                    <img src="/we4u/public/images/Empty-cuate.png" alt="No Request">
                    <p>No requests found.</p>
                </div>
            `;
        }
    });
    
    // Also attach the handler to the button click for good measure
    document.getElementById('apply-filters-btn').addEventListener('click', function() {
        // Trigger the form submission event
        const event = new Event('submit');
        filterForm.dispatchEvent(event);
    });
});