<?php
    //server: sdb-o.hosting.stackcp.net
    //db name: secretdiary-313937291c
    //password: kKFOwwT0**
    session_start();
    $error = "";

    if(array_key_exists("logout", $_GET)) {
        session_unset();
        setcookie("id", "", time() -60 * 60);
        $_COOKIE["id"] = "";
    } else if(array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)) {
        //go to the loggedinpage if you're still logged in
        header("Location: loggedinpage.php");
    } //end test for logout query string

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
            $password = mysqli_real_escape_string($link, $_POST["password"]);
            $password = password_hash($password, PASSWORD_DEFAULT);

            if($_POST["signUp"] == "1") {
                $query = "SELECT id FROM users WHERE email = '" . $emailAddress ."' LIMIT  1";

                $result = mysqli_query($link, $query);

                if(mysqli_num_rows($result) > 0) {
                    $error = "That email address is taken!";
                } else {
                        $query = "INSERT INTO users (email, password) VALUES ('" . $emailAddress . "', '" . $password . "')";

                    if(!mysqli_query($link, $query)) {
                    $error .= "<p>Could not sign you up - Please try again later.</p>";
                    $error = "<p>" . mysqli_error($link) . "</p>";
                    } else {
                        $id = mysqli_insert_id($link);

                        $_SESSION["id"] = $id;

                        if(isset($_POST["stayLoggedIn"])) {
                            setcookie("id", time() + 60 * 60 * 24 * 365);
                        }

                            header("Location: loggedinpage.php");

                    } //end if for successful/failed sign up
                } //end if mysqli_num_rows test
            } else {
                $query = "SELECT * FROM users WHERE email = '" . $emailAddress . "'";
                $result = mysqli_query($link, $query);
                $row = mysqli_fetch_array($result);
                $password = mysqli_real_escape_string($link, $_POST["password"]);

                if(isset($row) AND array_key_exists("password", $row)) {
                    $passwordMatch = password_verify($password, $row["password"]);

                    if($passwordMatch) {
                        $_SESSION["id"] = $row["id"];

                        if(isset($_POST["stayLoggedIn"]))  {
                            setcookie("id", $row["id"], time() + 60 * 60 * 24 * 365); 
                        }

                        header("Location: loggedinpage.php");
                    } else {
                         $error = "That email/password combination could not be found.";
                    } //end else - password matches or doesn't
                } else {
                    $error = "That email/password combination could not be found.";
                }
            }  //end if-else for signUp == 1 or 0      
        } //end of error existing check

    } //end if the submit exists
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Diary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

   <!-- CSS -->
    <style>
        html {
            background: url("diary.jpg") no-repeat center center fixed;
            background-size: cover;
            z-index: -1;
        }

        body {
            background: none;
        }
        .container {
            margin-top: 200px;
            text-align: center;
            width: 480px;
        }

        #loginForm {
            display: none;
        }

        .toggleForms {
            font-weight: bold;
            color: white;
        }

    </style>
 </head>   

 <body>
    <div id="error"><?php echo $error; ?></div>
<div class="container">
    <h1>Secret Diary</h1>
<!-- sign up form -->
    <form method="post" id="signupForm">
        <p>Intersted? Sign up now!</p>
        <fieldset class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Your email">
        </fieldset>
        <fieldset class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </fieldset>
        <fieldset class="checkbox">
            Stay Logged In:
            <input type="checkbox" name="stayLoggedIn" value="1">
        </fieldset>
        <fieldset class="form-group">
            <input type="hidden" name="signUp" value="1">
            <input type="submit" name="submit" class="btn btn-success" value="Sign Up!">
        </fieldset>    

        <p><a href="" class="toggleForms">Log In</a></p>
    </form>

    <!-- log in form -->
    <form method="post" id="loginForm">
        <p>Log in using your username and password</p>
        <fieldset class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Your email">
        </fieldset>
        <fieldset class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </fieldset>
        <fieldset class="checkbox">
            Stay Logged In:
            <input type="checkbox" name="stayLoggedIn" value="1">
        </fieldset>
        <fieldset class="form-group">
            <input type="hidden" name="signUp" value="0">
            <input type="submit" name="submit" class="btn btn-success" value="Log In">
        </fieldset>   
        
        <p><a href="" class="toggleForms">Sign Up</a></p>
    </form>
</div>

        

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
                crossorigin="anonymous"></script>

        <script type="text/javascript">
            $(".toggleForms").click(function(){
                $("#signupForm").toggle();
                $("#loginForm").toggle();
            })
        </script>

    </body>

</html>

