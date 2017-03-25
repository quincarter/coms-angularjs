<?php
/**
 * Created by PhpStorm.
 * User: Quin
 * Date: 2/10/2017
 * Time: 11:13 AM
 */
header("Content-Type: application/json; charset=UTF-8");
require __DIR__ . "ComsDB.php";

$ComsDB = new ComsDB();

$reportName = $ComsDB->getReports('everythingElse');

$outputToJSON = "";
$outputToJSONArray = array();
$i = 0;
foreach ($reportName as $reportLongName)
{
/*    if ($outputToJSON != "")
	{
		$outputToJSON .= ",";
	}
    $outputToJSON .= '{"reportName":"'       . $reportLongName["name"]            . '",';
    $outputToJSON .= '"shortName":"'         . $reportLongName["shortName"]       . '",';
    $outputToJSON .= '"reportIcon":"'        . $reportLongName["icon"]            . '",';
    $outputToJSON .= '"listLink":"'          . $reportLongName["listLink"]        . '",';
    $outputToJSON .= '"listDescription":"'   . $reportLongName["ListDescription"] . '",';
    $outputToJSON .= '"notificationTotal":"' . $reportLongName["GrandTotal"]      . '"}';
    */
    $outputToJSON = array(
        "reportName"        =>  $reportLongName["name"],
        "shortName"         =>  $reportLongName["shortName"],
        "reportIcon"        =>  $reportLongName["icon"],
        "listLink"          =>  $reportLongName["listLink"],
        "listDescription"   =>  $reportLongName["ListDescription"],
        "notificationTotal" =>  $reportLongName["GrandTotal"]
    );

    $outputToJSONArray[$i] = $outputToJSON;
    $i++;
}

$outputToJSONArray .= '{"reports":['.$outputToJSONArray.']}';


echo $outputToJSONArray;

$fp = fopen('reports.json', 'w');
fwrite($fp, $outputToJSONArray);
fclose($fp);
?>