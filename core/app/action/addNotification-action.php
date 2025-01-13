<?php
$user_id = $_SESSION['user_id'];
$notification = new ReservationData();

$notification->patient_id = $_POST["patient_id"];
$notification->next_date = $_POST["next_date"];
$notification->date = date("Y-m-d");
$notification->hour = date("H:i:s");
$notification->user_id = $user_id;
$notification->notification_module_id = $_POST["notification_module_id"];

if($notification->add_notification()) echo "success";
else http_response_code(500);

?>