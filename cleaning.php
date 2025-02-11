<?php include 'header.php'; ?>

<div class="breadcumb-wrapper ">
    <div class="parallax" data-parallax-image="assets/img/about/krishnadentacure_slider_teethcleaning.png"></div>

    <div class="container z-index-common">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Teeth Cleaning</h1>
            <div class="breadcumb-menu-wrap">
                <i class="far fa-home-lg"></i>
                <ul class="breadcumb-menu">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Teeth Cleaning</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="vs-service-wrapper space-top space-md-bottom">
    <div class="container">

        <h1 class="text-center mb-5">
            Teeth Cleaning in Rajahmundry <br> Brighten Your Smile with Expert Care

        </h1>
        <p class="fs-md text-title mb-4 pb-2 text-center">
            At Krishna Dental Cure, we offer professional teeth cleaning in Rajahmundry to remove plaque, tartar, and stains, ensuring a healthy and radiant smile.</p>

      


        <div class="row serice_space_div">


            <div class="col-12 col-md-8   service_text_padding">
            <h3>Step 1: Plaque and Tartar Removal</h3>
        <p>Our experts use ultrasonic scalers to gently remove plaque and tartar buildup from your teeth and gum line.</p>

     
            </div>

            <div class="col-12 col-md-4 service_text_padding_img  ">
                <img src="assets/img/services_steps/krishnadentacure_services_teeth_cleaning_1.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>
        </div>

        <div class="row  serice_space_div">

            <div class="col-12 col-md-4 service_text_padding_img order-1 order-md-0">

                <img src="assets/img/services_steps/krishnadentacure_services_teeth_cleaning_2.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

            </div>
            <div class="col-12 col-md-8 service_text_padding  order-0 order-md-1">
            <h3>Step 2: Polishing and Stain Removal</h3>
        <p>A specialized polishing paste is used to remove surface stains, leaving your teeth smooth and shiny.</p>


            </div>
        </div>




        <div class="row  serice_space_div">


            <div class="col-12 col-md-8 service_text_padding">

          
        <h3>Step 3: Fluoride Treatment for Protection</h3>
        <p>A fluoride gel is applied to strengthen enamel, protect against cavities, and keep your teeth healthy.</p>


            </div>

            <div class="col-12 col-md-4 service_text_padding_img">
                <img src="assets/img/services_steps/krishnadentacure_services_teeth_cleaning_3.png" alt="Service Image" class=" my-2 img-fluid service_border_radius_images">

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
                            <img src="assets/img/service_sliders" alt="Team Area" class="w-100">
                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders" alt="Team Area" class="w-100">

                        </div>

                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="team-card">
                        <div class="team-head">
                            <img src="assets/img/service_sliders" alt="Team Area" class="w-100">

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