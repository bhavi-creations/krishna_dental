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
        <?php include 'sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'navbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">CREATE BLOG</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-11">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-success">CREATE CONTENT</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <form style="color:black;" id="addblogform" action="addBlog.php" method="POST" enctype="multipart/form-data">

                                        <!-- Blog Title -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">ENTER TITLE</label>
                                            <input type="text" class="form-control text-grey-900" name="title" placeholder="Title" required>
                                        </div>

                                        <!-- Select Service -->
                                        <div class="filter-section mb-3">
                                            <label class="form-label text-primary">Select Service:</label>
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
                                            </select>
                                        </div>

                                        <!-- Main Content -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">ENTER MAIN CONTENT</label>
                                            <div id="mainEditor" style="height: 200px;"></div>
                                            <input type="hidden" name="main_content" id="mainContentData">
                                        </div>

                                        <!-- Main Image -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary my-2">Choose Main Image</label>
                                            <input class="form-control" name="main_image" type="file">
                                        </div>

                                        <!-- Video -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">Choose Video</label>
                                            <input class="form-control" name="video" type="file">
                                        </div>

                                        <!-- Full Content Quill Editor -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">ENTER FULL CONTENT</label>
                                            <div id="fullEditor" style="height: 400px;"></div>
                                            <input type="hidden" name="full_content" id="fullContentData">
                                        </div>

                                        <!-- Repeatable Sections: Section 1,2,3 -->
                                        <!-- <?php for ($i = 1; $i <= 3; $i++): ?>
                                            <div class="mb-3">
                                                <label class="form-label text-primary">Section <?php echo $i; ?> Content</label>
                                                <div id="editor<?php echo $i; ?>" style="height: 200px;"></div>
                                                <input type="hidden" name="section<?php echo $i; ?>_content" id="sectionContent<?php echo $i; ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-primary">Section <?php echo $i; ?> Image (optional)</label>
                                                <input class="form-control" name="section<?php echo $i; ?>_image" type="file">
                                            </div>
                                        <?php endfor; ?> -->

                                        <!-- Section 1 -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">Section 1 Content</label>
                                            <div id="editor1" style="height: 200px;"></div>
                                            <input type="hidden" name="section1_content" id="sectionContent1">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-primary">Section 1 Image (optional)</label>
                                            <input class="form-control" name="section1_image" type="file">
                                        </div>

                                        <!-- Section 2 -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">Section 2 Content</label>
                                            <div id="editor2" style="height: 200px;"></div>
                                            <input type="hidden" name="section2_content" id="sectionContent2">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-primary">Section 2 Image (optional)</label>
                                            <input class="form-control" name="section2_image" type="file">
                                        </div>

                                        <!-- Section 3 -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">Section 3 Content</label>
                                            <div id="editor3" style="height: 200px;"></div>
                                            <input type="hidden" name="section3_content" id="sectionContent3">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-primary">Section 3 Image (optional)</label>
                                            <input class="form-control" name="section3_image" type="file">
                                        </div>


                                        <button type="reset" class="btn btn-danger">Clear</button>
                                        <button type="submit" class="btn btn-success">Publish</button>
                                    </form>

                                    <!-- Include Quill -->
                                    <!-- <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
                                    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

                                    <script>
                                        // Initialize Quill editors
                                        const quillMain = new Quill('#mainEditor', {
                                            theme: 'snow',
                                            placeholder: 'Enter main content...'
                                        });
                                        const quillFull = new Quill('#fullEditor', {
                                            theme: 'snow',
                                            placeholder: 'Enter full content...'
                                        });

                                        const sections = [];
                                        for (let i = 1; i <= 3; i++) {
                                            sections[i] = new Quill('#editor' + i, {
                                                theme: 'snow',
                                                placeholder: 'Enter content for section ' + i
                                            });
                                        }

                                        // On submit, copy HTML to hidden inputs
                                        document.querySelector('#addblogform').onsubmit = function() {
                                            document.querySelector('#mainContentData').value = quillMain.root.innerHTML;
                                            document.querySelector('#fullContentData').value = quillFull.root.innerHTML;

                                            for (let i = 1; i <= 3; i++) {
                                                document.querySelector('#sectionContent' + i).value = sections[i].root.innerHTML;
                                            }
                                        }
                                    </script> -->

                                    <!-- Include Quill CSS & JS (ensure paths are correct for live server) -->
                                    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
                                    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

                                    <script>
                                        // Define full toolbar options
                                        const fullToolbarOptions = [
                                            [{
                                                'header': '1'
                                            }, {
                                                'header': '2'
                                            }, {
                                                'font': []
                                            }],
                                            [{
                                                'size': []
                                            }],
                                            ['bold', 'italic', 'underline', 'strike'],
                                            ['link', 'blockquote', 'code-block'], // link button included
                                            [{
                                                'list': 'ordered'
                                            }, {
                                                'list': 'bullet'
                                            }],
                                            [{
                                                'script': 'sub'
                                            }, {
                                                'script': 'super'
                                            }],
                                            [{
                                                'indent': '-1'
                                            }, {
                                                'indent': '+1'
                                            }],
                                            [{
                                                'direction': 'rtl'
                                            }],
                                            [{
                                                'color': []
                                            }, {
                                                'background': []
                                            }],
                                            [{
                                                'align': []
                                            }],
                                            ['clean']
                                        ];

                                        // Initialize main editor
                                        const quillMain = new Quill('#mainEditor', {
                                            theme: 'snow',
                                            modules: {
                                                toolbar: fullToolbarOptions
                                            },
                                            placeholder: 'Enter main content...'
                                        });

                                        // Initialize full editor
                                        const quillFull = new Quill('#fullEditor', {
                                            theme: 'snow',
                                            modules: {
                                                toolbar: fullToolbarOptions
                                            },
                                            placeholder: 'Enter full content...'
                                        });

                                        // Initialize section editors (section1, section2, section3)
                                        const sections = [];
                                        for (let i = 1; i <= 3; i++) {
                                            sections[i] = new Quill('#editor' + i, {
                                                theme: 'snow',
                                                modules: {
                                                    toolbar: fullToolbarOptions
                                                },
                                                placeholder: 'Enter content for section ' + i
                                            });
                                        }

                                        // On form submit, copy HTML content to hidden inputs
                                        document.querySelector('#addblogform').onsubmit = function() {
                                            document.querySelector('#mainContentData').value = quillMain.root.innerHTML;
                                            document.querySelector('#fullContentData').value = quillFull.root.innerHTML;

                                            for (let i = 1; i <= 3; i++) {
                                                document.querySelector('#sectionContent' + i).value = sections[i].root.innerHTML;
                                            }
                                        };
                                    </script>





                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <div class="footer-widget__copyright">
                                <p class="mini_text" style="color:black"> ©2024 Krishnadentacure . All Rights Reserved. Designed & Developed by <a href="https://bhavicreations.com/" target="_blank" style="text-decoration: none;color:black">Bhavi Creations</a></p>
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

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

</body>

</html>