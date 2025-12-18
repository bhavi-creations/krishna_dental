<?php
include '../../db.connection/db_connection.php'; // DB connection

// Fetch all services from services table
$services_result = $conn->query("SELECT id, service_name FROM services ORDER BY service_name ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Krishna dental hospital - Dashboard</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Quill CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">

        <?php include 'sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'navbar.php'; ?>

                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">CREATE BLOG</h1>

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <form id="addblogform" action="addBlog.php" method="POST" enctype="multipart/form-data">

                                <!-- ================= ENGLISH ================= -->
                                <h4 class="text-primary">English Content</h4>
                                <hr>

                                <div class="mb-3">
                                    <label>English Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>


                                <div class="mb-3">
                                    <!-- <label class="form-label text-primary">Select Service</label> -->
                                    <select id="service" name="service" class="form-control" required>
                                        <option value="">Select a Service</option>
                                        <?php if ($services_result->num_rows > 0): ?>
                                            <?php while ($service = $services_result->fetch_assoc()): ?>
                                                <!-- Use service_name as value instead of id -->
                                                <option value="<?= htmlspecialchars($service['service_name']) ?>">
                                                    <?= htmlspecialchars($service['service_name']) ?>
                                                </option>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label>English Main Content</label>
                                    <div id="mainEditor" style="height:200px;"></div>
                                    <input type="hidden" name="main_content" id="mainContentData">
                                </div>

                                <div class="mb-3">
                                    <label>English Full Content</label>
                                    <div id="fullEditor" style="height:300px;"></div>
                                    <input type="hidden" name="full_content" id="fullContentData">
                                </div>

                                <!-- ================= TELUGU ================= -->
                                <h4 class="text-primary mt-5">Telugu Content</h4>
                                <hr>

                                <div class="mb-3">
                                    <label>Telugu Title</label>
                                    <input type="text" class="form-control" name="telugu_title" required>
                                </div>

                                <div class="mb-3">
                                    <label>Telugu Main Content</label>
                                    <div id="teluguMainEditor" style="height:200px;"></div>
                                    <input type="hidden" name="telugu_main_content" id="teluguMainData">
                                </div>

                                <div class="mb-3">
                                    <label>Telugu Full Content</label>
                                    <div id="teluguFullEditor" style="height:300px;"></div>
                                    <input type="hidden" name="telugu_full_content" id="teluguFullData">
                                </div>

                                <!-- ================= IMAGES ================= -->
                                <h4 class="text-primary mt-5">Images</h4>
                                <hr>

                                <div class="mb-3">
                                    <label>Main Image</label>
                                    <input type="file" class="form-control" name="main_image">
                                </div>

                                <div class="mb-3">
                                    <label>Section 1 Image</label>
                                    <input type="file" class="form-control" name="section1_image">
                                </div>

                                <button type="reset" class="btn btn-danger">Clear</button>
                                <button type="submit" class="btn btn-success">Publish</button>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        const quillMain = new Quill("#mainEditor", {
            theme: "snow"
        });
        const quillFull = new Quill("#fullEditor", {
            theme: "snow"
        });
        const tqMain = new Quill("#teluguMainEditor", {
            theme: "snow"
        });
        const tqFull = new Quill("#teluguFullEditor", {
            theme: "snow"
        });

        document.querySelector("#addblogform").onsubmit = function() {
            document.querySelector("#mainContentData").value = quillMain.root.innerHTML;
            document.querySelector("#fullContentData").value = quillFull.root.innerHTML;
            document.querySelector("#teluguMainData").value = tqMain.root.innerHTML;
            document.querySelector("#teluguFullData").value = tqFull.root.innerHTML;
        };
    </script>

</body>

</html>



<!-- <label class="form-label text-primary">Select Service</label>
                                        <select name="service" class="form-control" required>
                                            <option value="">Select a Service</option>
                                            <option value="Root Canal">Root Canal</option>
                                            <option value="Wisdom Tooth Removal">Wisdom Tooth Removal</option>
                                            <option value="Bad Breath Treatment">Bad Breath Treatment</option>
                                            <option value="Gum Treatment">Gum Treatment</option>
                                            <option value="Teeth Cleaning">Teeth Cleaning</option>
                                            <option value="Orthodontic Treatment">Orthodontic Treatment</option>
                                            <option value="Dental Crown & Bridge">Dental Crown & Bridge</option>
                                            <option value="Invisible Aligners">Invisible Aligners</option>
                                            <option value="Dental Veneers">Dental Veneers</option>
                                            <option value="Smile Makeover">Smile Makeover</option>
                                            <option value="Teeth Whitening">Teeth Whitening</option>
                                            <option value="Dental Implants">Dental Implants</option>
                                            <option value="Dentures">Dentures</option>
                                            <option value="Fluoride Application & Dental Sealant">Fluoride Application & Dental Sealant</option>
                                            <option value="Full Mouth Rehabilitation Treatment">Full Mouth Rehabilitation Treatment</option>
                                        </select> -->