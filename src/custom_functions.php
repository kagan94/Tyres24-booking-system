<?php
/**
 * Created by PhpStorm.
 * User: Leo
 * Date: 20.03.2017
 * Time: 0:03
 */

function isLogged() {
    return isset($_SESSION['login']);
}

function getCurrentUserLogin(){
    return isset($_SESSION['login']) ? $_SESSION['login'] : null;
}

function getCurrentUserId(){
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function isAdmin(){
    return getCurrentUserLogin() == 'admin';
}

function format_datetime($datetime){
    // Change datetime format and remove seconds from the time
    //$datetime_parts = explode(':', $datetime);
    //array_pop($datetime_parts);
    //implode(':', $datetime_parts);

    return date('d.m.Y H:i', strtotime($datetime));
}

function getFormattedEndDatetime($post){
    $endDatetime = '';

    if (isset($post['startDatetime'], $post['vehicleType'])){
        $startDatetime = $post['startDatetime'];
        $vehicleType = $post['vehicleType'];

        // Calculate end datetime with offset depending on the vehicle type
        $minutesOffset = 0;

        if ($vehicleType == 'car' || $vehicleType == 'van'){
            $minutesOffset = 30;
        } elseif ($vehicleType == 'truck'){
            $minutesOffset = 60;
        }

        $seconds = ($minutesOffset * 60);
        $endDatetime = date('d.m.Y H:i', strtotime($startDatetime) + $seconds);
    }

    return $endDatetime;
}