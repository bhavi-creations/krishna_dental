<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['form_time'] = time();
include './db.connection/db_connection.php';
include 'header.php';
?>
<style>
    .appointment-card {
        border-radius: 18px;
        overflow: hidden;
    }

    .appointment-header {
        background: linear-gradient(135deg, #0d6efd, #084298);
        padding: 20px;
    }

    .book_slot {
        color: #fff;
        margin-bottom: 4px;
    }

    .appointment-card .form-control,
    .appointment-card .form-select {
        border-radius: 10px;
        padding: 10px 12px;
    }
</style>

<div class="breadcumb-wrapper">
    <div class="parallax" data-parallax-image="assets/img/about/krishnadentacure_appointment_slider.png"></div>
    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Appointment</h1>
            <div class="breadcumb-menu-wrap">
                <i class="far fa-home-lg"></i>
                <ul class="breadcumb-menu">
                    <li><a href="home.php">Home</a></li>
                    <li class="active">Appointment</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="appointment-wrapper space">
    <div class="container">
        <div class="row gx-40 align-items-center">
            <div class="col-lg-6 d-none d-lg-block">
                <img src="assets/img/about/kroshnadentacure_appointment_image.png" class="img-fluid" alt="Appointment">
            </div>

            <div class="col-lg-6 mb-40 mb-xl-0 wow fadeInUp" data-wow-delay="0.3s">
                <div class="card shadow-lg border-0 appointment-card">
                    <div class="appointment-header text-center text-white">
                        <h4 class="book_slot">Book Your Appointment</h4>
                        <small>Krishna Dental Care</small>
                    </div>

                    <div class="card-body p-4 bg-white">
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

                            <div class="g-recaptcha mb-2" data-sitekey="6Ldws0ktAAAAAD1Y2Q8PZa6aKCMKeqiHAK86IBhr"></div>
                            <br>

                            <div style="display:none;">
                                <input type="text" name="website" autocomplete="off">
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer text-center bg-light footer-text" style="font-size: 20px;">
                        &copy; Krishna Dental Care
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('appointment_date').addEventListener('change', function() {
    const date = this.value;
    const slotSelect = document.getElementById('time_slot');
    
    slotSelect.innerHTML = '<option value="">⏳ Loading Slots Please Wait...</option>';

    // Absolute Root Window Location configuration dynamically fixes path directory breaks
    const baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
    const targetUrl = baseUrl + '/get_slots.php?date=' + encodeURIComponent(date);

    fetch(targetUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status);
            }
            return response.text();
        })
        .then(rawData => {
            try {
                const data = JSON.parse(rawData);

                if (data.isHoliday && data.type === 'fullday') {
                    alert("Holiday: " + data.reason);
                    slotSelect.innerHTML = '<option value="">No Slots Available (Full Day Holiday)</option>';
                    return;
                }

                if (data.isHoliday) {
                    alert("Note: " + data.reason);
                }

                let html = '<option value="">--Select Slot--</option>';
                
                if (!data.slots || data.slots.length === 0) {
                    html = '<option value="">No Slots Configured For This Session</option>';
                } else {
                    data.slots.forEach(s => {
                        let dis = s.available <= 0 ? 'disabled' : '';
                        let text = s.available <= 0 ? 
                            `${s.time} (FULL)` : 
                            `${s.time} (${s.available} Slots Available)`;

                        html += `<option ${dis} value="${s.time}">${text}</option>`;
                    });
                }

                slotSelect.innerHTML = html;

            } catch (jsonError) {
                console.error("Raw Response from server side:", rawData);
                slotSelect.innerHTML = '<option value="">Server Error (Response is not clean JSON)</option>';
            }
        })
        .catch(fetchError => {
            console.error("Fetch failure root track details:", fetchError);
            slotSelect.innerHTML = '<option value="">Error loading slots. Try again.</option>';
        });
});
</script>

<?php include 'footer.php'; ?>