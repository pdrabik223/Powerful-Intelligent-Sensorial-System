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
<?php
		include "db_connection.php";
    $records = mysqli_query($con,"select date from measurements order by id desc limit 1");
    $data = mysqli_fetch_array($records);

?>
<body>
  <title>PIGLET- Data</title>
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
        <li class="nav-item"> <a class="btn disabled btn-primary m-1" href="data">Data</a> </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"> <a class="nav-link" href="logout">Log out</a> </li>
      </div>
    </div>
  </nav>
  
  <div class="py-5">
    <div class="container">
    <h1>Data</h1>
     <!-- data i godzina ostatniego pomiaru -->
	  <h5><?php echo $data['date']; ?></h5>

      <table class="table table-hover table-striped table-bordered">
        <thead class="thead-inverse">
            <tr>
                <!-- Columns in tabel -->
                <th scope="col">Measurement ID</th>
                <th scope="col">Date</th>
                <th scope="col">Temperature</th>
                <th scope="col">Humidity</th>
                <th scope="col">Greenery in %</th>
                <!-- <th scope="col">Greenery change</th> -->
				<th scope="col">Status</th>
                <th scope="col">Preview</th>
            </tr>
        </thead>
	<?php
		if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
		
		$total_per_page = 15; //wierszy na strone
        $total_rows_sql = mysqli_query($con,"select count(id) from measurements"); //wszystkich wierszy z bazy
		$total_rows = mysqli_fetch_array($total_rows_sql)[0];
		$minimum_id_query = mysqli_query($con,"select id from measurements"); //wszystkich wierszy z bazy
		$minimum_id = mysqli_fetch_array($minimum_id_query)[0];
		$total_pages = $total_rows / $total_per_page; //max stron
		
		if (($total_rows % $total_per_page) > 0 ) {
			$total_pages = $total_pages + 1;
		}
		
		if ( $page > $total_pages ) {
			$page = 1;
		}
		$records = mysqli_query($con,"select id, date, temp, humidity, greenery, greenery_change, flags from measurements where (id <= $total_rows + $minimum_id - ($total_per_page * ($page - 1))) order by id desc limit $total_per_page");

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
                    <!-- <td><php echo $data['greenery_change'];?></td> -->
					<td>
						<?php if (($data['flags']) == 0) { ?>
							<i class="fas fa-circle fa-2x text-success"></i>
						<?php } else { ?>
							<i class="fas fa-circle fa-2x text-danger"></i>
						<?php } ?>
					</td>
					<td>
					<form action="viewplant" method="get">
					  <button class="btn btn-primary m-2" type="submit" name="measurement_id" value="<?php echo $data['id'] ?>">View Plant</button>
					</form></td>
                </tr>
        </tbody>
	    <?php
		}
	    ?>
      </table>
	  
	<!-- Pagination -->
   <div class="py-2"  >
    <div class="container"  >
      <div class="row" >
        <div class="col-md-12" >
          <ul class="pagination" >
			  <?php pagination($total_pages, $page); ?>
          </ul>
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
		<?php
			function pagination(int $total_pages, int $page)
			{
				if ($total_pages > 8) {
						?>
							<?php if($page!=1) { ?><li class="page-item"> <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>">Prev</a> </li><?php }  ?>
							<?php if($page!=1) { ?><li class="page-item"> <a class="page-link" href="<?php echo "?page=1" ; ?>"> <?php echo 1; ?> </a> </li>
							<?php } else { ?><li class="page-item active"> <a class="page-link" href="<?php echo "?page=1" ; ?>"> <?php echo 1; ?> </a> </li> <?php }  ?>
							<?php if ($page > 5) { ?> <li class="page-item"> <a class="page-link" > ... </a> </li> <?php } ?>
					<?php if ( $page >= 5 && $page <= $total_pages - 4 ) {
							for( $i = $page - 3; $i<= $page + 3; $i++) {
								if ( $page == $i)	{ ?>
									<li class="page-item active"> <a class="page-link" href="<?php echo "?page=".$i ; ?>"> <?php echo $i; ?> </a> </li>
								<?php } else { ?>
									<li class="page-item"> <a class="page-link" href="<?php echo "?page=".$i ; ?>"> <?php echo $i; ?> </a> </li>
								<?php	}
						 } } elseif ($page < 5) {
							for( $i = 2; $i<= $page + 4; $i++) {
								if ( $page == $i)	{ ?>
									<li class="page-item active"> <a class="page-link" href="<?php echo "?page=".$i ; ?>"> <?php echo $i; ?> </a> </li>
								<?php } else { ?>
									<li class="page-item"> <a class="page-link" href="<?php echo "?page=".$i ; ?>"> <?php echo $i; ?> </a> </li>
								<?php	}
						} } elseif ($page > $total_pages - 4) {
							for( $i = $page-4; $i<= $total_pages - 1; $i++) {
								if ( $page == $i)	{ ?>
									<li class="page-item active"> <a class="page-link" href="<?php echo "?page=".$i ; ?>"> <?php echo $i; ?> </a> </li>
								<?php } else { ?>
									<li class="page-item"> <a class="page-link" href="<?php echo "?page=".$i ; ?>"> <?php echo $i; ?> </a> </li>
								<?php	}
						} }?>
							<?php if ($page < $total_pages-4) { ?> <li class="page-item"> <a class="page-link" > ... </a> </li> <?php } ?>
							<?php if($page!=$total_pages) { ?><li class="page-item"> <a class="page-link" href="<?php echo "?page=".$total_pages ; ?>"> <?php echo $total_pages; ?> </a> </li>
							<?php } else { ?><li class="page-item active"> <a class="page-link" href="<?php echo "?page=".$total_pages ; ?>"> <?php echo $total_pages; ?> </a> </li> <?php }  ?>
							<?php if($page!=$total_pages) { ?><li class="page-item"> <a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>">Next</a> </li><?php }  ?>
			<?php } else { ?>
							<li class="page-item"> <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>">Prev</a> </li>
					<?php for( $i = 1; $i<= $total_pages; $i++) {
									if ( $page == $i)	{ ?>
										<li class="page-item active"> <a class="page-link" href="<?php echo "?page=".$i ; ?>"> <?php echo $i; ?> </a> </li>
									<?php } else { ?>
										<li class="page-item"> <a class="page-link" href="<?php echo "?page=".$i ; ?>"> <?php echo $i; ?> </a> </li>
								<?php	}
							} ?>
							<li class="page-item"> <a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>">Next</a> </li>
					<?php }
			}?>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</body>

</html>
