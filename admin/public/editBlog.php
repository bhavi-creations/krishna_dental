<?php
// Database connection (replace with your actual database connection details)
include '../../db.connection/db_connection.php';

// Get blog ID from URL
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($blog_id > 0) {
    // Fetch blog data
    $stmt = $conn->prepare("SELECT title, main_content, full_content, service FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $stmt->bind_result($title, $main_content, $full_content, $service);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Invalid blog ID.";
    exit;
}

$conn->close();
?>

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
    <!-- Include Quill CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- End of Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <?php include 'navbar.php'; ?>
                <!-- End of Topbar -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit BLOG</h1>
                    </div>
                    <div class="row">
                        <div class="col-xl-11">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-success">EDIT CONTENT</h6>
                                </div>
                                <div class="card-body">
                                    <form style='color:black;' id="editblogform" action="addBlog.php" method="POST" enctype="multipart/form-data">
                                        <!-- Title Input -->
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1" class="form-label text-primary">ENTER TITLE</label>
                                            <input type="text" class="form-control text-grey-900" name='title' id="exampleFormControlInput1" value="<?php echo htmlspecialchars($title); ?>" placeholder="Title" required>
                                        </div>

                                        <!-- Service Input -->
                                        <!-- Service Dropdown -->
                                        <div class="filter-section mb-3">
                                            <label for="service" class="form-label text-primary">Select Service:</label>
                                            <select id="service" name="service" class="form-control" required>
                                                <option value="">Select a Service</option>
                                                <option value="Root Canal" <?php echo ($service == 'Root Canal') ? 'selected' : ''; ?>>Root Canal</option>
                                                <option value="Wisdom Tooth Removal" <?php echo ($service == 'Wisdom Tooth Removal') ? 'selected' : ''; ?>>Wisdom Tooth Removal</option>
                                                <option value="Bad Breath Treatment" <?php echo ($service == 'Bad Breath Treatment') ? 'selected' : ''; ?>>Bad Breath Treatment</option>
                                                <option value="Gum Treatment" <?php echo ($service == 'Gum Treatment') ? 'selected' : ''; ?>> Gum Treatment</option>
                                                <option value="Teeth Cleaning" <?php echo ($service == 'Teeth Cleaning') ? 'selected' : ''; ?>>Teeth Cleaning</option>
                                                <option value="Orthodontic Treatment" <?php echo ($service == 'Orthodontic Treatment') ? 'selected' : ''; ?>>Orthodontic Treatment</option>
                                                <option value="Dental Crown & Bridge" <?php echo ($service == 'Dental Crown & Bridge') ? 'selected' : ''; ?>>Dental Crown & Bridge</option>
                                                <option value="Invisible Aligners" <?php echo ($service == 'Invisible Aligners') ? 'selected' : ''; ?>>Invisible Aligners</option>
                                                <option value="Dental Veneers" <?php echo ($service == 'Dental Veneers') ? 'selected' : ''; ?>>Dental Veneers</option>
                                                <option value="Smile Makeover" <?php echo ($service == 'Smile Makeover') ? 'selected' : ''; ?>>Smile Makeover</option>
                                                <option value="Teeth Whitening" <?php echo ($service == 'Teeth Whitening') ? 'selected' : ''; ?>> Teeth Whitening</option>
                                                <option value="Dental Implants" <?php echo ($service == 'Dental Implants') ? 'selected' : ''; ?>>Dental Implants</option>
                                                <option value="Dentures" <?php echo ($service == 'Dentures') ? 'selected' : ''; ?>>Dentures  </option>
                                                <option value="Smile Designing" <?php echo ($service == 'Smile Designing') ? 'selected' : ''; ?>>Smile Designing</option>
                                                <option value="Full Mouth Rehabilitation Treatment" <?php echo ($service == 'Full Mouth Rehabilitation Treatment') ? 'selected' : ''; ?>>Full Mouth Rehabilitation Treatment</option>
                                              
                                                <!-- <option value="Laser Gum" <?php echo ($service == 'Laser Gum') ? 'selected' : ''; ?>>Laser & Gum</option>
                                                <option value="Tooth Extraction" <?php echo ($service == 'Tooth Extraction') ? 'selected' : ''; ?>>Tooth Extraction</option>
                                                <option value="Teeth Cleaning" <?php echo ($service == 'Teeth Cleaning') ? 'selected' : ''; ?>>Teeth Cleaning</option>
                                                <option value="Gum Depigment" <?php echo ($service == 'Gum Depigment') ? 'selected' : ''; ?>>Gum Depigment</option>
                                                <option value="Teeth Whitening" <?php echo ($service == 'Teeth Whitening') ? 'selected' : ''; ?>>Teeth Whitening</option>
                                                <option value="Laser Gum Surgery" <?php echo ($service == 'Laser Gum Surgery') ? 'selected' : ''; ?>>Laser Gum Surgery</option>
                                                <option value="Mouth Ulcers" <?php echo ($service == 'Mouth Ulcers') ? 'selected' : ''; ?>>Mouth Ulcers</option>
                                                <option value="Precancerous Lesion" <?php echo ($service == 'Precancerous Lesion') ? 'selected' : ''; ?>>Precancerous Lesion</option>
                                                <option value="Laser Crown Lengthening" <?php echo ($service == 'Laser Crown Lengthening') ? 'selected' : ''; ?>>Laser Crown Lengthening</option> -->



                                            </select>
                                        </div>


                                        <!-- Quill Editor for Main Content -->
                                        <div class="mb-3">
                                            <label for="mainEditor" class="form-label text-primary">ENTER MAIN CONTENT</label>
                                            <div id="mainEditor" style="height: 200px;"></div>
                                            <input name="main_content" id="mainContentData" style="display: none">
                                        </div>

                                        <!-- Main Image Upload -->
                                        <div class="mb-3">
                                            <label for="formFileMainImage" class="form-label text-primary my-2">Choose Main Image</label>
                                            <input class="form-control" name="main_image" type="file" id="formFileMainImage" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="formFileVideo" class="form-label text-primary">Choose Video</label>
                                            <input class="form-control" name="video" type="file" id="formFileVideo" required>
                                        </div>

                                        <!-- Quill Editor for Full Content -->
                                        <label for="editor" class="form-label text-primary">ENTER FULL CONTENT</label>
                                        <div id="editor" style='height:400px;'></div>
                                        <input name="full_content" id="formcontentdata" style="display: none">

                                        <!-- Hidden Input for Blog ID -->
                                        <input type="hidden" name="id" value="<?php echo $blog_id; ?>">

                                        <!-- Form Buttons -->
                                        <div class='row p-3'>
                                            <div class='col-xl-7 col-sm-2'></div>
                                            <button type='reset' class='btn btn-danger mx-1 my-2 col-xl-2'>Clear</button>
                                            <button type='submit' class='btn btn-success mx-1 my-2 col-xl-2'>Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <!-- End of Footer -->
        </div>
    </div>

    <!-- Include Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <!-- Initialize Quill Editors and Load Existing Data -->
    <script>
        // Initialize Quill editors with color options in the toolbar
        const quillMain = new Quill('#mainEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
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
                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // Color and background color options
                    ['link', 'blockquote'],
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
                        'align': []
                    }],
                    ['clean'] // Remove formatting button
                ]
            },
            placeholder: 'Enter main content...',
        });

        const quillFull = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
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
                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // Color and background color options
                    ['link', 'blockquote'],
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
                        'align': []
                    }],
                    ['clean'] // Remove formatting button
                ]
            },
            placeholder: 'Compose full content...',
        });

        // Load existing data into Quill editors
        quillMain.root.innerHTML = <?php echo json_encode($main_content); ?>;
        quillFull.root.innerHTML = <?php echo json_encode($full_content); ?>;

        // On form submission, set Quill content into hidden input fields
        document.querySelector('#editblogform').onsubmit = function() {
            document.querySelector('#mainContentData').value = quillMain.root.innerHTML;
            document.querySelector('#formcontentdata').value = quillFull.root.innerHTML;
        };
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>