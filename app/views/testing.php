<div class="container mt-4">
    <h2>Caregiver Schedule Testing</h2>
    
    <div class="row mb-4">
        <div class="col">
            <form action="<?php echo URLROOT; ?>/testing/createSchedule" method="post" class="mb-3">
                <button type="submit" class="btn btn-primary">Create Schedule</button>
            </form>
            
            <a href="<?php echo URLROOT; ?>/testing/getSchedules" class="btn btn-info">Get All Schedules</a>
            <a href="<?php echo URLROOT; ?>/testing/checkDateRangeAvailability" class="btn btn-success">Check Date Range</a>
        </div>
    </div>
    
    <?php if(isset($data['start_date']) && isset($data['end_date'])): ?>
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3>Date Range Availability Check</h3>
            </div>
            <div class="card-body">
                <div class="alert <?php echo $data['is_available'] == 'Available' ? 'alert-success' : 'alert-danger'; ?>">
                    <strong>Status:</strong> <?php echo $data['is_available']; ?>
                </div>
                
                <p><strong>Caregiver ID:</strong> <?php echo $data['caregiver_id']; ?></p>
                <p><strong>Date Range:</strong> <?php echo date('Y-m-d', strtotime($data['start_date'])); ?> to <?php echo date('Y-m-d', strtotime($data['end_date'])); ?></p>
                
                <?php if(!empty($data['schedules'])): ?>
                    <h4>Conflicting Schedules:</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['schedules'] as $schedule): ?>
                                <tr>
                                    <td><?php echo $schedule->id; ?></td>
                                    <td><?php echo $schedule->start_date_time; ?></td>
                                    <td><?php echo $schedule->end_date_time; ?></td>
                                    <td>
                                        <?php 
                                            $start = new DateTime($schedule->start_date_time);
                                            $end = new DateTime($schedule->end_date_time);
                                            $diff = $start->diff($end);
                                            echo $diff->days . ' days';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        No conflicting schedules found in this date range.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if(isset($data['shedules']) && !isset($data['start_date'])): ?>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Schedules <?php echo isset($data['date']) ? 'for ' . date('Y-m-d', strtotime($data['date'])) : ''; ?></h3>
            </div>
            <div class="card-body">
                <?php if(!empty($data['shedules'])): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Schedule ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <?php if(isset($data['shedules'][0]->shift)): ?>
                                    <th>Shift</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['shedules'] as $schedule): ?>
                                <tr>
                                    <td><?php echo $schedule->id; ?></td>
                                    <td><?php echo $schedule->start_date_time ?? $schedule->sheduled_date; ?></td>
                                    <td><?php echo $schedule->end_date_time ?? ''; ?></td>
                                    <?php if(isset($schedule->shift)): ?>
                                        <td><?php echo $schedule->shift; ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">
                        No schedules found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
