<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['form_time'] = time();
include './db.connection/db_connection.php';

// === API BLOCK INTERNAL TRIGGER ===
// Front-end date select chesinapudu dynamic requests ni ee block handle chesthundhi
if (isset($_GET['action']) && $_GET['action'] === 'fetch_slots' && isset($_GET['date'])) {
    header('Content-Type: application/json; charset=utf-8');
    $date = trim($_GET['date']);
    
    $slots_list = [
        "09:00 AM - 10:00 AM", "10:00 AM - 11:00 AM", "11:00 AM - 12:00 PM",
        "12:00 PM - 01:00 PM", "01:00 PM - 02:00 PM", "02:00 PM - 03:00 PM",
        "03:00 PM - 04:00 PM", "04:00 PM - 05:00 PM", "05:00 PM - 06:00 PM",
        "06:00 PM - 07:00 PM", "07:00 PM - 08:00 PM", "08:00 PM - 09:00 PM"
    ];

    $response_slots = [];

    foreach ($slots_list as $slot) {
        $total = 0;
        $stmtCheck = $conn->prepare("SELECT COUNT(*) as total FROM appointments WHERE appointment_date = ? AND time_slot = ?");
        if ($stmtCheck) {
            $stmtCheck->bind_param("ss", $date, $slot);
            $stmtCheck->execute();
            $res = $stmtCheck->get_result()->fetch_assoc();
            $total = isset($res['total']) ? (int)$res['total'] : 0;
            $stmtCheck->close();
        }

        $maxCapacity = 3;
        $availableSlots = $maxCapacity - $total;
        if ($availableSlots < 0) $availableSlots = 0;

        $response_slots[] = [
            'time' => $slot,
            'available' => $availableSlots
        ];
    }

    echo json_encode(['slots' => $response_slots]);
    exit;
}

include 'header.php';
?>

<style>
    .appointment-card { border-radius: 18px; overflow: hidden; }
    .appointment-header { background: linear-gradient(135deg, #0d6efd, #084298); padding: 20px; }
    .book_slot { color: #fff; margin-bottom: 4px; }
    .appointment-card .form-control { border-radius: 10px; padding: 10px 12px; }
</style>

<div class="container space my-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 appointment-card">
                <div class="appointment-header text-center text-white">
                    <h4 class="book_slot">Book Your Appointment</h4>
                    <small>Krishna Dental Care</small>
                </div>
                <div class="card-body p-4 bg-white">
                    <form id="appointmentForm" method="POST" action="save_appointment.php" class="row">
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Enter name">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required placeholder="name@email.com">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="phone" class="form-control" required placeholder="+91 XXXXX XXXXX">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Select Date</label>
                            <input type="date" id="appointment_date" name="appointment_date" min="<?= date('Y-m-d') ?>" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="form-label">Select Time Slot</label>
                            <select id="time_slot" name="time_slot" class="form-control" required>
                                <option value="">-- First Select Date --</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="form-label">Message (Optional)</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Additional details..."></textarea>
                        </div>

                        <div class="g-recaptcha mb-3" data-sitekey="6Ldws0ktAAAAAD1Y2Q8PZa6aKCMKeqiHAK86IBhr"></div>
                        
                        <div style="display:none;"><input type="text" name="website" autocomplete="off"></div>

                        <div class="col-md-12"><button type="submit" class="btn btn-primary w-100">Confirm Booking</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('appointment_date').addEventListener('change', function() {
    const date = this.value;
    const slotSelect = document.getElementById('time_slot');
    if(!date) return;

    slotSelect.innerHTML = '<option value="">⏳ Fetching live slots...</option>';

    // Appending action parameters directly to the same page template URL structure
    const currentCleanUrl = window.location.origin + window.location.pathname + '?action=fetch_slots&date=' + encodeURIComponent(date);

    fetch(currentCleanUrl)
        .then(res => {
            if (!res.ok) throw new Error('HTTP Status response validation crash: ' + res.status);
            return res.json(); 
        })
        .then(data => {
            let dropdownHtml = '<option value="">-- Select Available Slot --</option>';
            
            if (!data.slots || data.slots.length === 0) {
                dropdownHtml = '<option value="">No Slots Configured</option>';
            } else {
                data.slots.forEach(slotItem => {
                    let isFull = slotItem.available <= 0;
                    let disabledText = isFull ? 'disabled' : '';
                    let labelText = isFull ? `${slotItem.time} (FULL)` : `${slotItem.time} (${slotItem.available} Open)`;
                    dropdownHtml += `<option ${disabledText} value="${slotItem.time}">${labelText}</option>`;
                });
            }
            slotSelect.innerHTML = dropdownHtml;
        })
        .catch(err => {
            console.error("Fetch tracing error:", err);
            slotSelect.innerHTML = '<option value="">Error rendering server slots. Try again.</option>';
        });
});
</script>
<?php include 'footer.php'; ?>