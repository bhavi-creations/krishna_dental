<?php include 'header.php'; ?>

<div class="breadcumb-wrapper ">
    <div class="parallax" data-parallax-image="assets/img/about/krishnadentacure_slider_Smile_Makeover.png"></div>

    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Smile Makeover</h1>
            <div class="breadcumb-menu-wrap">
                <i class="far fa-home-lg"></i>
                <ul class="breadcumb-menu">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Smile Makeover</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<img src="assets/img/special/services_page_bg.png" class="special_teeth_service img-fluid" alt="">
<img src="assets/img/special/smile.png" class="special_teeth_edge_left img-fluid" alt="">

<section class="vs-service-wrapper space-top space-md-bottom">
    <div class="container">

        <h1 class="text-center mb-5">


            Smile Makeover in Rajahmundry <br> Achieve a Perfect, Radiant Smile</h1>

        <p class="fs-md text-title mb-4 pb-2 text-center">At Krishna Dental Cure, we offer customized smile makeovers in Rajahmundry to enhance your smile using advanced cosmetic and restorative treatments.</p>






        <div class="row serice_space_div">


            <div class="col-12 col-md-8   service_text_padding">
                <h3>Step 1: Smile Assessment and Planning</h3>
                <p>We evaluate your teeth, gums, and facial structure to design a personalized smile makeover plan.</p>

            </div>

            <div class="col-12 col-md-4 service_text_padding_img  ">
                <img src="assets/img/services_steps/krishnadentacure_services_smile_akeover_1.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>
        </div>

        <div class="row  serice_space_div">

            <div class="col-12 col-md-4 service_text_padding_img order-1 order-md-0">

                <img src="assets/img/services_steps/krishnadentacure_services_smile_akeover_2.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>
            <div class="col-12 col-md-8 service_text_padding  order-0 order-md-1">
                <h3>Step 2: Cosmetic and Restorative Treatments</h3>
                <p>Depending on your needs, treatments may include teeth whitening, veneers, crowns, or orthodontics.</p>


            </div>
        </div>




        <div class="row  serice_space_div">


            <div class="col-12 col-md-8 service_text_padding">


                <h3>Step 3: Final Adjustments and Smile Reveal</h3>
                <p>We ensure proper fit and aesthetics, making final refinements for a stunning and confident smile.</p>

            </div>

            <div class="col-12 col-md-4 service_text_padding_img">
                <img src="assets/img/services_steps/krishnadentacure_services_smile_akeover_3.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>

        </div>
    </div>


    </div>
</section>
<img src="assets/img/special/stand.png" class="special_teeth_service_left img-fluid" alt="">



<section class="vs-team-wrapper space-md-bottom">
    <div class="container">

        <!-- Swiper Container -->
        <div class="swiper-container team-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_Smile_Makeover_1.png" alt="Team Area" class="w-100">
                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_Smile_Makeover_2.png" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_Smile_Makeover_3.png" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_Smile_Makeover_4.png" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<img src="assets/img/special/slider_edge.jpg" class="special_teeth_edge img-fluid" alt="">



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