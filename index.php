<?php
    //server: sdb-o.hosting.stackcp.net
    //db name: secretdiary-313937291c
    //password: kKFOwwT0**
    $error = "";

    if(array_key_exists("submit",  $_POST)) {

        $link = mysqli_connect("sdb-o.hosting.stackcp.net",
            "secretdiary-313937291c",
            "kKFOwwT0**",
            "secretdiary-313937291c");

        if(mysqli_connect_error()) {
            die("Data Connection Error");
        }
        
      

        if(!$_POST["email"]) {
            $error .= "An emil address is required.<br>";
        }

        if(!$_POST["password"]) {
            $error .= "A password is required.<br>";
        }

        if($error !="")  {
            $error = "<p>There were error(s) in your form!</p>" . $error;
        } else {
            $emailAddress = mysqli_real_escape_string($link, $_POST["email"]);
            $query = "SELECT id FROM users WHERE email = '" . $emailAddress ."' LIMIT  1";

            $result = mysqli_query($link, $query);

            if(mysqli_num_rows($result) > 0) {
                $error = "That email address is taken!";
            } else {
                $password = mysqli_real_escape_string($link, $_POST["password"]);
                $password = password_hash($password, PASSWORD_DEFAULT);

                $query = "INSERT INTO users (email, password) VALUES ('" . $emailAddress . "', '" . $password . "')";

                if(!mysqli_query($link, $query)) {
                    $error .= "<p>Could not sign you up - Please try again later.</p>";
                    $error = "<p>" . mysqli_error($link) . "</p>";
                } else {
                    echo "Sign up succesful!";
                }
            }
        }

    }
?>

<div id="error"><?php echo $error; ?></div>

<!-- sign up form -->
<form method="post">
    <input type="email" name="email" placeholder="Your email">
    <input type="password" name="password" placeholder="Password">
    <input type="checkbox" name="stayLoggedIn" value="1">
    <!-- <input type="hidden" name="signUp" value="1"> -->

    <input type="submit" name="submit" value="Sign Up!">
</form>

<!-- log in form -->
<!-- <form method="post">
    <input type="email" name="email" placeholder="Your email">
    <input type="password" name="password" placeholder="Password">
    <input type="checkbox" name="stayLoggedIn" value="1">
    <input type="hidden" name="signUp" value="0">

    <input type="submit" name="submit" value="Log In">
</form> -->