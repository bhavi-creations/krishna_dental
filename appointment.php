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


                <form action="appointmentform.php" method="post" role="form" class="php-email-form form-wrap1 shadow-none mb-0" data-bg-color="#f3f6f7">
                    <div class="form-title-box bg-title" data-bg-src="assets/img/bg/bg-shape-9.jpg">
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <h2 class="h4 mb-2 text-white">Book An Appointment</h2>
                            </div>
                            <div class="col-auto d-none d-sm-block">
                                <a href="tel:0088123456789" class="ripple-icon style2"><i class="fas fa-phone"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="form-box">
                        <div class="row">

                            <div class="col-lg-6 form-group">
                                <input type="text" class="form-control  style3" placeholder="Your Name" name="name">
                                <i class="fal small fa-user"></i>
                            </div>

                            <div class="col-lg-6 form-group">
                                <input type="number" class="form-control  style3" placeholder="Your Phone" name="phone">
                                <i class="fal small fa-phone"></i>
                            </div>


                            <div class="col-lg-6 form-group">

                                <select  name="doctor" class="form-select style3">
                                    <option hidden disabled selected>Choose Doctor</option>
                                    <option value="Dr. B. Suresh Kumar"> Dr. B. Suresh Kumar</option>
                                    <option value="Dr. B Ratna Vineela">Dr. B Ratna Vineela</option>
                                    <option value="Dr. B Sai Sruthi">Dr. B Sai Sruthi</option>
                                    <option value="Dr. G Niharika">Dr. G Niharika</option>
                                    <option value="Dr. Jyothsna Kalepu">Dr. Jyothsna Kalepu</option>
                                    <option value="Dr. Malathi Chakravarthy">Dr. Malathi Chakravarthy</option>
                                    <option value="Dr. M Ganesh ">Dr. M Ganesh </option>
                                    <option value="Dr. R. SriLekhya">Dr. R. SriLekhya</option>
                                    
                                </select>

                            </div>



                            <div class="col-lg-6 form-group">

                                <select name="department" class="form-select style3"  >
                                    <option  hidden disabled selected>Select Service</option>
                                    <option value="Root Canal"> Root Canal</option>
                                    <option value="Wisdom Tooth Removal"> Wisdom Tooth Removal </option>
                                    <option value="Bad Breath Treatment">Bad Breath Treatment </option>
                                    <option value="Gum Treatment">Gum Treatment</option>
                                    <option value="Teeth Cleaning">General Surgery </option>
                                    <option value="Orthodontic Treatment">Orthodontic Treatment</option>
                                    <option value="Dental Crown & Bridge">Dental Crown & Bridge</option>
                                    <option value="Dental Veneers">Dental Veneers</option>
                                    <option value="Smile Makeover">Smile Makeover</option>
                                    <option value="Teeth Whitening"> Teeth Whitening</option>
                                    <option value="Dental Implants"> Dental Implants</option>
                                    <option value="Dentures"> Dentures</option>
                                    <option value="Fluoride Application & Dental Sealant"> Fluoride Application & Dental Sealant</option>
                                    <option value="Full Mouth Rehabilitation Treatment"> Full Mouth Rehabilitation Treatment</option>


                                </select>

                            </div>
                            <div class="col-lg-6 form-group">
                                <input type="text" class="dateTime-pick form-control  style3" placeholder="Select Date & Time" name="date">
                                <i class="fal small fa-calendar-alt"></i>
                            </div>
                            <div class="col-lg-6 form-group">
                                <input type="email" class="form-control  style3" placeholder="Email Address" name="email">
                                <i class="fal small fa-envelope"></i>
                            </div>





                            <div class="col-xl-12 text-center">
                                <button type="submit" class="vs-btn style2">Make Appointment<i class="far fa-calendar-alt"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
</section>





<?php include 'footer.php'; ?>