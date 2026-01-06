<?php include 'header.php'; ?>

<div class="breadcumb-wrapper ">
    <div class="parallax" data-parallax-image="assets/img/about/krishnadentacure_slider_crownandbridges.png"></div>

    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Dental Crown & Bridge</h1>
            <div class="breadcumb-menu-wrap">
                <i class="far fa-home-lg"></i>
                <ul class="breadcumb-menu">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Dental Crown & Bridge</li>
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

            Dental Crown & Bridge in Rajahmundry <br> Restore Your Smile with Confidence</h1>

        <p class="fs-md text-title mb-4 pb-2 text-center">


            At Krishna Dental Cure, we provide high-quality dental crowns and bridges in Rajahmundry to restore damaged or missing teeth, ensuring durability and a natural look.</p>




        <div class="row serice_space_div">


            <div class="col-12 col-md-8   service_text_padding">
                <h3>Step 1: Tooth Preparation and Impressions</h3>
                <p>The affected tooth is reshaped, and impressions are taken to create a custom-fitted crown or bridge.</p>

            </div>

            <div class="col-12 col-md-4 service_text_padding_img  ">
                <img src="assets/img/services_steps/krishnadentacure_services_crown_bridges_1.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>
        </div>

        <div class="row  serice_space_div">

            <div class="col-12 col-md-4 service_text_padding_img order-1 order-md-0">

                <img src="assets/img/services_steps/krishnadentacure_services_crown_bridges_2.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>
            <div class="col-12 col-md-8 service_text_padding  order-0 order-md-1">


                <h3>Step 2: Temporary and Permanent Placement</h3>
                <p>A temporary crown or bridge is placed while the permanent one is crafted for a perfect fit and function.</p>


            </div>
        </div>




        <div class="row  serice_space_div">


            <div class="col-12 col-md-8 service_text_padding">

                <h3>Step 3: Final Fitting and Adjustment</h3>
                <p>The permanent crown or bridge is cemented securely, ensuring comfort, aesthetics, and long-lasting strength.</p>


            </div>

            <div class="col-12 col-md-4 service_text_padding_img">
                <img src="assets/img/services_steps/krishnadentacure_services_crown_bridges_3.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

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
                            <img src="assets/img/service_sliders/krishnadentacure_Dental_Crown_Bridge_1.png" alt="Team Area" class="w-100">
                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_Dental_Crown_Bridge_2.png" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_Dental_Crown_Bridge_3.png" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders/krishnadentacure_Dental_Crown_Bridge_4.png" alt="Team Area" class="w-100">

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