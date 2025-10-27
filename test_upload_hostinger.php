<?php

/**
 * Test Upload Functionality for Hostinger
 * This script tests if file uploads are working correctly
 */

echo "<h1>Hostinger Upload Test</h1>";
echo "<hr>";

// Test 1: Check if upload directory exists and is writable
echo "<h2>Test 1: Directory Check</h2>";
$uploadDir = __DIR__ . '/public/images/admins';
echo "Upload directory: <code>$uploadDir</code><br>";

if (!is_dir($uploadDir)) {
    echo "❌ Directory does not exist. Creating it...<br>";
    if (mkdir($uploadDir, 0755, true)) {
        echo "✅ Directory created successfully<br>";
    } else {
        echo "❌ Failed to create directory<br>";
    }
} else {
    echo "✅ Directory exists<br>";
}

if (is_writable($uploadDir)) {
    echo "✅ Directory is writable<br>";
} else {
    echo "❌ Directory is NOT writable (check permissions)<br>";
}

// Test 2: Check file upload via form
echo "<h2>Test 2: File Upload Test</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<h3>Upload Attempt:</h3>";

    $file = $_FILES['test_file'];
    echo "File name: " . $file['name'] . "<br>";
    echo "File size: " . $file['size'] . " bytes<br>";
    echo "File type: " . $file['type'] . "<br>";
    echo "Temp name: " . $file['tmp_name'] . "<br>";
    echo "Error code: " . $file['error'] . "<br><br>";

    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = 'test_' . time() . '_' . basename($file['name']);
        $destination = $uploadDir . '/' . $filename;

        echo "Attempting to move file to: <code>$destination</code><br>";

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            echo "✅ <strong>File uploaded successfully!</strong><br>";
            $webPath = '/images/admins/' . $filename;
            echo "File URL: <a href='$webPath' target='_blank'>$webPath</a><br>";
            echo "<img src='$webPath' style='max-width: 200px; margin-top: 10px;' alt='Uploaded image'><br>";
        } else {
            echo "❌ <strong>Failed to upload file</strong><br>";
            echo "Possible reasons:<br>";
            echo "- Directory permissions (needs 755 or 777)<br>";
            echo "- PHP upload settings (check php.ini)<br>";
            echo "- File size too large<br>";
        }
    } else {
        echo "❌ Upload error: ";
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
                echo "File exceeds upload_max_filesize in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo "File exceeds MAX_FILE_SIZE in HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "File was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "Missing temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                echo "File upload stopped by extension";
                break;
            default:
                echo "Unknown error";
        }
        echo "<br>";
    }
}

// Upload form
?>
<h3>Upload Test Form:</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test_file" accept="image/*" required>
    <button type="submit">Test Upload</button>
</form>

<hr>

<?php
// Test 3: Check PHP configuration
echo "<h2>Test 3: PHP Configuration</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>upload_max_filesize</td><td>" . ini_get('upload_max_filesize') . "</td></tr>";
echo "<tr><td>post_max_size</td><td>" . ini_get('post_max_size') . "</td></tr>";
echo "<tr><td>max_file_uploads</td><td>" . ini_get('max_file_uploads') . "</td></tr>";
echo "<tr><td>file_uploads</td><td>" . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "</td></tr>";
echo "<tr><td>upload_tmp_dir</td><td>" . (ini_get('upload_tmp_dir') ?: 'Default') . "</td></tr>";
echo "<tr><td>disable_functions</td><td>" . (ini_get('disable_functions') ?: 'None') . "</td></tr>";
echo "</table>";

// Test 4: Check Laravel paths
echo "<h2>Test 4: Laravel Paths</h2>";
if (file_exists(__DIR__ . '/artisan')) {
    echo "✅ Laravel detected<br>";
    echo "Laravel root: <code>" . __DIR__ . "</code><br>";
    echo "Public path: <code>" . __DIR__ . '/public</code><br>';
} else {
    echo "❌ Not in Laravel root directory<br>";
}

// Test 5: List existing files
echo "<h2>Test 5: Existing Files in Upload Directory</h2>";
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    $files = array_diff($files, array('.', '..'));

    if (count($files) > 0) {
        echo "<ul>";
        foreach ($files as $file) {
            $filePath = '/images/admins/' . $file;
            echo "<li><a href='$filePath' target='_blank'>$file</a></li>";
        }
        echo "</ul>";
    } else {
        echo "No files found in upload directory<br>";
    }
} else {
    echo "Upload directory does not exist<br>";
}

?>

<hr>
<h2>Instructions:</h2>
<ol>
    <li>Use the form above to test file upload</li>
    <li>Check if the file appears in the list</li>
    <li>If it works here, the problem is in Laravel's profile upload logic</li>
    <li>If it doesn't work, check directory permissions on Hostinger</li>
</ol>

<p><strong>Fix permissions via SSH:</strong></p>
<pre>chmod -R 755 public/images/
mkdir -p public/images/admins
chmod 755 public/images/admins</pre>
