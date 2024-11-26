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
    <div class="m-c-r-container">
        <div class="m-c-r-search-section">
            <input type="text" placeholder="Search By UserName Or ID" />
            <button>Search</button>
        </div>

        <div class="m-c-r-table-container">
            <h2>Approved Requests</h2>
            <div class="m-c-r-table">
                <div class="m-c-r-table-header">
                    <div class="m-c-r-table-cell">Request ID</div>
                    <div class="m-c-r-table-cell">Date</div>
                    <div class="m-c-r-table-cell">User ID</div>
                    <div class="m-c-r-table-cell">Status</div>
                    <div class="m-c-r-table-cell">Action</div>
                </div>
                <div class="m-c-r-table-body">
                    <?php foreach($data['requests'] as $request) : ?>
                        <div class="m-c-r-table-row">
                            <div class="m-c-r-table-cell"><?php echo $request->request_id; ?></div>
                            <div class="m-c-r-table-cell"><?php echo date_format(date_create($request->request_date), 'j M Y'); ?></div>
                            <div class="m-c-r-table-cell"><?php echo $request->user_id; ?></div>
                            <div class="m-c-r-table-cell status-approved"><?php echo $request->status; ?></div>
                            <div class="m-c-r-table-cell">
                                <form action="<?php echo URLROOT; ?>/moderator/viewrequests" method="POST">
                                    <input type="hidden" name="request_id" value="<?php echo $request->request_id; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $request->user_id; ?>">
                                    <input type="hidden" name="request_type" value="<?php echo $request->request_type; ?>">
                                    <button type="submit" class="m-c-r-view-req-action-btn">Details</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'?>
