<?php 
    $required_styles = [
        'moderator/careseekerrequests',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT.'/views/includes/components/sidebar.php'; ?>
    
    <div class="dashboard-container">
        <!-- Dashboard-style header -->
        <header class="dashboard-header">
            <h1>Rejected Requests</h1>
            <div class="search-wrapper">
                <input type="text" id="searchInput" placeholder="Search By UserName Or Email" />
            </div>
        </header>

        <!-- Main content area -->
        <div class="request-content">
            <div class="request-table-container">
                <h2>Request Information</h2>
                <div class="request-table">
                    <div class="request-table-header">
                        <div class="request-table-cell">Request ID</div>
                        <div class="request-table-cell">Date</div>
                        <div class="request-table-cell">User Name</div>
                        <div class="request-table-cell">Status</div>
                        <div class="request-table-cell">Action</div>
                    </div>
                    <div class="request-table-body" id="requestTableBody">
                        <?php foreach($data['requests'] as $request) : ?>
                            <div class="request-table-row" 
                                 data-username="<?php echo htmlspecialchars($request->username); ?>" 
                                 data-email="<?php echo htmlspecialchars($request->email); ?>"
                                 data-user-id="<?php echo $request->user_id; ?>">
                                <div class="request-table-cell"><?php echo $request->request_id; ?></div>
                                <div class="request-table-cell"><?php echo date_format(date_create($request->request_date), 'j M Y'); ?></div>
                                <div class="request-table-cell"><?php echo $request->username; ?></div>
                                <div class="request-table-cell status-declined"><?php echo $request->status; ?></div>
                                <div class="request-table-cell">
                                    <form action="<?php echo URLROOT; ?>/moderator/viewrequests" method="POST">
                                        <input type="hidden" name="request_id" value="<?php echo $request->request_id; ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $request->user_id; ?>">
                                        <input type="hidden" name="request_type" value="<?php echo $request->request_type; ?>">
                                        <button type="submit" class="request-action-btn">Details</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</page-body-container>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const requestRows = document.querySelectorAll('.request-table-row');
    
    // Function to perform search
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        // If search term is empty, show all rows
        if (searchTerm === '') {
            requestRows.forEach(row => {
                row.style.display = '';
            });
            return;
        }
        
        // Filter rows based on search term
        requestRows.forEach(row => {
            const username = row.getAttribute('data-username').toLowerCase();
            const email = row.getAttribute('data-email').toLowerCase();
            const userId = row.getAttribute('data-user-id').toLowerCase();
            
            // Check if search term matches username, email, or user ID
            if (username.includes(searchTerm) || 
                email.includes(searchTerm) || 
                userId.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Add event listener for search input
    searchInput.addEventListener('input', performSearch);
    
    // Add event listener for search button click
    searchButton.addEventListener('click', performSearch);
    
    // Add event listener for Enter key press in search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
});
</script>

<?php require APPROOT.'/views/includes/footer.php'?>
