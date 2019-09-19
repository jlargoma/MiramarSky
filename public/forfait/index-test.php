<?php
die();
$Bearer = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsInN1YiI6MTEwMiwiaWF0IjoxNTY0MTc3ODkzfQ.hcCNY1jJgzXNwwxmJSYwdflAgp6C_8jKkfsu_nirdUI';

$data = [
"forfaits" => [
[
"age" => 25,
"dateFrom" => "10/12/2019",
"dateTo" => "12/12/2019"
]
],
"extras" => [
"equipments" => [
[
"equipmentId" => 14,
"dateFrom" => "10/12/2019",
"dateTo" => "12/12/2019"
]
],
"insurances" => [
[
"insuranceId" => 5,
"clientDni" => "33333333E",
"clientName" => "Juan Pérez",
"dateFrom" => "10/12/2019",
"dateTo" => "12/12/2019"
]
],
"classes" => [
[
"classId" => 1,
"dateFrom" => "10/12/2019",
"dateTo" => "12/12/2019"
],
[
"classId" => 1,
"dateFrom" => "10/12/2019",
"dateTo" => "12/12/2019"
]
]
],
"skiResortId" => 5
];
?>
<br><h1>getprices</h1><br>
<?php
$json = json_encode($data);
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.dev.forfaitexpress.com/v1/api/getprices",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_POSTFIELDS => $json,
CURLOPT_HTTPHEADER => array(
"Content-Type: application/json",
"Authorization: Bearer $Bearer"
),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
echo "cURL Error #:" . $err;
} else {
echo $response;
}
?>
<br><hr><h2>getskiresorts</h2><br>

<?php
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.dev.forfaitexpress.com/v1/api/getskiresorts",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array(
"Authorization: Bearer $Bearer"
),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
echo "cURL Error #:" . $err;
} else {
echo $response;
}
?>
<br><hr><h2>getbalance</h2><br>
<?php
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.dev.forfaitexpress.com/v1/api/getbalance",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array(
"Authorization: Bearer $Bearer"
),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
echo "cURL Error #:" . $err;
} else {
echo $response;
}
?>
<br><hr><h2>getseasons</h2><br>
<?php
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.dev.forfaitexpress.com/v1/api/getseasons",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array(
"Authorization: Bearer $Bearer"
),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
echo "cURL Error #:" . $err;
} else {
echo $response;
}


