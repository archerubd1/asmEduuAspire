<?php
require_once('../config.php');

$level  = isset($_GET['level']) ? $_GET['level'] : '';
$parent = isset($_GET['parent']) ? (int) $_GET['parent'] : 0;

if ($level == 'country') {
    $r = mysqli_query($coni, "SELECT country_id, name FROM geo_countries ORDER BY name");
    echo '<option value="">Select Country</option>';
    while ($row = mysqli_fetch_assoc($r))
        echo "<option value='{$row['country_id']}'>{$row['name']}</option>";

} elseif ($level == 'state') {
    $r = mysqli_query($coni, "SELECT state_id, name FROM geo_states WHERE country_id=$parent ORDER BY name");
    echo '<option value="">Select State</option>';
    while ($row = mysqli_fetch_assoc($r))
        echo "<option value='{$row['state_id']}'>{$row['name']}</option>";

} elseif ($level == 'district') {
    $r = mysqli_query($coni, "SELECT district_id, name FROM geo_districts WHERE state_id=$parent ORDER BY name");
    echo '<option value="">Select District</option>';
    while ($row = mysqli_fetch_assoc($r))
        echo "<option value='{$row['district_id']}'>{$row['name']}</option>";

} elseif ($level == 'city') {
    $r = mysqli_query($coni, "SELECT city_id, name, pincode FROM geo_cities WHERE district_id=$parent ORDER BY name");
    echo '<option value="">Select City</option>';
    while ($row = mysqli_fetch_assoc($r))
        echo "<option value='{$row['city_id']}'>{$row['name']} ({$row['pincode']})</option>";
}
?>
