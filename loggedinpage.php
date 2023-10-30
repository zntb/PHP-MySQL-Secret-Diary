<?php
    session_start();

    if(array_key_exists("id", $_COOKIE)) {
        $_SESSION["id"] = $_COOKIE["id"];
    }

    if(array_key_exists("id", $_SESSION)) {
        echo '<p>Logged in! <a href="index.php?logout=1">Log out</a></p>';
    } else {
        header("Location: index.php");
    }

    include("header.php");
?>

    <div class="container-fluid">
        <textarea name="" id="diary" class="form-control" cols="30" rows="10"></textarea>
    </div>

<?php
    include("footer.php");
?>