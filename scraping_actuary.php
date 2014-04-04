<?php

$ch = curl_init("http://www.ssa.gov/OACT/STATS/table4c6.html");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
 
$actuarial_page = explode("\n", curl_exec($ch));
curl_close($ch);

$actuarial_data = array();

for ($i = 0; $i <= count($actuarial_page); $i++) {
  $line = $actuarial_page[$i];
  if(strpos($line, '<td align="center">') !== false) {
    preg_match('/(.*)<td align="center"> (.*)<\/td>/', $line, $matches); 
    $age = trim($matches[2]);

    $male_expectancy = $actuarial_page[$i+3];
    $female_expectancy = $actuarial_page[$i+6];

    preg_match('/(.*)<td>(.*)<\/td>/', $male_expectancy, $matches); 
    $male_expectancy = trim($matches[2]);

    preg_match('/(.*)<td>(.*)<\/td>/', $female_expectancy, $matches); 
    $female_expectancy = trim($matches[2]);

    $data = $age . ',' . $male_expectancy . ',' . $female_expectancy . "\n";
    $actuarial_data[] = $data;
  }
}
unset($actuarial_data[0]);
print_r($actuarial_data);
file_put_contents('actuarial_data.csv', $actuarial_data);
?>
