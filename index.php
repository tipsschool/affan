<?php
session_start();

// Simple admin credentials
$admin_user = "admin";
$admin_pass = "12345";

// Handle login
if(isset($_POST['login'])){
    if($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass){
        $_SESSION['admin'] = true;
    } else {
        echo "<p style='color:red;'>Wrong credentials!</p>";
    }
}

// Handle logout
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
}

// Handle file upload
if(isset($_POST['upload']) && isset($_SESSION['admin'])){
    $target_dir = "uploads/";
    if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $target_file = $target_dir . basename($_FILES["logo"]["name"]);
    if(move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)){
        echo "<p style='color:green;'>Logo uploaded successfully!</p>";
    } else {
        echo "<p style='color:red;'>Upload failed!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Logo Upload</title>
</head>
<body>
<?php if(!isset($_SESSION['admin'])): ?>
    <!-- Login Form -->
    <h2>Admin Login</h2>
    <form method="POST">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>
<?php else: ?>
    <!-- Upload Form -->
    <h2>Upload Logo</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="logo" accept="image/*" required>
        <input type="submit" name="upload" value="Upload">
    </form>
    <br>
    <a href="index.php?logout=true">Logout</a>

    <!-- Display uploaded logo -->
    <?php
    $files = glob("uploads/*");
    if(count($files) > 0){
        echo "<h3>Current Logo:</h3>";
        echo "<img src='".$files[0]."' style='width:200px;'>";
    }
    ?>
<?php endif; ?>
</body>
</html>
