<?php
    session_start();
    if (!isset($_SESSION['admin_login'])) {
        header("location: ../index.php");
    }

    require_once '../connection.php';

    $sql_order = "SELECT * FROM sp_transaction WHERE operation = 'จัดส่งสําเร็จ'";
    $result = $conn->query($sql_order);
    $all_orders = $result->num_rows; #จำนวน order

    $sql_customer = "SELECT * FROM masterlogin WHERE role = 'user'";
    $result2 = $conn->query($sql_customer);
    $all_customer = $result2->num_rows; #จำนวน user

    $sql_product = "SELECT * FROM sp_product";
    $result3 = $conn->query($sql_product);
    $all_product = $result3->num_rows; #จำนวน product

    $sql_netamount = "SELECT SUM(netamount) as total FROM sp_transaction WHERE operation='จัดส่งสําเร็จ';";
    $result4 = $conn->query($sql_netamount);
    while($row = $result4->fetch_assoc()) {
        $total=$row["total"];
    }

    $data = "";
    $sql_graph_day = "SELECT SUM(netamount) as total,
                      date(updated_at) as day 
                      FROM sp_transaction 
                      WHERE operation = 'จัดส่งสําเร็จ' 
                      GROUP BY date(updated_at);";
    $result5 = $conn->query($sql_graph_day);

    if ($result5->num_rows > 0) {
      // output data of each row
      while($row = $result5->fetch_assoc()) {

        $day = $row['day'];
        $total2 = $row['total'];
        $data.="['$day', $total2],";

      }
    } else {
      echo "0 results";
    }

    $data2 = "";
    $sql_graph_month = "SELECT SUM(netamount) as total, 
                        DATE_FORMAT(updated_at, '%Y-%m') as month 
                        FROM sp_transaction 
                        WHERE operation = 'จัดส่งสําเร็จ' 
                        GROUP BY month 
                        ORDER BY month;"; // จัดเรียงตามเดือน
    
    $result5 = $conn->query($sql_graph_month);
    
    if ($result5->num_rows > 0) {
        // output data of each row
        while ($row = $result5->fetch_assoc()) {
            $month = $row['month']; // ตัวแปร month จะเก็บค่าเป็น 'YYYY-MM'
            $total2 = $row['total'];
            $data2 .= "['$month', $total2],";
        }
    } else {
        echo "0 results";
    }
    
    // ลบ comma ท้ายสุด
    $data2 = rtrim($data2, ',');

    $data3 = "";
    $sql_graph_year = "SELECT SUM(netamount) as total, 
                       YEAR(updated_at) as year 
                       FROM sp_transaction 
                       WHERE operation = 'จัดส่งสําเร็จ' 
                       GROUP BY year 
                       ORDER BY year;"; // จัดเรียงตามปี

    $result5 = $conn->query($sql_graph_year);

    if ($result5->num_rows > 0) {
        // output data of each row
        while ($row = $result5->fetch_assoc()) {
            $year = $row['year']; // ตัวแปร year จะเก็บค่าเป็น 'YYYY'
            $total2 = $row['total'];
            $data3 .= "['$year', $total2],";
        }
    } else {
        echo "0 results";
    }

    $data4 = "";
    $sql_type = "SELECT type , COUNT(*) as product_count FROM sp_product GROUP BY type;";
    $result6 = $conn->query($sql_type);

    if ($result5->num_rows > 0) {
        // output data of each row
        while($row = $result6->fetch_assoc()) {
            $type = $row['type'];
            $count = $row['product_count'];
            $data4.="['$type', $count],";
        }
      } else {
        echo "0 results";
      }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN PAGE</title>
    
    <link rel="stylesheet" href="css/admin.css">
    <script src="index.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    
    <div class="text-center">
        <div class="container1 background-container-header">

            <?php if(isset($_SESSION['success'])) : ?>
                <div class="alert alert-success">
                    <h3>
                        <?php
                            echo $_SESSION['success'];
                            header("refresh:1;admin_home.php");
                            unset($_SESSION['success']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>

            <h1>Admin Page</h1>

            <h3>
                <?php if(isset($_SESSION['admin_login'])) { ?>
                Welcome, <?php echo $_SESSION['admin_login']; }?>
            </h3>

        </div>
    </div>
    <div class="container1 background-container-menu">
        <div class="container2">
            <div class="sidebar">

                <a href="admin_home.php" class="sidebar-menu">
                    หน้าแรก
                </a>

                <a href="admin_administrator.php" class="sidebar-menu">
                    ผู้ดูแลระบบ
                </a>

                <a href="customer_list.php" class="sidebar-menu">
                    รายชื่อลูกค้า
                </a>

                <a href="product_list.php" class="sidebar-menu">
                    รายการสินค้า
                </a>

                <a href="order_history.php" class="sidebar-menu">
                    ประวัติรายการสั่งซื้อ
                </a>

                <hr>
                
                <a href="../logout.php" class="sidebar-menu btn-danger" style="border-radius: 10px;">ออกจากระบบ</a>

            </div>
            <div class="filter2">
                <div class="container background-container">
    
                    <div class="box1">
                        <div class="product-item" style="background: #3498DB;">
                            <p style="font-size: 1.2vw; margin-left: 10px; margin-top: 10px; padding: 10px;">ยอดขายทั้งหมด <?php echo $all_orders; ?> รายการ</p>
                            <i style="font-size: 7vw; color: #b9b9b9;" class="product-img fa-brands fa-shopify"></i>
                        </div>
                        <div class="product-item" style="background: #229954;">
                            <p style="font-size: 1.2vw;  margin-left: 10px; margin-top: 10px; padding: 10px;">รายการสินค้าทั้งหมด <?php echo $all_product; ?> รายการ</p>
                            <i style="font-size: 7vw; color: #b9b9b9;" class="product-img fa-solid fa-chart-simple"></i>
                        </div>
                    </div>

                    <div class="box1">
                        <div class="product-item" style="background: #E67E22;">
                            <p style="font-size: 1.2vw; margin-left: 10px; margin-top: 10px; padding: 10px;">จำนวนสมาชิกทั้งหมด <?php echo $all_customer; ?> คน</p>
                            <i style="font-size: 7vw; color: #b9b9b9;" class="product-img fa-solid fa-users"></i>
                        </div>
                        <div class="product-item" style="background: #E74C3C;">
                            <p style="font-size: 1.2vw; margin-left: 10px; margin-top: 10px; padding: 10px;">ยอดเงินรวมทั้งหมด <?php echo $total; ?> บาท</p>
                            <i style="font-size: 7vw; color: #b9b9b9;" class="product-img fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    
                    <div class="box2">
                        <div class="product-item1" style="background: #F9E79F;">
                            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                            <script type="text/javascript">
                                google.charts.load('current', {'packages':['corechart']});
                                google.charts.setOnLoadCallback(drawDayChart);
                                google.charts.setOnLoadCallback(drawMonthChart);
                                google.charts.setOnLoadCallback(drawYearChart); // ถ้าคุณมีข้อมูลสำหรับกราฟรายปี

                                function drawDayChart() {
                                    var data = google.visualization.arrayToDataTable([
                                        ['Day', 'Total'], <?php echo $data; ?>
                                    ]);
                                
                                    var options = {
                                        title: 'ผลการดำเนินงานของบริษัท (รายวัน)',
                                        curveType: 'function',
                                        legend: { position: 'bottom' }
                                    };
                                
                                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                                    chart.draw(data, options);
                                }
                            
                                function drawMonthChart() {
                                    var data = google.visualization.arrayToDataTable([
                                        ['Month', 'Total'], <?php echo $data2; ?>
                                    ]);
                                
                                    var options = {
                                        title: 'ผลการดำเนินงานของบริษัท (รายเดือน)',
                                        curveType: 'function',
                                        legend: { position: 'bottom' }
                                    };
                                
                                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart2'));
                                    chart.draw(data, options);
                                }

                                function drawYearChart() {
                                    var data = google.visualization.arrayToDataTable([
                                        ['Year', 'Total'], <?php echo $data3; ?>
                                    ]);
                                
                                    var options = {
                                        title: 'ผลการดำเนินงานของบริษัท (รายปี)',
                                        curveType: 'function',
                                        legend: { position: 'bottom' }
                                    };
                                
                                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart3'));
                                    chart.draw(data, options);
                                }
                            
                                function openGraphDay() {
                                    document.getElementById('curve_chart').style.display = 'block';
                                    document.getElementById('curve_chart2').style.display = 'none';
                                    document.getElementById('curve_chart3').style.display = 'none';
                                    drawDayChart(); // เรียกใช้ฟังก์ชันเพื่อวาดกราฟรายวัน
                                }
                            
                                function openGraphMouth() {
                                    document.getElementById('curve_chart').style.display = 'none';
                                    document.getElementById('curve_chart2').style.display = 'block';
                                    document.getElementById('curve_chart3').style.display = 'none';
                                    drawMonthChart(); // เรียกใช้ฟังก์ชันเพื่อวาดกราฟรายเดือน
                                }
                            
                                function openGraphYear() {
                                    document.getElementById('curve_chart').style.display = 'none';
                                    document.getElementById('curve_chart2').style.display = 'none';
                                    document.getElementById('curve_chart3').style.display = 'block';
                                    drawYearChart(); // เรียกใช้ฟังก์ชันเพื่อวาดกราฟรายปี
                                }
                            </script>

                            <center><p style="font-size: 1.2vw;">กราฟสรุปยอด</p></center>
                            
                            <div class="box3">
                                <a href="javascript:void(0);" onclick="openGraphDay()" class="sidebar-menu">รายวัน</a>
                                <a href="javascript:void(0);" onclick="openGraphMouth()" class="sidebar-menu">รายเดือน</a>
                                <a href="javascript:void(0);" onclick="openGraphYear()" class="sidebar-menu">รายปี</a>
                            </div>
                            
                            <center>
                                <div id="curve_chart" style="width: 98%; height: 380px; display: block;"></div>
                                <div id="curve_chart2" style="width: 98%; height: 380px; display: none;"></div>
                                <div id="curve_chart3" style="width: 98%; height: 380px; display: none;"></div>
                            </center>
                        </div>
                    </div>

                    <div class="box2">
                        <div class="product-item1" style="background: #F9E79F;">
                        
                            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                            <script type="text/javascript">
                              google.charts.load('current', {'packages':['corechart']});
                              google.charts.setOnLoadCallback(drawChart);
                        
                              function drawChart() {
                            
                                var data = google.visualization.arrayToDataTable([
                                  ['Task', 'Hours per Day'], <?php echo $data4; ?>
                                ]);
                        
                            
                                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                            
                                chart.draw(data);
                              }
                            </script>

                            <center><p style="font-size: 1.2vw;">กราฟจำนวนสินค้าแต่ละชนิด</p></center>
                            <center><div id="piechart" style="width: 98%; height: 400px;"></div></center>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</body>
</html>