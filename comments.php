





        <section class="ul-service-details  second_section_blogs  d-block   d-md-none   d-lg-none">
            <div class="container-fluid">
                <d class="row g-xl-5 g-4 mx-3">













                    <div class=" col-md-12 ">
                     
















                                <style>
                                    .comment-overlay {
                                        position: fixed;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        height: 100%;
                                        background: rgba(0, 0, 0, 0.5);
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        z-index: 999;
                                    }

                                    .comment-box {
                                        background: #fff;
                                        padding: 20px;
                                        border-radius: 8px;
                                        width: 90%;
                                        max-width: 500px;
                                        position: relative;
                                    }

                                    .close-btn {
                                        position: absolute;
                                        top: 10px;
                                        right: 15px;
                                        font-size: 24px;
                                        cursor: pointer;
                                        color: #333;
                                    }

                                    .comment-box input,
                                    .comment-box textarea,
                                    .comment-box button {
                                        width: 100%;
                                        margin-bottom: 10px;
                                        padding: 10px;
                                        border-radius: 5px;
                                        border: 1px solid #ccc;
                                    }

                                    .comment-box button {
                                        background-color: #007bff;
                                        color: #fff;
                                        border: none;
                                        cursor: pointer;
                                    }

                                    .comment-list {
                                        margin-top: 20px;
                                        padding: 10px;
                                        background: #f9f9f9;
                                        border-radius: 8px;
                                    }

                                    .comment-item {
                                        padding: 10px;
                                        border-bottom: 1px solid #ddd;
                                    }

                                    .comment-item:last-child {
                                        border-bottom: none;
                                    }

                                    .comment-item strong {
                                        display: block;
                                        margin-bottom: 5px;
                                    }

                                    .comment-item p {
                                        margin: 0;
                                    }
                                </style>



                                <!-- Styling -->
                                <style>
                                    .show-comment-btn {
                                        background: #007bff;
                                        color: white;
                                        padding: 10px 18px;
                                        border: none;
                                        border-radius: 6px;
                                        cursor: pointer;
                                        margin-bottom: 10px;
                                        transition: background 0.3s ease;
                                    }

                                    .show-comment-btn:hover {
                                        background: #0056b3;
                                    }

                                    .comment-box {
                                        /* background: red; */
                                        background-image: radial-gradient(circle, #e8f2f9, #dcedf9, #d0e8f9, #c4e3f9, #b7def9, #b1dcf9, #abd9f9, #a5d7f9, #a5d7f9, #a5d7f9, #a5d7f9, #a5d7f9) !important;
                                        padding: 20px;
                                        border-radius: 8px;
                                        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
                                        max-width: 600px;
                                    }

                                    .comment-box h3 {
                                        margin-bottom: 15px;
                                        color: #333;
                                    }

                                    .comment-box input,
                                    .comment-box textarea {
                                        width: 100%;
                                        padding: 10px;
                                        margin-bottom: 12px;
                                        border: 1px solid #ddd;
                                        border-radius: 5px;
                                    }

                                    .comment-box button {
                                        background: #28a745;
                                        color: white;
                                        padding: 10px 16px;
                                        border: none;
                                        border-radius: 6px;
                                        cursor: pointer;
                                        transition: background 0.3s ease;
                                    }

                                    .comment-box button:hover {
                                        background: #1e7e34;
                                    }
                                </style>







                                <?php
                                // Auto DB Connection (localhost / live)
                                $host = 'localhost';
                                if ($_SERVER['SERVER_NAME'] == 'localhost') {
                                    $user = "root";
                                    $pass = "";
                                    $db = "krishnadental";
                                } else {
                                    $user = "krishnadentacureclinic";
                                    $pass = "ip4IvBVvK8TlT7y";
                                    $db = "krishnadentacureclinic";
                                }

                                try {
                                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    // ✅ Blog ID check
                                    $blog_id = isset($blog['id']) ? intval($blog['id']) : 0;

                                    // ✅ Fetch all comments for this blog
                                    $stmt = $pdo->prepare("SELECT user_name, comment 
                           FROM blog_comments 
                           WHERE blog_id = :blog_id 
                           ORDER BY created_at DESC");
                                    $stmt->bindParam(':blog_id', $blog_id, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $comment_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    die("❌ DB Error: " . $e->getMessage());
                                }
                                ?>


                                <!-- Write Comment Button -->
                                <button class="show-comment-btn mt-5" onclick="toggleCommentBox()">✍️ Write a Comment</button>

                                <!-- Comment Form Popup -->
                                <div id="comment-overlay" class="comment-overlay" style="display:none;">
                                    <div class="comment-box">
                                        <span class="close-btn" onclick="toggleCommentBox()">&times;</span>
                                        <h3>💬 Leave a Comment</h3>
                                        <form action="save_comment.php" method="POST">
                                            <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">

                                            <input type="text" name="user_name" placeholder="Your Name" required>
                                            <input type="email" name="user_email" placeholder="Your Email" required>
                                            <textarea name="comment" rows="4" placeholder="Write your comment..." required></textarea>

                                            <button type="submit">Post Comment</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Display Comments -->
                                <?php


                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    $comment_id = intval($_POST['comment_id']);
                                    $type = $_POST['type'];

                                    if (!in_array($type, ['like', 'dislike'])) {
                                        echo json_encode(["success" => false, "message" => "Invalid type"]);
                                        exit;
                                    }

                                    if (!isset($_SESSION['reactions'])) {
                                        $_SESSION['reactions'] = [];
                                    }

                                    // Check if user already reacted
                                    if (isset($_SESSION['reactions'][$comment_id])) {
                                        echo json_encode(["success" => false, "message" => "You can only react once."]);
                                        exit;
                                    }

                                    // Update database count
                                    if ($type === 'like') {
                                        $conn->query("UPDATE blog_comments SET likes = likes + 1 WHERE id=$comment_id");
                                    } else {
                                        $conn->query("UPDATE blog_comments SET dislikes = dislikes + 1 WHERE id=$comment_id");
                                    }

                                    // Store reaction in session
                                    $_SESSION['reactions'][$comment_id] = $type;

                                    // Fetch updated counts
                                    $res = $conn->query("SELECT likes, dislikes FROM blog_comments WHERE id=$comment_id");
                                    $row = $res->fetch_assoc();

                                    echo json_encode([
                                        "success" => true,
                                        "likes" => (int)$row['likes'],
                                        "dislikes" => (int)$row['dislikes']
                                    ]);
                                }
                                ?>




                                <div class="comment-list">
                                    <h4>📝 Latest Comments</h4>
                                    <div class="row">
                                        <?php
                                        // All comments fetch
                                        $all_comments_sql = "SELECT * FROM blog_comments WHERE blog_id = '$blog_id' ORDER BY id DESC";
                                        $all_comment_result = $conn->query($all_comments_sql);

                                        if ($all_comment_result && $all_comment_result->num_rows > 0) {
                                            while ($row = $all_comment_result->fetch_assoc()) {
                                                $comment_id = $row['id'];
                                                $user_name  = htmlspecialchars($row['user_name']);
                                                $comment    = htmlspecialchars($row['comment']);
                                                $reply_text = htmlspecialchars($row['reply_text']);
                                                $likes      = (int)$row['likes'];
                                                $dislikes   = (int)$row['dislikes'];

                                                echo "
                                        <div class='col-md-6 mb-3'>
                                            <div class='comment-item p-3 border rounded shadow-sm h-100'>
                                                <p><strong>Name:</strong> $user_name</p>
                                                <p><strong>Comment:</strong> $comment</p>";

                                                // ✅ If reply exists, show replies count (split by || for multiple replies)
                                                if (!empty($reply_text)) {
                                                    $replies = explode("||", $reply_text); // multiple replies stored as text separated by ||
                                                    $reply_count = count($replies);

                                                    echo "
                                                <a href='javascript:void(0)' class='text-primary small' onclick='toggleReply($comment_id)'>
                                                    $reply_count Reply" . ($reply_count > 1 ? "ies" : "") . "
                                                </a>
                                                <div id='reply-box-$comment_id' class='mt-2' style='display:none;'>";

                                                    foreach ($replies as $reply) {
                                                        $reply = htmlspecialchars(trim($reply));
                                                        echo "
                                                    <div class='p-2 mb-1 bg-light border rounded'>
                                                        <strong>krishnadental Dental Hospital :</strong> $reply
                                                    </div>";
                                                    }

                                                    echo "</div>";
                                                }

                                                echo "
                                                <!-- Like / Dislike buttons -->
                                                <div class='mt-2 d-flex justify-content-between'>
                                                    <button class='btn btn-sm btn-outline-success' onclick='updateReaction($comment_id, \"like\")'>
                                                        👍 Like (<span id='like-count-$comment_id'>$likes</span>)
                                                    </button>
                                                    <button class='btn btn-sm btn-outline-danger' onclick='updateReaction($comment_id, \"dislike\")'>
                                                        👎 Dislike (<span id='dislike-count-$comment_id'>$dislikes</span>)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        ";
                                            }
                                        } else {
                                            echo "<div class='col-12'><p>No comments yet. Be the first to comment.!</p></div>";
                                        }
                                        ?>
                                    </div>
                                </div>


                                <script>
                                    function updateReaction(commentId, type) {
                                        let xhr = new XMLHttpRequest();
                                        xhr.open("POST", "update_reaction.php", true);
                                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                        xhr.onreadystatechange = function() {
                                            if (xhr.readyState === 4 && xhr.status === 200) {
                                                try {
                                                    let res = JSON.parse(xhr.responseText);
                                                    if (res.success) {
                                                        document.getElementById("like-count-" + commentId).innerText = res.likes;
                                                        document.getElementById("dislike-count-" + commentId).innerText = res.dislikes;
                                                    } else {
                                                        alert("❌ Failed to update reaction");
                                                    }
                                                } catch (e) {
                                                    console.error("Invalid JSON:", xhr.responseText);
                                                }
                                            }
                                        };
                                        xhr.send("comment_id=" + commentId + "&type=" + type);
                                    }
                                </script>





                                <!-- JS -->
                                <script>
                                    function toggleCommentBox() {
                                        var overlay = document.getElementById("comment-overlay");
                                        if (overlay.style.display === "none" || overlay.style.display === "") {
                                            overlay.style.display = "flex"; // show
                                        } else {
                                            overlay.style.display = "none"; // hide
                                        }
                                    }
                                </script>










