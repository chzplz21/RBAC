<?php
require_once "PrivilegedUser.php";
require_once "Role.php";




session_start();

if (!empty($_POST)){

    $_SESSION["loggedin"] = $_POST["name"];

    if (isset($_SESSION["loggedin"])) {
        
      
       $u = PrivilegedUser::getByUsername($_SESSION["loggedin"]);
       
    }
    
    

}


?>




<html>
<body>

<form action="" method="post">
Name: <input  name="name"><br>
E-mail: <input name="email"><br>
<input type="submit">
</form>

</body>
</html>