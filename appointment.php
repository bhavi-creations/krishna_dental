 <?php
    include './db.connection/db_connection.php';


    $selected_date = date('Y-m-d');
    $slots = [

        "10:00 AM - 11:00 AM",
        "11:00 AM - 12:00 PM",
        "12:00 PM - 01:00 PM",
        "01:00 PM - 02:00 PM",
        "02:00 PM - 03:00 PM",
        "03:00 PM - 04:00 PM",
        "04:00 PM - 05:00 PM",
        "05:00 PM - 06:00 PM",
        "06:00 PM - 07:00 PM",
        "07:00 PM - 08:00 PM"
    ];

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
                        <form id="appointmentForm"
                 method="POST"
                 action="save_appointment.php"
                 class="row appointment-form mx-auto">

                 <div class="col-md-6 mb-4">
                     <label>Name</label>
                     <input type="text" name="name" class="form-control" required placeholder="Enter Your Name">
                 </div>

                 <div class="col-md-6 mb-4">
                     <label>Email</label>
                     <input type="email" name="email" class="form-control" required placeholder="Email">
                 </div>

                 <div class="col-md-6 mb-4">
                     <label>Contact Number</label>
                     <input type="text" name="phone" class="form-control" required placeholder="Number">
                 </div>

                 <div class="col-md-6 mb-4">
                     <label>Select Date</label>
                     <input type="date"
                         id="appointment_date"
                         name="appointment_date"
                         min="<?= date('Y-m-d') ?>"
                         class="form-control"
                         required>
                 </div>

                 <div id="slotContainer" class="col-md-12 mb-4">
                     <label>Select Time Slot</label>
                     <select id="time_slot" name="time_slot" class="form-control" required>
                         <option value="">-- First Select Date --</option>
                     </select>
                 </div>

                 <div class="col-md-12 mb-4">
                     <label>Message</label>
                     <textarea name="message" class="form-control" placeholder="Message"></textarea>
                 </div>

                 <div class="col-md-12">
                     <button type="submit" class="btn appointment-btn btn-lg w-100">
                         Book Appointment
                     </button>
                 
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
