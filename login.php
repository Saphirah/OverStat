<html>
    <body>
        <?php
            include_once("static/Model/Model.php");

            //Check if already logged in, then logout
            if(isset($_SESSION['userid']))
                Account::Logout();

            //User is trying to login
            elseif(isset($_POST['username']) && isset($_POST['password']))
                Account::Login($_POST['username'], $_POST['password']);

            //User is trying to Register a new Account
            elseif(isset($_POST['registerUsername']) && isset($_POST['registerEmail']) && isset($_POST['registerPassword']))
                Account::CreateNewAccount($_POST['registerUsername'], $_POST['registerEmail'], $_POST['registerPassword']);
        ?>
        
        <!-- Header -->
        <?php
            new Header("Overview", "Overwatch Stat Analyzing Tool");
        ?>

        <!-- Login Field -->
        <div class="loginField" id="loginfield">
            <h1>LOGIN</h1>
            <form name="login" action="login.php" method="POST">
                <label for="username">Username:</label><br>
                <input class="loginInput" type="text" value="" id="username" name="username" /><br>
                <label for="password">Password:</label><br>
                <input class="loginInput" type="password" value="" id="password" name="password" /><br>
                <input class="loginButton enlargeField" type="submit" value="Login">
                <input id="s2" type="checkbox" class="switch" checked style="margin:15px; margin-right: 5px;">
                <label for="s2" style="margin-top:15px;">Stay Logged in</label>
            </form>
        </div>
        <!-- Register Field -->
        <div class="loginField" id="registerField">
            <h1>Register</h1>
            <form name="register" action="?register=1" method="POST">
                <label for="registerUsername">Username:</label><br>
                <input class="loginInput" type="text" value="" id="registerUsername" name="registerUsername" /><br>
                <label for="registerEmail">Email:</label><br>
                <input class="loginInput" type="email" value="" id="registerEmail" name="registerEmail" /><br>
                <label for="registerPassword">Password:</label><br>
                <input class="loginInput" type="password" value="" id="registerPassword" name="registerPassword"/><br>
                <input class="loginButton enlargeField" type="submit" value="Register">
            </form>
        </div>
    </body>
</html>


        
