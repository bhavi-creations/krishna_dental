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
                                    <?php
                                    include '../../db.connection/db_connection.php';

                                    // Initialize variables
                                    $blog_id = $_GET['id'] ?? 0;
                                    $blog_id = intval($blog_id);

                                    // Fetch blog data if editing
                                    $title = $service = $main_content = $full_content = '';
                                    $main_image = $video = '';
                                    $section_contents = [1 => '', 2 => '', 3 => ''];
                                    $section_images = [1 => '', 2 => '', 3 => ''];

                                    if ($blog_id > 0) {
                                        $result = $conn->query("SELECT * FROM blogs WHERE id=$blog_id");
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $title = $row['title'];
                                            $service = $row['service'];
                                            $main_content = $row['main_content'];
                                            $full_content = $row['full_content'];
                                            $main_image = $row['main_image'];
                                            $video = $row['video'];
                                            $section_contents[1] = $row['section1_content'];
                                            $section_contents[2] = $row['section2_content'];
                                            $section_contents[3] = $row['section3_content'];
                                            $section_images[1] = $row['section1_image'];
                                            $section_images[2] = $row['section2_image'];
                                            $section_images[3] = $row['section3_image'];
                                        }
                                    }
                                    ?>

                                    <form style='color:black;' id="editblogform" action="addBlog.php" method="POST" enctype="multipart/form-data">

                                        <input type="hidden" name="id" value="<?php echo $blog_id; ?>">

                                        <!-- Title -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">ENTER TITLE</label>
                                            <input type="text" class="form-control" name='title' value="<?php echo htmlspecialchars($title); ?>" placeholder="Title" required>
                                        </div>

                                        <!-- Service Dropdown -->
                                        <div class="filter-section mb-3">
                                            <label class="form-label text-primary">Select Service:</label>
                                            <select name="service" class="form-control" required>
                                                <option value="">Select a Service</option>
                                                <?php
                                                $services = ["Root Canal", "Wisdom Tooth Removal", "Bad Breath Treatment", "Gum Treatment", "Teeth Cleaning", "Orthodontic Treatment", "Dental Crown & Bridge", "Invisible Aligners", "Dental Veneers", "Smile Makeover", "Teeth Whitening", "Dental Implants", "Dentures", "Smile Designing", "Full Mouth Rehabilitation Treatment"];
                                                foreach ($services as $s) {
                                                    $selected = ($service == $s) ? 'selected' : '';
                                                    echo "<option value=\"$s\" $selected>$s</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Main Content Quill -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">ENTER MAIN CONTENT</label>
                                            <div id="mainEditor" style="height:200px;"></div>
                                            <input type="hidden" name="main_content" id="mainContentData">
                                        </div>

                                        <!-- Main Image -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">Choose Main Image</label>
                                            <input type="file" name="main_image" class="form-control">
                                            <?php if (!empty($main_image)) { ?>
                                                <img src="uploads/blogs/<?php echo $main_image; ?>" style="max-width:200px;" class="img-thumbnail mt-2">
                                            <?php } ?>
                                        </div>

                                        <!-- Video -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">Choose Video</label>
                                            <input type="file" name="video" class="form-control">
                                            <?php if (!empty($video)) { ?>
                                                <video width="300" controls class="mt-2">
                                                    <source src="uploads/blogs/<?php echo $video; ?>" type="video/mp4">
                                                </video>
                                            <?php } ?>
                                        </div>

                                        <!-- Full Content Quill -->
                                        <div class="mb-3">
                                            <label class="form-label text-primary">ENTER FULL CONTENT</label>
                                            <div id="editor" style="height:400px;"></div>
                                            <input type="hidden" name="full_content" id="formcontentdata">
                                        </div>

                                        <!-- Sections -->
                                        <?php for ($i = 1; $i <= 3; $i++): ?>
                                            <div class="mb-3">
                                                <label class="form-label text-primary">Section <?php echo $i; ?> Content</label>
                                                <div id="editor<?php echo $i; ?>" style="height:200px;"></div>
                                                <input type="hidden" name="section<?php echo $i; ?>_content" id="sectionContent<?php echo $i; ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-primary">Section <?php echo $i; ?> Image (optional)</label>
                                                <input type="file" name="section<?php echo $i; ?>_image" class="form-control">
                                                <?php if (!empty($section_images[$i])) { ?>
                                                    <img src="uploads/blogs/<?php echo $section_images[$i]; ?>" style="max-width:200px;" class="img-thumbnail mt-2">
                                                <?php } ?>
                                            </div>
                                        <?php endfor; ?>

                                        <div class='row p-3'>
                                            <div class='col-xl-7 col-sm-2'></div>
                                            <button type='reset' class='btn btn-danger mx-1 my-2 col-xl-2'>Clear</button>
                                            <button type='submit' class='btn btn-success mx-1 my-2 col-xl-2'>Update</button>
                                        </div>
                                    </form>

                                    <!-- Quill JS -->
                                    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
                                    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
                                    <script>
                                        const quillMain = new Quill('#mainEditor', {
                                            theme: 'snow'
                                        });
                                        const quillFull = new Quill('#editor', {
                                            theme: 'snow'
                                        });
                                        const quill1 = new Quill('#editor1', {
                                            theme: 'snow'
                                        });
                                        const quill2 = new Quill('#editor2', {
                                            theme: 'snow'
                                        });
                                        const quill3 = new Quill('#editor3', {
                                            theme: 'snow'
                                        });

                                        // Load existing content
                                        quillMain.root.innerHTML = <?php echo json_encode($main_content); ?>;
                                        quillFull.root.innerHTML = <?php echo json_encode($full_content); ?>;
                                        quill1.root.innerHTML = <?php echo json_encode($section_contents[1]); ?>;
                                        quill2.root.innerHTML = <?php echo json_encode($section_contents[2]); ?>;
                                        quill3.root.innerHTML = <?php echo json_encode($section_contents[3]); ?>;

                                        // On submit, set hidden inputs
                                        document.querySelector('#editblogform').onsubmit = function() {
                                            document.querySelector('#mainContentData').value = quillMain.root.innerHTML;
                                            document.querySelector('#formcontentdata').value = quillFull.root.innerHTML;
                                            document.querySelector('#sectionContent1').value = quill1.root.innerHTML;
                                            document.querySelector('#sectionContent2').value = quill2.root.innerHTML;
                                            document.querySelector('#sectionContent3').value = quill3.root.innerHTML;
                                        };
                                    </script>

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