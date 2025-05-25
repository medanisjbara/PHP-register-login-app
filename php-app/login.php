<?php

  require_once 'classes/User.php';
  require_once 'classes/Input.php';
  require_once 'classes/Redirect.php';
  require_once 'classes/Cookies.php';


  if(Input::exist()) {


    $users = new User();

    $user_input = array(
      "username" => $_POST["username"],
      "password" => $_POST["password"]
    );

    $findUser = $users->findUser($user_input);

    if($findUser){
      Cookie::new('user', $findUser, 60*60*24);
      Redirect::go('dashboard');
    }

  }

  if(isset($_COOKIE['user'])) {
    Redirect::go('dashboard');
  }

?>


<form action="" method="post">

  <h1> Login page </h1>

  <div class="field">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="" autocomplete="off">
  </div>

  <div class="field">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
  </div>

  <input class="button" type="submit" value="Login">
</form>

<link rel="stylesheet" href="styles/user-panel.css">
