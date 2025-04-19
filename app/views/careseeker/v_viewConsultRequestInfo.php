<?php
$required_styles = [
    'careseeker/viewRequestInfo',
];
echo loadCSS($required_styles);
?>

<?php require APPROOT . '/views/includes/header.php'; ?>
<?php require APPROOT . '/views/includes/components/topnavbar.php'; ?>

<page-body-container>
    <?php require APPROOT . '/views/includes/components/sidebar.php'; ?>
    <!-- Container -->
    <div class="request-info">
        <div class="request-info-heading">
            <p>Request Information</p>
        </div>

        <!-- Personal info section -->
        <div class="request-info-header">
            <div class="request-info-header-left">
                <div class="request-info-header-left-left">
                    <?php

                    // Determine which image to display
                    $ConsultantprofilePic = !empty($data->consultant_pic)
                        ? URLROOT . '/public/images/profile_imgs/' . $data->consultant_pic
                        : URLROOT . '/public/images/def_profile_pic2.jpg';

                    $CareseekerprofilePic = !empty($data->requester_pic)
                        ? URLROOT . '/public/images/profile_imgs/' . $data->requester_pic
                        : URLROOT . '/public/images/def_profile_pic2.jpg';

                    $elderprofilePic = !empty($data->elder_pic)
                        ? URLROOT . '/public/images/profile_imgs/' . $data->elder_pic
                        : URLROOT . '/public/images/def_profile_pic2.jpg';
                    ?>
                    <div class="info-circle image1"><img src="<?= $CareseekerprofilePic ?>" alt="Profile" /></div>
                    <div class="info-circle image1"><img src="<?= $elderprofilePic ?>" alt="Profile" /></div>
                    <div class="info-circle image1"><img src="<?= $ConsultantprofilePic ?>" alt="Profile" /></div>
                </div>
                <div class="request-info-header-left-right">
                    <div class="request-info-personal-info-profile">

                        <div class="request-info-personal-info-details">
                            <h2>Request ID #<?= $data->request_id ?></h2>
                            <span class="tag <?= strtolower($data->status) ?>">
                                <?= ucfirst($data->status) ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="request-info-buttons">
                    <button class="request-send-button">
                        Make Payment
                    </button>
                    <button class="request-cancel-button" onclick="window.location.href='<?php echo URLROOT; ?>/careseeker/viewRequests'">
                        Cancel
                    </button>
                </div>

            </div>


        </div>
        <div class="request-infos-section">
            <!-- First Column -->
            <div class="request-info-column">
                <div class="request-info-row">
                    <label>Requested Date</label>
                    <p>
                        <?= date('d/m/Y', strtotime($data->appointment_date)) ?>
                    </p>
                </div>

                <div class="request-info-row">
                    <label>Elder Profile Name</label>
                    <p><?= htmlspecialchars($data->elder_name) ?> (<?= htmlspecialchars($data->relationship_to_careseeker) ?>)</p>
                </div>

                <div class="request-info-row">
                    <label>Requested Slot</label>
                    <p>
                        <?php
                        if (!empty($data->time_slot)) {
                            [$from, $to] = explode('-', $data->time_slot);

                            $fromTime = DateTime::createFromFormat('H:i', trim($from));
                            $toTime = DateTime::createFromFormat('H:i', trim($to));

                            if ($fromTime && $toTime) {
                                echo "From " . $fromTime->format('g:i A') . " to " . $toTime->format('g:i A');
                            } else {
                                echo htmlspecialchars($data->time_slot); // fallback if format is unexpected
                            }
                        } else {
                            echo "N/A";
                        }
                        ?>
                    </p>


                </div>

                <div class="request-info-row">
                    <label>Service</label>
                    <p>Consultation</p>
                </div>
                <div class="request-info-row">
                    <label>Expected Services</label>
                    <p><?= htmlspecialchars($data->expected_services) ?></p>
                </div>

            </div>

            <!-- Second Column -->
            <div class="request-info-column">
                <div class="request-info-row">
                    <label>Service Provider ID</label>
                    <p>#<?= $data->consultant_id ?></p>
                </div>
                <div class="request-info-row">
                    <label>Service Provider Name</label>
                    <p><?= htmlspecialchars($data->consultant_name) ?></p>
                </div>
                <div class="request-info-row">
                    <label>Service Provider Email</label>
                    <p><?= htmlspecialchars($data->consultant_email) ?></p>
                </div>
                <div class="request-info-row">
                    <label>Total Payment</label>
                    <p>Rs. <?= $data->payment_details ?? 'N/A' ?></p>
                </div>
                <div class="request-info-row">
                    <label>Additional Notes</label>
                    <p><?= htmlspecialchars($data->additional_notes) ?></p>
                </div>

            </div>
        </div>




    </div>

    </div>

</page-body-container>
<script src="<?php echo URLROOT; ?>/js/ratingStars.js"></script>
<?php require APPROOT . '/views/includes/footer.php' ?>