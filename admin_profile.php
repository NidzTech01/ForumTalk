<?php
session_start();

// Check if user is logged in
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $username = $user['username'];
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

 
include "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="dashboard.css">
    
    <title>Welcome: <?php echo htmlspecialchars($username); ?></title>
    
</head>
<body>
    <header>
        <label>Admin Dashboard</label>
        
        <nav class="nav">
            
            <a href="admin_profile.php" class="btn btn-warning">Profile</a>
            <a href="admindashboard.php" class="btn btn-warning">Approval Page</a>
            <a href="admin_create_event.php" class="btn btn-warning">Events</a>
            <a href="user_list.php" class="btn btn-warning">User List</a>
            <a href="index-chat.php" class="btn btn-warning">Channel</a>
            <a href="logout.php" class="btn btn-warning">Log-Out</a>
        </nav>
    </header>

    <div class = "main-profile">
                
    <div class="profile">
   
    
    
    </div>
        <h1> <?php echo htmlspecialchars($username); ?></h1>
        
        <nav>
            
            <li><a href="admin_otherpost.php"><button>Post</button></a></li>
            <li><a href="admin_editprofile.php"><button>Edit Profile</button></a></li>
            <li><a href="admin_profile.php"><button>Create a Post</button></a></li>
        </nav> 
        
    </div>

    <div class="clear-fix"></div>
    

    <div class="section2">
            <div class="create-post">
                
                <div class="container-create">

                <h2>Create a Post</h2>

                <form action="create_post.php" method="post" enctype="multipart/form-data">
                    <div class="post">
                        <label for="title">Title:</label> <br>
                        <input type="text" id="title" name="title" required> 
                    </div>
                        
                    <div class="post">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required></textarea> <br>
                    </div>
                    
                    <div class="post">
                        <label for="image">Upload Image:</label>
                        <input type="file" id="image" name="image"> <br>
                    </div>
                        <button id="btn-submitpost" type="submit">Submit Post</button>
                </form>
                </div>
            </div>

            <div class="About">
                    <h2>About me</h2>
                    <div class="about-info">
                    <label>Bio: <?php echo $user['bio']; ?></label> <br>
                    <label>Gender: <?php echo $user['gender']; ?></label> <br>
                    <label>Birthday: <?php echo $user['birthday']; ?></label><br>
                    <label>Age: <?php echo $user['age']; ?></label>

            </div>
    </div>
