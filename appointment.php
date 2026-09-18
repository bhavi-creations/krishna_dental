<?php
include './db.connection/db_connection.php';

$selected_date = date('Y-m-d');
$slots = [
    "9:00 AM - 10:00 AM",
    "10:00 AM - 11:00 AM",
    "11:00 AM - 12:00 PM",
    "12:00 PM - 01:00 PM",
    "01:00 PM - 02:00 PM",
    "02:00 PM - 03:00 PM",
    "03:00 PM - 04:00 PM",
    "04:00 PM - 05:00 PM",
    "05:00 PM - 06:00 PM",
    "06:00 PM - 07:00 PM",
    "07:00 PM - 08:00 PM",
    "08:00 PM - 09:00 PM"
];
?>

<?php include 'header.php'; ?>

<img src="./assets/img/bg/appointment_1.png" alt="about" class="img-fluid">

<section class="dsdl-hero text-center" style="position: relative; height: 40vh; background-image: url('images1/about-bg.jpg'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4);"></div>
    <h1 style="position: relative; z-index: 2; color: white; font-size: 2.5rem; font-weight: bold; text-transform: uppercase;">Appointment</h1>
</section>

<section class="my-5 card_wrapper">
    <div class="container">
        <h1 class="text-center">Appointment Form</h1>
        <div class="row">
            <div class="col-12">
                <form id="appointmentForm" method="POST" action="save_appointment.php" class="row appointment-form mx-auto">

                    <div class="col-md-6 mb-4">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter your name">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="example@email.com">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label>Contact Number</label>
                        <input type="text" name="phone" class="form-control" required placeholder="+91 XXXXX XXXXX">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label>Select Date</label>
                        <input type="date" id="appointment_date" name="appointment_date" min="<?= date('Y-m-d') ?>" class="form-control" required>
                    </div>

                    <div id="slotContainer" class="col-md-12 mb-4">
                        <label>Select Time Slot</label>
                        <select id="time_slot" name="time_slot" class="form-control" required>
                            <option value="">-- First Select Date --</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label>Message (Optional)</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Any additional information..."></textarea>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('appointment_date').addEventListener('change', function() {
    const date = this.value;
    const slotSelect = document.getElementById('time_slot');
    
    if (!date) return;
    
    slotSelect.innerHTML = '<option>Loading...</option>';

    fetch('get_slots.php?date=' + date + '&t=' + new Date().getTime())
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.isHoliday && data.type === 'fullday') {
                alert("Holiday: " + data.reason);
                slotSelect.innerHTML = '<option value="">No Slots Available</option>';
                return;
            }

            if (data.isHoliday) {
                alert("Note: " + data.reason);
            }

            let html = '<option value="">--Select Slot--</option>';

            if (data.slots && Array.isArray(data.slots)) {
                if (data.slots.length === 0) {
                    html = '<option value="">No Slots Available for this day</option>';
                } else {
                    data.slots.forEach(s => {
                        let dis = s.available <= 0 ? 'disabled' : '';
                        let text = s.available <= 0 ?
                            `${s.time} (FULL)` :
                            `${s.time} (${s.available} Slots Available)`;

                        html += `<option ${dis} value="${s.time}">${text}</option>`;
                    });
                }
            } else {
                html = '<option value="">Error: Invalid slots format from server</option>';
            }

            slotSelect.innerHTML = html;
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            slotSelect.innerHTML = '<option value="">Error loading slots</option>';
        });
});
</script>

<?php include 'footer.php'; ?>
