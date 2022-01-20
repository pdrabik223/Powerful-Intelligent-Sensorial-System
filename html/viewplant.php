<!-- Jesli user nie zalogowany, nie moze wejsc w Dashboard bezposrednio -->
<?php
session_start();
if (!isset($_SESSION['user_id'])){
	header('location:login');
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="theme.css" type="text/css">
</head>

<body>
  <title>PIGLET- View Plant</title>

 <!-- Navbar --> 
  <nav class="navbar navbar-expand-md navbar-dark bg-dark" style="">
    <div class="container"> <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar12">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar12"> <a class="navbar-brand d-none d-md-block" href="dashboard">
          <i class="fa d-inline fa-lg fa-leaf"></i>
          <b class=""> PIGLET<br></b>
        </a>
        <ul class="navbar-nav mx-auto">
        <li class="nav-item"> <a class="btn btn-primary m-1" href="dashboard" >Dashboard <i class="fas fa-home fa-fw"></i></a> </li>
        <li class="nav-item"> <a class="btn btn-primary m-1" href="data">Data</a> </li>

        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"> <a class="nav-link" href="logout">Log out</a> </li>
      </div>
    </div>
  </nav>
  
  <div class="py-5">
    <div class="container">
    <h1>View Plant</h1>

   

      <table class="table table-hover table-striped table-bordered">
        <thead class="thead-inverse">
            <tr>
                <!-- Columns in tabel -->
                <th scope="col">Measurement ID</th>
                <th scope="col">Date</th>
                <th scope="col">Temperature</th>
                <th scope="col">Humidity</th>
                <th scope="col">Greenery in %</th>
                <th scope="col">Greenery change</th>
				<th scope="col">Status</th>
                
            </tr>
        </thead>

       
    <?php
		include "db_connection.php";
		$plant = $_GET['measurement_id'];

	$records = mysqli_query($con,"select id, date, temp, humidity, greenery, greenery_change, image, flags from measurements where id='$plant'");
		mysqli_close($con);

	while($data = mysqli_fetch_array($records))
		{
	?>

        <tbody>
            <!-- Rows in tabel -->
                <tr>
                    <td><?php echo $data['id']; ?></td>
                    <td><?php echo $data['date']; ?></td>
                    <td><?php echo $data['temp'].'°C'; ?></td>
                    <td><?php echo $data['humidity'].'%'; ?></td>
                    <td><?php echo $data['greenery']; ?></td>
                    <td><?php echo $data['greenery_change'];?></td>
					<td>
						<?php if (($data['flags']) == 0) { ?>
							<i class="fas fa-circle fa-2x text-success"></i>
						<?php } else { ?>
							<i class="fas fa-circle fa-2x text-danger"></i>
						<?php } ?>
					</td>
                </tr>
        </tbody>
      </table>
		<p style="text-align:center;"><img src="<?php echo $data['image']; ?>" max-width="850" max-height="500"></p>
		<?php
		}
		?>

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</body>

</html>
