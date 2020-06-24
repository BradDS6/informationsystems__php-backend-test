<?php

/*namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;*/

class StatisticsServiceController extends Controller implements StatisticsService
{
    //
    public function getOrderStatistics(int $orderId, \DateTimeInterface $monthOfService)
    {
        echo "Order start";
        return DeliveredRevenue::find($orderId);
    }

    public function getJobStatistics(string $jobCode)
    {
        echo $jobCode;
        //return Job::find($orderId);
        return Job::where('job_code',$jobCode);
    }
}
