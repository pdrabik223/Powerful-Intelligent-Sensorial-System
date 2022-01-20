<?php
session_start();
if(isset($_SESSION['user_id'])){
	header('location:Dashboard');
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="https://static.pingendo.com/bootstrap/bootstrap-4.3.1.css">
</head>

<body>
  <title>PIGLET- Log in</title>

  <!-- Navbar --> 
  <nav class="navbar navbar-expand-md navbar-dark bg-dark" style="">
    <div class="container"> <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar12">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar12"> <a class="navbar-brand d-none d-md-block" href="#">
          <i class="fa d-inline fa-lg fa-leaf"></i>
          <b class=""> PIGLET<br></b>
        </a>
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"> <a class="nav-link" href="dashboard">Dashboard<br></a> </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"></li>
          <li class="nav-item"> <a class="nav-link" href="register">Register<br></a> </li>
      </div>
    </div>
  </nav>
  
  <!-- Log in module -->
  <div class="py-5 text-center w-100 h-100" style="background-image: url(&quot;./local/background1.jpg&quot;); background-size: cover;">
    <div class="container">
      <div class="row">
        <div class="mx-auto col-md-6 col-10 bg-white p-5">

          <!-- h1 Log in -->
          <h1 class="mb-4">Log in</h1>

          <!-- Actual form -->
          <form action="loginModule.php" method="post">
            <div class="form-group"> <input type="username" name="user" class="form-control" placeholder="Enter username" id="form9"> </div>
            <div class="form-group mb-3"> <input type="password" name="password" class="form-control" placeholder="Password" id="form10"> <small class="form-text text-muted text-right">
                <a href="register"> Do not have an account?</a>
              </small> </div> <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <p class="mb-0" >© 2021 Nowoczesne rozwiązania cyfrowe w służbie nauce i społeczeństwu. PIGLET</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</body>

</html>
