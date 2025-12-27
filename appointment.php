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
                        <form action="save_appointment.php" method="POST">

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




<?php include 'footer.php'; ?>