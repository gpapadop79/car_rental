<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 28/12/2014
 * Time: 7:49 μμ
 */

function getCarLocations($dbcon){
    return mysqli_query($dbcon, "SELECT * FROM car_locations;");
}

function insertCarLocations($dbcon, $name, $description, $lat, $long){
    return mysqli_query($dbcon, "INSERT INTO car_locations(name, description, lat, `long`) " .
                                 "VALUES('" . $name . "', '" . $description . "', " . $lat . ", " . $long . ");");
}

function getLastInsertedCarLocation($dbcon){
    return mysqli_query($dbcon, "SELECT * FROM car_locations WHERE id = LAST_INSERT_ID();");
}

function updateCarLocation($dbcon, $name, $description, $lat, $long, $id) {
    return mysqli_query($dbcon, "UPDATE car_locations SET name = '" . $name . "', description = '" . $description . "', lat = " . $lat . ", `long`=" . $long . " WHERE id = " . $id . ";");
}

function deleteCarLocation($dbcon, $id){
    return mysqli_query($dbcon, "DELETE FROM car_locations WHERE id = " . $id . ";");
}