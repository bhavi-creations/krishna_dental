 <?php
session_start();
$_SESSION['form_time'] = time();
?>
 
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

 <section class="dsdl-hero text-center" style="position: relative; height: 40vh; background-image: url('images1/about-bg.jpg'); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center;">
     <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4);"></div>
     <h1 style="position: relative; z-index: 2; color: white; font-size: 2.5rem; font-weight: bold; text-transform: uppercase;">Appointment</h1>
 </section>


 <section class=" my-5 card_wrapper">
     <div class="container">
         <h1 class="text-center">Appointment Form</h1>
         <div class="row">
             <div class="col-md-6">
                 <form id="appointmentForm"
                     method="POST"
                     action="save_appointment.php"
                     class="row appointment-form mx-auto">

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
                         <label>Message (Optional)</label>
                         <textarea name="message" class="form-control" rows="4"
                             placeholder="Any additional information..."></textarea>
                     </div>

                     <div class="g-recaptcha mb-2" data-sitekey="6Ldws0ktAAAAAD1Y2Q8PZa6aKCMKeqiHAK86IBhr"></div>
                     <br>


                     <div style="display:none;">
    <input type="text" name="website" autocomplete="off">
</div>



                     
                     <div class="col-md-12">
                         <button type="submit" class="btn btn-primary w-100">
                             Book Appointment
                         </button>
                     </div>

                 </form>
             </div>
             <div class="col-md-6">
                 <div class="google-map" data-aos="fade-right" data-aos-delay="100" style="flex: 1; min-width: 45%; background: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                     <iframe src="https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d30334.605525010476!2d83.38071781801781!3d18.12583900511469!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e6!4m3!3m2!1d18.136257!2d83.3896213!4m5!1s0x3a3be551bb580c3f%3A0xa15d74cd17e1d939!2sD.%20NO.%2022-1-10%2C%20Ist%20Floor%2C%20Apple%20Dental%20Specialities%2C%20A.G%20Complex%2C%20Ananda%20Gajapathi%20Rd%2C%20Ambati%20Satram%20Area%2C%20Vizianagaram%2C%20Andhra%20Pradesh%20535002!3m2!1d18.115064399999998!2d83.4140839!5e0!3m2!1sen!2sin!4v1735639081429!5m2!1sen!2sin"
                         width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                     </iframe>
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