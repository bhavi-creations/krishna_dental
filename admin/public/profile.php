<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Krishnadentacure </title>

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
                <?php

                include '../../db.connection/db.php';
                $id = $_SESSION['user_id'];
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? ");
                $stmt->execute([$id]);
                $user = $stmt->fetch();


                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="row justify-content-between">

                     

                        <div class="    mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Profile </h1>

                        </div>

                        <!-- <div class="  mb-4">
                            <button class="h3 mb-0   btn btn-primary btn-user">Edit </button>
                        
                        </div> -->
                    </div>



                    <form class="user" action="profileUpdate.php" method="POST">
                        <div class="form-group row">
                            <div class="col-md-6 mb-3 ">
                                <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="User Name" value=<?php echo $user['first_name'];  ?>>
                            </div>
                            <div class="col-md-6  mb-3 ">
                                <input type="text" class="form-control form-control-user" id="exampleLastName" name="last_name" placeholder="Last Name" value=<?php echo $user['last_name'];  ?>>
                            </div>


                            <div class="  col-md-6  mb-3 ">
                                <input type="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" name='email' value=<?php echo $user['email'];  ?>>
                            </div>

                            <div class="col-md-6 mb-3  ">
                                <input type="password" class="form-control form-control-user" placeholder="Password" id="password" name="password" required>
                            </div>
                            <button type="reset" class="btn btn-danger mx-4">Clear</button><button type="submit" class="btn btn-success">Submit</button>
                        </div>

                    </form>
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
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
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