<?php include 'header.php'; ?>

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
                        <form id="appointmentForm" method="POST" action="save_appointment.php" class="row g-3">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter Your Name">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required placeholder="Email">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="phone" class="form-control" required placeholder="Number">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Select Date</label>
                                <input
                                    type="date"
                                    id="appointment_date"
                                    name="appointment_date"
                                    min="<?= date('Y-m-d') ?>"
                                    class="form-control"
                                    required>
                            </div>

                            <div id="slotContainer" class="col-md-12 mb-3">
                                <label class="form-label">Select Time Slot</label>
                                <select id="time_slot" name="time_slot" class="form-select" required>
                                    <option value="">-- First Select Date --</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" placeholder="Message" rows="4"></textarea>
                            </div>

                            <div class="col-12">
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
    document.getElementById('appointment_date').addEventListener('change', function () {
        const date = this.value;
        const slotSelect = document.getElementById('time_slot');

        if (!date) {
            slotSelect.innerHTML = '<option value="">-- First Select Date --</option>';
            return;
        }

        slotSelect.innerHTML = '<option value="">Loading...</option>';

        fetch('get_slots.php?date=' + encodeURIComponent(date))
            .then(response => response.json())
            .then(data => {
                if (data.isHoliday && data.type === 'fullday') {
                    alert('Holiday: ' + data.reason);
                    slotSelect.innerHTML = '<option value="">No Slots Available</option>';
                    return;
                }

                if (data.isHoliday && data.reason) {
                    alert('Note: ' + data.reason);
                }

                if (!Array.isArray(data.slots) || data.slots.length === 0) {
                    slotSelect.innerHTML = '<option value="">No Slots Available</option>';
                    return;
                }

                let html = '<option value="">--Select Slot--</option>';

                data.slots.forEach(slot => {
                    const disabled = slot.available <= 0 ? 'disabled' : '';
                    const text = slot.available <= 0
                        ? `${slot.time} (FULL)`
                        : `${slot.time} (${slot.available} Slots Available)`;

                    html += `<option ${disabled} value="${slot.time}">${text}</option>`;
                });

                slotSelect.innerHTML = html;
            })
            .catch(() => {
                slotSelect.innerHTML = '<option value="">Error loading slots</option>';
            });
    });
</script>

<?php include 'footer.php'; ?>
