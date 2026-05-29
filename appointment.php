<?php
include './db.connection/db_connection.php';

// Initial Load కోసం (ఈ PHP పార్ట్ కేవలం డేట్ సెలెక్ట్ చేయనప్పుడు డిఫాల్ట్ కోసం)
$selected_date = date('Y-m-d');
$slots = [
    "10:30 AM - 11:30 AM",
    "11:30 AM - 12:30 PM",
    "12:30 PM - 01:30 PM",
    "01:30 PM - 02:30 PM",
    "04:00 PM - 05:00 PM",
    "05:00 PM - 06:00 PM",
    "06:00 PM - 07:00 PM",
    "07:00 PM - 08:00 PM",
    "08:00 PM - 08:30 PM"
];
?>


<?php include 'header.php'; ?>



<div class="breadcumb-wrapper ">
    <div class="parallax" data-parallax-image="assets/img/about/krishnadentacure_appointment_slider.png"></div>
    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Appointment</h1>
            <div class="breadcumb-menu-wrap">
                <i class="far fa-home-lg"></i>
                <ul class="breadcumb-menu">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Appointment</li>
                </ul>
            </div>
        </div>
    </div>
</div>




<!--==============================
    Appointment Form Area 
    ==============================-->
<section class="appointment-wrapper space">

    <div class="container">
        <div class="row gx-40">

            <div class="col-lg-6 d-none d-lg-block">
                <img src="assets/img/about/kroshnadentacure_appointment_image.png" class="img-fluid" alt="">
            </div>



            <div class="col-lg-6 mb-40 mb-xl-0 wow fadeInUp" data-wow-delay="0.3s">

                <div class="card shadow-lg border-0 appointment-card">

                    <div class="appointment-header text-center text-white " style="  background: linear-gradient(135deg, #0d6efd, #084298);
        padding: 20px; ">
                        <h4 class="book_slot" style=" color: white;">Book Your Appointment</h4>
                        <small>Krishna Dental Care</small>
                    </div>

                    <div class="card-body p-4 bg-white">
                        <!-- <form action="save_appointment.php" method="POST">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Patient Name</label>
                                <input type="text" name="patient_name" class="form-control" placeholder="Enter patient name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Appointment Date</label>
                                <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Select Time Slot</label>
                                <select name="appointment_time" id="appointment_time" class="form-select" required>
                                    <option value="">Choose Slot</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-book">
                                    Book Appointment
                                </button>
                            </div>

                        </form> -->

                        <form id="appointmentForm" method="POST" action="save_appointment.php" class="row">
                            <div class="mb-3 col-md-6">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter Your Name">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required placeholder="Email">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Contact Number</label>
                                <input type="text" name="phone" class="form-control" required placeholder="Number">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label>Select Date</label>
                                <input type="date" id="appointment_date" name="appointment_date"
                                    min="<?= date('Y-m-d') ?>" class="form-control" required>
                            </div>

                            <div id="slotContainer" class="col-md-12 mb-3">
                                <label>Select Time Slot</label>
                                <select id="time_slot" name="time_slot" class="form-control" required>
                                    <option value="">-- First Select Date --</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label>Message</label>
                                <textarea name="message" class="form-control" placeholder="Message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
                        </form>




                    </div>

                    <div class="card-footer text-center bg-light footer-text" style="font-size: 20px;">
                        © Krishna Dental Care
                    </div>

                </div>

            </div>


        </div>
    </div>
</section>

<script>
    /* ✅ 1 Hour Slots */
    const slots = [
        "10:00 - 11:00AM",
        "11:00 - 12:00PM",
        "12:00 - 01:00PM",
        "01:00 - 02:00PM",
        "02:00 - 03:00PM",
        "03:00 - 04:00PM",
        "04:00 - 05:00PM",
        "05:00 - 06:00PM",
        "06:00 - 07:00PM",
        "07:00 - 08:00PM",
        "08:00 - 09:00PM"
    ];

    document.getElementById("appointment_date").addEventListener("change", function() {

        let date = this.value;
        let slotSelect = document.getElementById("appointment_time");

        slotSelect.innerHTML = '<option value="">Loading slots...</option>';

        fetch("check_slots.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "date=" + date
            })
            .then(res => res.json())
            .then(data => {

                slotSelect.innerHTML = '<option value="">Choose Slot</option>';

                slots.forEach(slot => {

                    let bookedCount = data[slot] ?? 0;
                    let available = 3 - bookedCount;

                    let option = document.createElement("option");

                    if (available <= 0) {
                        option.text = slot + " (FULL)";
                        option.disabled = true;
                    } else {
                        option.text = slot + " (Available: " + available + ")";
                        option.value = slot;
                    }

                    slotSelect.appendChild(option);
                });
            });
    });
</script>

<script>
    document.getElementById("appointment_date").addEventListener("change", function() {

        let date = this.value;
        let slot = document.getElementById("appointment_time");

        slot.innerHTML = '<option>Loading...</option>';
        slot.style.display = "block";

        fetch("check_slots.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "date=" + date
            })
            .then(res => res.json())
            .then(data => {

                if (data.holiday) {
                    slot.style.display = "none";
                    alert("Clinic closed on selected date");
                    return;
                }

                slot.innerHTML = '<option value="">Choose Slot</option>';

                for (let s in data) {
                    let opt = document.createElement("option");

                    if (data[s].available <= 0) {
                        opt.text = s + " (FULL)";
                        opt.disabled = true;
                    } else {
                        opt.text = s + " (Available: " + data[s].available + ")";
                        opt.value = s;
                    }

                    slot.appendChild(opt);
                }
            });
    });
</script>

<script>
    document.getElementById('appointment_date').addEventListener('change', function() {
        const date = this.value;
        const slotSelect = document.getElementById('time_slot');
        slotSelect.innerHTML = '<option>Loading...</option>';

        fetch('get_slots.php?date=' + date)
            .then(r => r.json())
            .then(data => {

                if (data.isHoliday && data.type == 'fullday') {
                    alert("Holiday: " + data.reason);
                    slotSelect.innerHTML = '<option>No Slots Available</option>';
                    return;
                }

                if (data.isHoliday) {
                    alert("Note: " + data.reason);
                }

                let html = '<option value="">--Select Slot--</option>';

                data.slots.forEach(s => {
                    let dis = s.available <= 0 ? 'disabled' : '';
                    let text = s.available <= 0 ?
                        `${s.time} (FULL)` :
                        `${s.time} (${s.available} Slots Available)`;

                    html += `<option ${dis} value="${s.time}">${text}</option>`;
                });

                slotSelect.innerHTML = html;
            })
            .catch(() => {
                slotSelect.innerHTML = '<option>Error loading slots</option>';
            });
    });
</script>




<?php include 'footer.php'; ?>