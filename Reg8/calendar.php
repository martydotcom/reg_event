<?php

	session_start(); 
	//echo session_id();
	//echo "Hello world calendar!<br>";
	
	//caused problems/ because its an array not 
	//just a variable echo $_SESSION['username']; doesnt work
	
	echo $_SESSION['user']['username'];	
	

function build_calendar($month,$year){	
$mysqli=new mysqli('localhost','root','','registration2');
/*
$stmt=$mysqli->prepare('select*from bookings where MONTH(date)=? AND YEAR(date)=?');
$stmt->bind_param('ss',$month,$year);
$bookings=array();
if($stmt->execute()){
	$result=$stmt->get_result();
	if($result->num_rows>0){
	while($row=$result->fetch_assoc()){
		$bookings[]=$row['date'];
	}
	$stmt->close();
	}
}
*/
	
//Create an array for days of week
$daysOfWeek=array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday99');

//First day of Month
$firstDayOfMonth=mktime(0,0,0,$month, 1,$year);

//Days in Month
$numberDays=date('t',$firstDayOfMonth);

//Retrive info about first day of month
$dateComponents=getdate($firstDayOfMonth);

//Name of Month 
$monthName=$dateComponents['month'];

//Index value (0-6) of first day in month
$dayOfWeek=$dateComponents['wday'];

//Create the table tag opener and day headers
$datetoday=date('Y-m-d');

$prev_month=date('m', mktime(0,0,0,$month-1,1,$year));
$prev_year=date('Y', mktime(0,0,0,$month-1,1,$year));
$next_month=date('m', mktime(0,0,0,$month+1,1,$year));
$next_year=date('Y' ,mktime(0,0,0,$month+1,1,$year));
$calendar="<center><h2>$monthName $year</h2>";

//Select current month 
$calendar.="<a class='btn btn-primary btn-xs'href='?month=".date('m')."&year=".date('Y')."'>CurrentMonth<a/>";

//select next month
$calendar.="<a class='btn btn-primary btn-xs'href='?month=".$next_month."&year=".$next_year."'>NextMonth</a></center>";
$calendar.="<br><table class='table table-bordered'>";
$calendar.="<tr>";

//Create calendar headers
foreach($daysOfWeek as $day){
$calendar.="<th class='header'>$day</th>";
}



//Create rest of Calendar, begin the day counter starting at the 1st
$currentDay=1;
$calendar.="</tr><tr>";
//variable dayOfWeek used for calendar to display on exactly 7 colums
if($dayOfWeek > 0){
	for($k=0; $k<$dayOfWeek; $k++){
		$calendar.="<td class='empty'></td>";
		}
	}
$month= str_pad($month,2,"0",STR_PAD_LEFT);
while($currentDay<=$numberDays){
	
//seventh column reached, start a new row
	if($dayOfWeek==7){
		$dayOfWeek=0;
		$calendar.="</tr><tr>";
	}
$currentDayRel=str_pad($currentDay,2,"0",STR_PAD_LEFT);
$date="$year-$month-$currentDayRel";
$eventNum=0;
$dayname = strtolower(date('l', strtotime($date)));
$today=$date==date('Y-m-d')?"today":"";

//test
 if($date<date('Y-m-d')){
             $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>N/A</button>";
 }else{
             $calendar.="<td class='$today'><h4>$currentDay</h4> <a href='book.php?date=".$date."' class='btn btn-success btn-xs'>Book</a>";
         }

$calendar.="</td>";

//increment counter
$currentDay++;
$dayOfWeek++;

}

//complete the row of the last week in month if necessary

if($dayOfWeek!=7){
	
	$remainingDays=7-$dayOfWeek;
	for($l=0;$l<$remainingDays;$l++){
		$calendar.="<td class='empty'></td>";
	}
}


$calendar.="</tr>";
$calendar.="</table>";

echo$calendar;

}
?>


<html>
 <body>
<head>
<meta name="viewpoint" content="width=device-width,initial-scale=1.0">
<link rel="stylesheet"href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="style.css">


</head>

<body>
<div class="container">
	<div class="row">
		<div class="col-md-12">
		<?php
		$dateComponents = getdate();
		if(isset($_GET['month'])&&isset($_GET['year'])){
			$month=$_GET['month'];
			$year=$_GET['year'];
			}else{
			$month=$dateComponents['mon'];
			$year=$dateComponents['year'];
			}

			echo build_calendar($month, $year);

		?>
		</div>
	</div>
</div>


</body>
</html>

