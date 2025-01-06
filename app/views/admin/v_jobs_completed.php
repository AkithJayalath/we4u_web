<?php 
    $required_styles = [
        'admin/jobs_completed',
    ];
    echo loadCSS($required_styles);
?>

<?php require APPROOT.'/views/includes/header.php'; ?>
<div>
<?php require APPROOT.'/views/includes/components/topnavbar.php'; ?>
</div>

<page-body-container>
<?php require APPROOT.'/views/includes/components/sidebar.php'; ?>

<div class="dashboard-container">
    <header class="dashboard-header">
        <h1>Jobs Completed Overview</h1>
        <button class="export-btn">Export Report</button>
    </header>

    <div class="dashboard-metrics">
        <div class="metric">
            <h3 class="metric-title">Weekly Jobs</h3>
            <div class="metric-value"><?php echo $data['jobs_stats']['weekly']; ?></div>
            <div class="metric-change">+15%</div>
        </div>
        <div class="metric">
            <h3 class="metric-title">Monthly Jobs</h3>
            <div class="metric-value"><?php echo $data['jobs_stats']['monthly']; ?></div>
            <div class="metric-change">+22%</div>
        </div>
        <div class="metric">
            <h3 class="metric-title">Yearly Jobs</h3>
            <div class="metric-value"><?php echo $data['jobs_stats']['yearly']; ?></div>
            <div class="metric-change">+35%</div>
        </div>
        <div class="metric">
            <h3 class="metric-title">Total Jobs</h3>
            <div class="metric-value"><?php echo $data['jobs_stats']['total']; ?></div>
            <div class="metric-change">+48%</div>
        </div>
    </div>

    <div class="jobs-table-container">
        <div class="table-controls">
            <div class="search-wrapper">
                <input type="search" placeholder="Search jobs..." />
            </div>
            <select class="filter-select">
                <option value="">All Services</option>
                <option value="nursing">CareGiving</option>
                <option value="physiotherapy">Consulting</option>
            </select>
        </div>

        <table class="jobs-table">
            <thead>
                <tr>
                    <th>Service Provider</th>
                    <th>Service</th>
                    <th>Careseeker</th>
                    <th>Date Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="provider-cell">
                        <img src="<?php echo URLROOT; ?>/public/images/caregiver-logo.png" alt="Provider" />
                        <span>John Doe</span>
                    </td>
                    <td>CareGiving</td>
                    <td>Sarah Smith</td>
                    <td>2023-11-15</td>
                    <td>
                    <a href="<?php echo URLROOT; ?>/admin/viewCompletedJob/1">
                        <button class="action-btn view-btn">View Details</button>
                    </a>    
                    </td>
                </tr>
                <tr>
                    <td class="provider-cell">
                        <img src="<?php echo URLROOT; ?>/public/images/caregiver-logo.png" alt="Provider" />
                        <span>John Doe</span>
                    </td>
                    <td>Consulting</td>
                    <td>Sarah Smith</td>
                    <td>2023-11-15</td>
                    <td>
                        <button class="action-btn view-btn">View Details</button>
                    </td>
                </tr>
                <tr>
                    <td class="provider-cell">
                        <img src="<?php echo URLROOT; ?>/public/images/caregiver-logo.png" alt="Provider" />
                        <span>John Doe</span>
                    </td>
                    <td>CareGiving</td>
                    <td>Sarah Smith</td>
                    <td>2023-11-15</td>
                    <td>
                        <button class="action-btn view-btn">View Details</button>
                    </td>
                </tr>
                <tr>
                    <td class="provider-cell">
                        <img src="<?php echo URLROOT; ?>/public/images/caregiver-logo.png" alt="Provider" />
                        <span>John Doe</span>
                    </td>
                    <td>Consulting</td>
                    <td>Sarah Smith</td>
                    <td>2023-11-15</td>
                    <td>
                        <button class="action-btn view-btn">View Details</button>
                    </td>
                </tr>
                <tr>
                    <td class="provider-cell">
                        <img src="<?php echo URLROOT; ?>/public/images/caregiver-logo.png" alt="Provider" />
                        <span>John Doe</span>
                    </td>
                    <td>CareGiving</td>
                    <td>Sarah Smith</td>
                    <td>2023-11-15</td>
                    <td>
                        <button class="action-btn view-btn">View Details</button>
                    </td>
                </tr>
                <tr>
                    <td class="provider-cell">
                        <img src="<?php echo URLROOT; ?>/public/images/caregiver-logo.png" alt="Provider" />
                        <span>John Doe</span>
                    </td>
                    <td>Consulting</td>
                    <td>Sarah Smith</td>
                    <td>2023-11-15</td>
                    <td>
                        <button class="action-btn view-btn">View Details</button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>
</page-body-container>

<?php require APPROOT.'/views/includes/footer.php'; ?>
