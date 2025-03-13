<!DOCTYPE html>
<html>
<head>
    <title>Image Compression Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .upload-box {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .result-box {
            margin-top: 20px;
        }
        .preview-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .preview-image {
            max-width: 45%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Image Compression Tool</h1>
    
    <div class="upload-box">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/*" required onchange="previewOriginal(event)">
            <br><br>
            <div id="original-preview" style="margin: 10px 0;"></div>
            <input type="submit" name="submit" value="Compress Image">
        </form>
    </div>

    <?php
    if(isset($_POST['submit']) && isset($_FILES['image'])) {
        $upload_dir = 'uploads/';
        $compressed_dir = 'compressed/';
        
        if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
        if (!file_exists($compressed_dir)) mkdir($compressed_dir, 0777, true);

        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = uniqid() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_file_name;
        $compressed_path = $compressed_dir . 'compressed_' . $new_file_name;

        if(move_uploaded_file($file_tmp, $upload_path)) {
            $command = "python3 compress.py " . escapeshellarg($upload_path) . " " . escapeshellarg($compressed_path);
            $output = shell_exec($command);

            $original_size = filesize($upload_path) / 1024;
            $compressed_size = filesize($compressed_path) / 1024;

            echo "<div class='result-box'>";
            echo "<h3>Results:</h3>";
            echo "<p>Original Size: " . round($original_size, 2) . " KB</p>";
            echo "<p>Compressed Size: " . round($compressed_size, 2) . " KB</p>";
            echo "<p>Space Saved: " . round($original_size - $compressed_size, 2) . " KB</p>";
            echo "<a href='$compressed_path' download>Download Compressed Image</a>";
            
            // Add preview section
            echo "<div class='preview-container'>";
            echo "<div>";
            echo "<h4>Original</h4>";
            echo "<img src='$upload_path' class='preview-image' alt='Original'>";
            echo "</div>";
            echo "<div>";
            echo "<h4>Compressed</h4>";
            echo "<img src='$compressed_path' class='preview-image' alt='Compressed'>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p style='color: red;'>Error uploading file</p>";
        }
    }
    ?>

    <script>
        function previewOriginal(event) {
            const preview = document.getElementById('original-preview');
            preview.innerHTML = '';
            const file = event.target.files[0];
            if (file) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '200px';
                img.style.height = 'auto';
                preview.appendChild(img);
            }
        }
    </script>
</body>
</html>