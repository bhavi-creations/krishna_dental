<?php include 'header.php'; ?>

<div class="breadcumb-wrapper ">
    <div class="parallax" data-parallax-image="assets/img/about/krishnadentacure_rootcanal_slider.png"></div>

    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Root Canal</h1>
            <div class="breadcumb-menu-wrap">
                <i class="far fa-home-lg"></i>
                <ul class="breadcumb-menu">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Root Canal</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="vs-service-wrapper space-top space-md-bottom">
    <div class="container">

        <h1 class="text-center mb-5">

            Root Canal Treatment in Rajahmundry <br> Painless & Effective Dental Care</h1>
        </h1>
        <p class="fs-md text-title mb-4 pb-2 text-center">

            At Krishna Dental Cure, we offer advanced root canal treatment in Rajahmundry to save infected teeth and relieve pain. Our expert procedure restores your tooth’s function while ensuring a comfortable and painless experience.</p>



        <div class="row serice_space_div">


            <div class="col-12 col-md-8   service_text_padding">

                <h3>Step 1: Diagnosis and Preparation</h3>
                <p>Our specialists examine your tooth using X-rays to assess infection severity. The area is numbed for a pain-free experience before starting the procedure.</p>

            </div>

            <div class="col-12 col-md-4 service_text_padding_img  ">
                <img src="assets/img/services_steps/krishnadentacure_services_rootcanal_treatment_step1.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>
        </div>

        <div class="row  serice_space_div">

            <div class="col-12 col-md-4 service_text_padding_img order-1 order-md-0">

                <img src="assets/img/services_steps/krishnadentacure_services_rootcanal_treatment_step2.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>
            <div class="col-12 col-md-8 service_text_padding  order-0 order-md-1">

                <h3>Step 2: Cleaning and Disinfection</h3>
                <p>The infected pulp is removed, and the root canals are thoroughly cleaned and disinfected to eliminate bacteria and prevent further infection.</p>


            </div>
        </div>




        <div class="row  serice_space_div">


            <div class="col-12 col-md-8 service_text_padding">

                <h3>Step 3: Filling and Sealing</h3>
                <p>The cleaned canals are filled with biocompatible material and sealed to prevent reinfection. A dental crown may be placed for added protection and durability.</p>
            </div>

            <div class="col-12 col-md-4 service_text_padding_img">
                <img src="assets/img/services_steps/krishnadentacure_services_rootcanal_treatment_step3.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>

        </div>
    </div>


    </div>
</section>


<section class="vs-team-wrapper space-md-bottom">
    <div class="container">

        <!-- Swiper Container -->
        <div class="swiper-container team-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_service_slider _image _Root_Canal_1.png" alt="Team Area" class="w-100">
                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_service_slider _image _Root_Canal_2.png" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_service_slider _image _Root_Canal_3.png" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_service_slider _image _Root_Canal_4.png" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>



<!-- Swiper JS Initialization -->
<script>
    var swiper = new Swiper(".team-slider", {
        slidesPerView: 3, // Show exactly 3 slides
        spaceBetween: 30, // Adjust space between slides
        loop: true,
        centeredSlides: false, // Ensure it aligns properly
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            1024: {
                slidesPerView: 3
            },
            768: {
                slidesPerView: 2
            },
            0: {
                slidesPerView: 1
            }
        }
    });
</script>





<?php include 'footer.php'; ?>