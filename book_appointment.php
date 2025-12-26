<?php include 'header.php'; ?>


<style>
    .appointment-card {
        border-radius: 15px;
    }

    .appointment-header {
        background: linear-gradient(135deg, #0d6efd, #084298);
        padding: 20px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 10px;
    }

    .btn-book {
        border-radius: 30px;
        padding: 12px;
        font-size: 18px;
    }

    .footer-text {
        font-size: 14px;
    }

    .book_slot {
        color: white;
    }
</style>
</head>

<body>

    <section class="appointment-section w-100 mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">

                    <div class="card shadow-lg border-0 appointment-card">

                        <div class="appointment-header text-center text-white">
                            <h4 class="book_slot">Book Your Appointment</h4>
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
document.getElementById("appointment_date").addEventListener("change", function () {

    let date = this.value;
    let slot = document.getElementById("appointment_time");

    slot.innerHTML = '<option>Loading...</option>';
    slot.style.display = "block";

    fetch("check_slots.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
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