<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Krishnadentacure - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        include 'sidebar.php';
        ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                include 'navbar.php';
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->

                    <!-- Content Row -->


                    <!-- Content Row -->
                    <style>
                        .card-custom {
                            margin: 6px;
                            /* Reset margin to prevent extra space */
                        }
                    </style>
                    </head>

                    <body>
                        <div class="container">
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <h2 class="h2 mb-0 text-info mx-2"> Published Blogs</h2>
                                <a href="newBlog.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Create Blog</a>

                            </div>
                            <div class='row row-custom no-gutters'>
    <?php
    // Database connection (replace with your actual database connection details)
    include '../../db.connection/db_connection.php';

    // Fetch blog data ordered by created_at in descending order
    $sql = "SELECT id, title, main_content, main_image FROM blogs ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Determine image to display (use main_image if exists)
            $image_path = !empty($row['main_image']) ? "../uploads/photos/{$row['main_image']}" : "https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png";

            echo "
            <div class='col-12 col-md-4 col-custom'>
                <div class='card card-custom'>
                    <img src='{$image_path}' class='card-img-top' alt='Blog Image'>
                    <div class='card-body'>
                        <h5 class='card-title' style='color:black;'>{$row['title']}</h5>
                        <p class='card-text'>" . substr(strip_tags($row['main_content']), 0, 100) . "...</p>
                        <div class='row'>
                            <a href='editBlog.php?id={$row['id']}' class='btn btn-warning col-xl-4 mx-3 my-2'>Edit Blog</a>
                            <a href='deleteBlog.php?id={$row['id']}' class='col-xl-4 btn btn-danger mx-3 my-2'>Delete</a>
                        </div>
                    </div>
                </div>
            </div>
            ";
        }
    } else {
        echo "<p>No blog posts found.</p>";
    }

    $conn->close();
    ?>
</div>










                        </div>

                        <!-- <div class="col-12 col-md-4 col-custom">
                                        <div class="card card-custom">
                                            <img style='height:200px;  object-fit: cover;'
                                                src="https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png"
                                                class="card-img-top p-2" alt="...">

                                            <div class="card-body">
                                                <h5 class="card-title" style='color:black;'>Blog title</h5>
                                                <p class="card-text">Some quick example text to build on the card title
                                                    and make up the bulk of the card's content.</p>
                                                <div class='row'>
                                                    <a href="editBlog.php"
                                                        class="btn btn-warning col-xl-4 mx-3 my-2">Edit Blog</a> <a
                                                        href="#" class="col-xl-4 btn btn-danger mx-3 my-2">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-custom">
                                        <div class="card card-custom">
                                            <img style='height:200px;  object-fit: cover;'
                                                src="https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png"
                                                class="card-img-top p-2" alt="...">

                                            <div class="card-body">
                                                <h5 class="card-title" style='color:black;'>Blog title</h5>
                                                <p class="card-text">Some quick example text to build on the card title
                                                    and make up the bulk of the card's content.</p>
                                                <div class='row'>
                                                    <a href="editblog.php"
                                                        class="btn btn-warning col-xl-4 mx-3 my-2">Edit Blog</a> <a
                                                        href="#" class="col-xl-4 btn btn-danger mx-3 my-2">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-custom">
                                        <div class="card card-custom">
                                            <img style='height:200px;  object-fit: cover;'
                                                src="https://mailrelay.com/wp-content/uploads/2018/03/que-es-un-blog-1.png"
                                                class="card-img-top p-2" alt="...">

                                            <div class="card-body">
                                                <h5 class="card-title" style='color:black;'>Blog title</h5>
                                                <p class="card-text">Some quick example text to build on the card title
                                                    and make up the bulk of the card's content.</p>
                                                <div class='row'>
                                                    <a href="editblog.php"
                                                        class="btn btn-warning col-xl-4 mx-3 my-2">Edit Blog</a> <a
                                                        href="#" class="col-xl-4 btn btn-danger mx-3 my-2">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                        <!-- Pie Chart -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <div class="footer-widget__copyright">
                            <p class="mini_text" style="color:black"> ©2024 Krishnadentacure . All Rights Reserved. Designed &
                                Developed by <a href="https://bhavicreations.com/" target="_blank" style="text-decoration: none;color:black">Bhavi
                                    Creations</a></p>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>