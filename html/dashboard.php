<!-- Jesli user nie zalogowany, nie moze wejsc w Dashboard bezposrednio -->
<?php
session_start();
if(!isset($_SESSION['user_id'])){
	header('location:login');
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="theme.css" type="text/css">
</head>


<body>
  <title>PIGLET</title>

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
          <li class="nav-item"> <a class="btn disabled btn-primary m-1" href="dashboard">Dashboard</a> </li> 
          <li class="nav-item"> <a class="btn btn-primary m-1" href="data" >Data <i class="fas fa-chart-line fa-fw"></i></a> </li>
          
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"> <a class="nav-link" href="logout">Log out</a> </li>
      </div>
    </div>
  </nav>
  
  <!-- Polaczenie z baza -->
  <?php
		include "db_connection.php";
		
		$records = mysqli_query($con,"select id, date, temp, humidity, greenery, greenery_change, image, flags from measurements order by id desc limit 1");
		$data = mysqli_fetch_array($records);

		mysqli_close($con); 
	?>

  <div class="py-5">
    <div class="container">
    <h1>Dashboard</h1>

    <!-- data i godzina ostatniego pomiaru -->
		<h5><?php echo $data['date']; ?> <b>	ID: <?php echo $data['id']; ?></b></h5>

      <div class="row">
        <div class="col-md-4" style="">
          <ul class="list-group">
            <li class=" border-0 list-group-item d-flex justify-content-between align-items-center">Temperature:<h3><?php echo number_format($data['temp'], 2).'°C'; ?></h3><i class="fa fa-thermometer-half fa-2x text-danger"></i></li>
            <!-- <li class=" border-0 list-group-item d-flex justify-content-between align-items-center">Humidity:<align-center><h3>59 %</h3></align-center> <br><i class="fa fa-tint fa-2x text-secondary"></i></li>  --> 
			      <li class=" border-0 list-group-item d-flex justify-content-between align-items-center">Humidity:<h3><?php echo number_format($data['humidity'], 2).' %'; ?></h3><i class="fa fa-tint fa-2x text-secondary"></i></li>
            
            <li class=" border-0 list-group-item d-flex justify-content-between align-items-center">Greenery:<h3><?php echo number_format($data['greenery'], 2).' %'; ?></h3><i class="fab fa-envira fa-2x text-success"></i></li>
            
            <!--
            <li class=" border-0 list-group-item d-flex justify-content-between align-items-center">Growth:<h3><?php echo $data['greenery_change']. ' %'; ?></h3>
            Jesli Growth > 0 strzalka w gore, jesli zmalal strzalka w dol
            <php if (($data['greenery_change']) > 0) : ?>
               <i class="fa fa-arrow-up fa-2x text-success"></i>
            <php elseif (($data['greenery_change']) < 0) : ?>
              <i class="fa fa-arrow-down fa-2x text-danger"></i>
            <php endif; ?>
            </li>
            -->

			  <?php if (($data['flags']) == 0) { ?>
		  			<li class=" border-0 list-group-item d-flex justify-content-between align-items-center">Plant status:<i class="fas fa-circle fa-2x text-success"></i></li>
			  <?php } else { ?>
					<li class=" border-0 list-group-item d-flex justify-content-between align-items-center">Plant status:<i class="fas fa-circle fa-2x text-danger"></i></li>
			  <?php } ?>
		  <!-- SAVE LOG -->
			      <li class="border-0 list-group-item d-flex justify-content-between align-items-center list-group-item-warning"> <a  href=
            <?php wh_log(
              'Temperature: '.$data['temp'].'°C'.
              ' Humidity: '.$data['humidity'].'%'.
              ' Greenery: '.$data['greenery'].'%'.
              ' Growth: '.$data['greenery_change'].'%'.
              ' Image source: '.$data['image'] ) ?>
            ><b>SAVE LOG</b><br></a><i class="fa fa-floppy-o text-muted fa-lg"></i></li>
          </ul>
          <!-- Form to change range of dates -->
          <div class="py-1">
                <div class="container">
                <form action="dashboard.php" method="post">
                <label for="begindate">Begin :</label>
                <input type="date" id="begindate" name="begindate" size=5px value="<?php echo $_POST['begindate']; ?>"><br>
                <label for="enddate">End at:</label>
                <input type="date" id="enddate" name="enddate" size=5px value="<?php echo $_POST['enddate']?>"><br>
                <input type="submit" class="btn btn-primary my-2" value="Submit" name="button">
                </form>
                </div>
              </div>
			<!-- Zdjecie- zaciagane z bazy -->
			<div class=""><img class="img-fluid d-block large" src="<?php echo $data['image']; ?>"  max-width="850" max-height="500"></div>
        </div>

        <!-- Nav tabs Canvas z wykresami -->
        <div class="col-md-8">
          <ul class="nav nav-tabs">
            <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabone"><i class="fa fa-thermometer-half"></i> Temperature</a> </li>
            <li class="nav-item"> <a class="nav-link" href="" data-toggle="pill" data-target="#tabtwo"><i class="fa fa-tint"></i> Humidity</a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabthree"><i class="fab fa-envira"></i> Greenery</a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabfour"><i class="fa fa-arrow-up"></i> Growth</a> </li>
          </ul>
          <div class="tab-content mt-2">
            <div class="tab-pane fade show active" id="tabone" role="tabpanel">
            <canvas id="chart_canvas_1" width="400" height="400"></canvas>
            </div>
            <div class="tab-pane fade" id="tabtwo" role="tabpanel">
              <canvas id="chart_canvas_2" width="400" height="400"></canvas>            
            </div>
            <div class="tab-pane fade" id="tabthree" role="tabpanel">
              <canvas id="chart_canvas_3" width="400" height="400"></canvas>
            </div>
            <!-- M i K dodaliśmy kolejny chart -->
            <div class="tab-pane fade" id="tabfour" role="tabpanel">
              <canvas id="chart_canvas_4" width="400" height="400"></canvas>
            </div>
          </div>
        </div>

        <!-- Downloading data to Data from database. Default range is last 24 hours. -->
        <?php
          include "downloadChartData.php";

          if (isset($_POST['button'])) {
            $start_date = $_POST['begindate'];
            $end_date = $_POST['enddate'];
          } else {
			  // te dane musza byc jakies globalne albo sesyjne, nie wiem
			  $start_date = new DateTime("now");
			  date_sub($start_date,date_interval_create_from_date_string("1 day"));
			  $start_date = $start_date->format('Y-m-d H:i:s');
			  $end_date = new DateTime("now");
			  $end_date = $end_date->format('Y-m-d H:i:s');
          }
          $chart_master = downloadChartData($start_date, $end_date);
        ?>

        <!-- Skrypt wykresów Data.js -->
        <script type="text/javascript">

          // Translating arrays form PHP to JS
          var data_date = <?php echo json_encode($chart_master['date']); ?>;
          var data_temperature = <?php echo json_encode($chart_master['temp']); ?>;
          var data_humidity = <?php echo json_encode($chart_master['hum']); ?>;
          var data_greenery = <?php echo json_encode($chart_master['green']); ?>;
          var data_growth = <?php echo json_encode($chart_master['growth']); ?>;
          var data_date_greenery = <?php echo json_encode($chart_master['date_greenery']); ?>;

          const ctx_t = document.getElementById('chart_canvas_1').getContext('2d');
          const chart_temperature = new Chart(ctx_t, {
              type: 'line',
              data: {
                  labels: data_date,
                  datasets: [{
                      label: "Temperature",
                      data: data_temperature,
                      borderColor:'rgb(201, 0, 0)',
                      tension: 0.2,
                      borderWidth: 5
                  }]
              },
              options: {
                 plugins: { 
                      legend: 
                      { 
                        display: false 
                        }
                     } ,
                  scales: {
                      x: {
                          title: {
                            display: true,
                            text: ' Date',
                            font: {
                              size: 20
                            }

                          }
                      },
                      y: {
                        beginAtZero: false,
                        title: {
                          display: true,
                          text: 'Temperature  [°C]',
                          font: {
                            size: 20
                          }
                        }
                      }
                  }
                 
              }
          });

          const ctx_h = document.getElementById('chart_canvas_2').getContext('2d');
          const chart_humidity = new Chart(ctx_h, {
              type: 'line',
              data: {
                  labels: data_date,
                  datasets: [{
                      label: 'Humidity [%]',
                      data: data_humidity,
                      borderColor:'rgb(37, 80, 250)',
                      tension: 0.2,
                      borderWidth: 5
                  }]
              },
              options: {
                 plugins: { 
                      legend: 
                      { 
                        display: false 
                        }
                     } ,
                  scales: {
                      x: {
                          title: {
                            display: true,
                            text: ' Date',
                            font: {
                              size: 20
                            }

                          }
                      },
                      y: {
                        beginAtZero: false,
                        title: {
                          display: true,
                          text: 'Humidity  [%]',
                          font: {
                            size: 20
                          }
                        }
                      }
                  }
                 
              }
          
          });

          const ctx_g = document.getElementById('chart_canvas_3').getContext('2d');
          const chart_greenery = new Chart(ctx_g, {
              type: 'line',
              data: {
                  labels: data_date_greenery,
                  datasets: [{
                      label: 'Greenery [%]',
                      data: data_greenery,
                      borderColor:'rgb(0, 200, 0)',
                      tension: 0.2,
                      borderWidth: 5
                  }]
              },
              options: {
                 plugins: { 
                      legend: 
                      { 
                        display: false 
                        }
                     } ,
                  scales: {
                      x: {
                          title: {
                            display: true,
                            text: ' Date',
                            font: {
                              size: 20
                            }

                          }
                      },
                      y: {
                        beginAtZero: false,
                        title: {
                          display: true,
                          text: 'Greenery  [%]',
                          font: {
                            size: 20
                          }
                        }
                      }
                  }
                 
              }
          });

          const ctx_gt = document.getElementById('chart_canvas_4').getContext('2d');
          const chart_growth = new Chart(ctx_gt, {
              type: 'bar',
              data: {
                  labels: data_date_greenery,
                  datasets: [{
                      label: 'Greenery Change',
                      data: data_growth ,
                      backgroundColor:'rgb(0, 200, 0)',
                      tension: 0.2
                  }]
              },
              options: {
                 plugins: { 
                      legend: 
                      { 
                        display: false 
                        }
                     } ,
                  scales: {
                      x: {
                          title: {
                            display: true,
                            text: ' Date',
                            font: {
                              size: 20
                            }

                          }
                      },
                      y: {
                        beginAtZero: false,
                        title: {
                          display: true,
                          text: 'Greenery Change',
                          font: {
                            size: 20
                          }
                        }
                      }
                  }
                 
              }
          });
        </script>

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

  <!-- Function SAVE LOG -->
      <?php
          function wh_log($log_msg)
        {
            $log_filename = "log";
            if (!file_exists($log_filename)) 
            {
                // create directory/folder uploads.
                mkdir($log_filename, 0777, true);
            }
            $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
            // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
            file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
        } 
    ?>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</body>

</html>