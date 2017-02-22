<?php

/**
 * Created by PhpStorm.
 * User: carterlq
 * Date: 1/30/2017
 * Time: 9:32 AM
 */
class ComsDB
{
    private $server = "integration";
    private $db = "COMSdb";
    private $username = "COMSdbWriter";
    private $password = "C0m5d8wr173r";

    private $conn;

    public function __construct()
    {
        $this->conn = sqlsrv_connect($this->server, array("UID"=>$this->username,
            "PWD"=>$this->password, "Database"=>$this->db));
        if(!$this->conn)
        {
            error_log("COMSdb MSSQL Connection Failed");
        }
    }



    public function getNotificationCounts($reportClassification)
    {
        $sql = "SELECT 
                ReportClassification, 
                COUNT(reportClassification) as Total 
                FROM studentFormSubmissions 
                WHERE 
                reportClassification = '$reportClassification'
                
                GROUP BY reportClassification";

        $stmt = sqlsrv_query($this->conn, $sql, array());

        $results = array();
        if($stmt !== false)
        {
            while($info = sqlsrv_fetch_array($stmt))
            {
                array_push($results, $info);
            }
        }
        else
        {
            if( ($errors = sqlsrv_errors() ) != null)
            {
                foreach ($errors as $error)
                {
                    error_log("SQLSTATE: " . $error['SQLSTATE']);
                    error_log("code: " . $error['code']);
                    error_log("message: " . $error['message']);
                }
            }
        }

        return $results;
    }

    public function getForms()
    {
        $sql = "SELECT
                      formID as formNumber,
                      StudentID,
                      LastName + ', ' + FirstName as StudentName,
                      CAST(DateSubmitted as varchar(50)) as DateSubmitted,
                      reportNames.reportName as OriginReport,
                      studentFormSubmissions.AdditionalComments as AdditionalComments
                    FROM studentFormSubmissions
                      JOIN reportNames ON reportNames.reportShortName = studentFormSubmissions.reportClassification
                    where allList = 'yes'";

        $stmt = sqlsrv_query($this->conn, $sql, array());

        $results = array();
        if($stmt !== false)
        {
            while($info = sqlsrv_fetch_array($stmt))
            {
                array_push($results, $info);
            }
        }
        else
        {
            if( ($errors = sqlsrv_errors() ) != null)
            {
                foreach ($errors as $error)
                {
                    error_log("SQLSTATE: " . $error['SQLSTATE']);
                    error_log("code: " . $error['code']);
                    error_log("message: " . $error['message']);
                }
            }
        }

        return $results;
    }

    public function getReports($queryHelper)
    {
        //$id = $id + 1; //adding 1 because the for loop starts at 0 and the database starts at 1
        $sql = "";
        if ($queryHelper == 'all')
        {
            $sql = "SELECT DISTINCT reportName as name,
                    reportShortName as shortName,
                        reportIcon as icon,
                        reportIconColor as iconColor,
                        listLink as listLink,
                        ListDescription as ListDescription,
						grandtotals.grandtotal as GrandTotal
						--Totals.Total as Total,

                FROM reportNames
				LEFT JOIN studentFormSubmissions form ON form.reportClassification = reportNames.reportShortName
				LEFT JOIN
				(
					SELECT ReportClassification, COUNT(reportClassification) as Total FROM studentFormSubmissions GROUP BY reportClassification
				) as Totals ON Totals.reportClassification = reportNames.reportShortName
				JOIN
				(
					SELECT SUM(Total) as grandtotal FROM (

					SELECT DISTINCT reportName as name,
										reportShortName as shortName,
											reportIcon as icon,
											reportIconColor as iconColor,
											listLink as listLink,
											ListDescription as ListDescription,
											Totals.Total as Total
									FROM reportNames
									JOIN studentFormSubmissions form ON form.reportClassification = reportNames.reportShortName
									JOIN
									(
										SELECT ReportClassification, COUNT(reportClassification) as Total FROM studentFormSubmissions GROUP BY reportClassification
									) as Totals ON Totals.reportClassification = reportNames.reportShortName
									WHERE allList = 'yes') as main
				) as grandtotals ON grandtotals.grandtotal = grandtotals.grandtotal
               WHERE reportShortName = 'all'";
        }
        else if ($queryHelper == 'everythingElse')
        {
            $sql = "SELECT DISTINCT reportName as name,
                    reportShortName as shortName,
                        reportIcon as icon,
                        reportIconColor as iconColor,
                        listLink as listLink,
                        ListDescription as ListDescription,
						Totals.Total as Total
                FROM reportNames
				JOIN studentFormSubmissions form ON form.reportClassification = reportNames.reportShortName
				JOIN
				(
					SELECT ReportClassification, COUNT(reportClassification) as Total FROM studentFormSubmissions GROUP BY reportClassification
				) as Totals ON Totals.reportClassification = reportNames.reportShortName
                WHERE reportShortName NOT IN ('all')";
        }


        $stmt = sqlsrv_query($this->conn, $sql, array());

        $results = array();
        if($stmt !== false)
        {
            while($info = sqlsrv_fetch_array($stmt))
            {
                array_push($results, $info);
            }
        }
        else
        {
            if( ($errors = sqlsrv_errors() ) != null)
            {
                foreach ($errors as $error)
                {
                    error_log("SQLSTATE: " . $error['SQLSTATE']);
                    error_log("code: " . $error['code']);
                    error_log("message: " . $error['message']);
                }
            }
        }

        return $results;
    }
}