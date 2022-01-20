<!-- Function downloading data from database and making them ready to use by chart.js
It takes two dates as a parameters, and selecting rows between them. -->

<?php
    function downloadChartData($start_date, $end_date)
    {
            // Database connection
            $con = mysqli_connect("34.125.40.127","root","pssd123");

            if(!$con){
                die("Connection failed: " . mysqli_connect_error());
            }else{
              mysqli_select_db($con, 'measurements');
            }
            
            // Quering data from database
            $results_temp_hum = mysqli_query($con,"SELECT date, temp, humidity FROM measurements WHERE (date BETWEEN '$start_date' AND '$end_date') ORDER BY date ASC");
            $results_greenery = mysqli_query($con,"SELECT date, greenery, greenery_change FROM measurements WHERE (date BETWEEN '$start_date' AND '$end_date') AND (flags <> 32) ORDER BY date ASC");

            // Declaration of arrays to hold divided data
            $chart_master_array = [];
            $chart_data_date = [];
            $chart_data_date_greenery = [];
            $chart_data_temp = [];
            $chart_data_humidity = [];
            $chart_data_greenery = [];
            $chart_data_greenery_change = [];

            // Assaigning values from result querry to separate arrays.
            while($row = mysqli_fetch_assoc($results_temp_hum)) {
              $chart_data_date[] = $row["date"];
              $chart_data_temp[] = $row["temp"];
              $chart_data_humidity[] = $row["humidity"];
            }

            $last_pomiar = 0;
            while($row = mysqli_fetch_assoc($results_greenery)) {
              $chart_data_date_greenery[] = $row["date"];;
              $chart_data_greenery[] = $row["greenery"];
              $chart_data_greenery_change[] = $last_pomiar - $row['greenery'];
              $last_pomiar = $row['greenery'];
            }

            $chart_master_array['date'] = $chart_data_date;
            $chart_master_array['date_greenery'] = $chart_data_date_greenery;
            $chart_master_array['temp'] = $chart_data_temp;
            $chart_master_array['hum'] = $chart_data_humidity;
            $chart_master_array['green'] = $chart_data_greenery;
            $chart_master_array['growth'] = $chart_data_greenery_change;

            // Closing database connection
            mysqli_close($con);

            return $chart_master_array;
    }
?>