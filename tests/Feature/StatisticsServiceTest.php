<?php declare(strict_types=1);

namespace App;

use Carbon\Carbon;

//App::bind('StatisticsService', 'StatisticsServiceTest');

class StatisticsServiceTest implements StatisticsService
{
    public function date_sort($a, $b) {
        //Used with usort to sort pulled month_of_service dates
        return strtotime(substr($a, 0, 10)) - strtotime(substr($b, 0, 10));
    }

    public function getOrderStatistics(int $orderId, \DateTimeInterface $monthOfService)
    {
        $rev = array();
        $deliveredImpressions = array();
        $avgEPCM = 0;
        $ctr = 0;

        $query = DeliveredRevenue::select('order_id', 'order_name', 'job_id', 'month_of_service', 'revenue', 'delivered_impressions')
            ->where('order_id',$orderId)
            ->where('month_of_service',$monthOfService)
            ->get();

        if($query->isEmpty())
        {
            //No DB entries for where criteria. Return null.
            $this->count = 0;
            $this->orderId = null;
            $this->orderName = null;
            $this->jobId = null;
            $this->totalRevenue = null;
            $this->totalImpressions = null;
            $this->totalECPM = null;
            $this->averageECPM = null;
            return $this;
        }

        foreach ($query as $row)
        {
            $this->count = 0;

            //order_id, order_name, and job_id, guaranteed to be same based on query where clases.
            $this->orderId = $row->order_id;
            $this->orderName = $row->order_name;
            $this->jobId = $row->job_id;

            //Store revenue and impressions into arrays to calculate averageEPCM.
            $rev[$ctr] = $row->revenue;
            $deliveredImpressions[$ctr] = $row->delivered_impressions;

            //increase array counter
            $ctr++;
            
        }
        //Sum the revenue from each returned record.
        $this->totalRevenue = $query->sum('revenue');
        $this->totalImpressions = $query->sum('delivered_impressions');
        
        //if $this->totalImpressions == 0, just set $this->totalECPM to 0 to avoid division by zero.
        if($this->totalImpressions == 0)
        {
            $this->totalECPM = 0;
        }
        else
        {
            $this->totalECPM = $this->totalRevenue / ($this->totalImpressions / 1000);
        }

        //Calculate average eCPM (eCPM per individual revenue, devided by revenue count).
        for ($i=0; $i<$ctr; $i++)
        {
            //if delivered_impressions[$i] == 0, just set $this->averageECPM to 0 to avoid division by zero.
            if($deliveredImpressions[$i] == 0)
            {
                $avgEPCM += 0;
            }
            else
            {
                //Sum individual eCPMs.
                $avgEPCM += $rev[$i] / ($deliveredImpressions[$i] / 1000);
            }
        }
        //Averaged summed eCPM by count.
        $this->averageECPM = $avgEPCM / $ctr;
        $this->count = $ctr;

        return $this;
    }

    public function getJobStatistics(string $jobCode)
    {
        $rev = array();
        $deliveredImpressions = array();
        $monthOfService = array();
        $avgEPCM = 0;
        $expectedRevenue = false;
        $ctr = 0;
        
        $query = Job::select('id', 'name', 'job_code', 'expected_revenue')
            ->where('job_code',$jobCode)->get();
        
        if($query->isEmpty())
        {
            //No DB entries for where criteria. Return null.
            $this->count = 0;
            $this->jobName = null;
            $this->jobCode = null;
            $this->jobId = null;
            $this->orderId = null;
            $this->orderName = null;
            $this->monthOfServiceCount = null;
            $this->totalRevenue = null;
            $this->totalImpressions = null;
            $this->totalECPM = null;
            $this->averageECPM = null;
            $this->revenueTargetMet = null;
            return $this;
        }

        foreach ($query as $row)
        {
            //Pull job_id and expected_revenue for job to determine if revenue target was met. 
            $this->jobName = $row->name;
            $this->jobCode = $row->job_code;
            $this->jobId = $row->id;
            $expectedRevenue = $row->expected_revenue;
        }

        $query = DeliveredRevenue::select('order_id', 'order_name', 'job_id', 'month_of_service', 'revenue', 'delivered_impressions')
            ->where('job_id',$this->jobId)
            ->get();

        if($query->isEmpty())
        {
            //No DB entries for where criteria. Return null.
            $this->count = 0;
            $this->jobName = null;
            $this->jobCode = null;
            $this->jobId = null;
            $this->orderId = null;
            $this->orderName = null;
            $this->monthOfServiceCount = null;
            $this->totalRevenue = null;
            $this->totalImpressions = null;
            $this->totalECPM = null;
            $this->averageECPM = null;
            $this->revenueTargetMet = null;
            return $this;
        }
        
        foreach ($query as $row)
        {
            //order_id, order_name, and job_id, guaranteed to be same based on query where clases 
            $this->orderId = $row->order_id;
            $this->orderName = $row->order_name;

            //Store revenue and impressions into arrays to calculate averageEPCM
            $rev[$ctr] = $row->revenue;
            $deliveredImpressions[$ctr] = $row->delivered_impressions;

            //Pull month_of_service to calculate date range, and change to string to sort
            $monthOfService[$ctr] = $row->month_of_service->toDateTimeString();
            //echo 'MOS: ' . $monthOfService[$ctr] . PHP_EOL;

            //increase array counter
            $ctr++;
            
        }
        
        //Sort dates
        usort($monthOfService, array($this, "date_sort"));

        //Now that dates are sorted, compare first and last
        $date_format = 'Y-m-d H:i:s';
        $month1 = date_format(date_create_from_format($date_format, $monthOfService[0]), 'n');
        $month2 = date_format(date_create_from_format($date_format, $monthOfService[$ctr-1]), 'n');
        $this->monthOfServiceCount = ($month2 - $month1) + 1;
        
        //Sum the revenue from each returned record
        $this->totalRevenue = $query->sum('revenue');
        $this->totalImpressions = $query->sum('delivered_impressions');

        //if $this->totalImpressions == 0, just set $this->totalECPM to 0 to avoid division by zero.
        if($this->totalImpressions == 0)
        {
            $this->totalECPM = 0;
        }
        else
        {
            $this->totalECPM = $this->totalRevenue / ($this->totalImpressions / 1000);
        }

        //Calculate average eCPM (eCPM per individual revenue, devided by revenue count).
        for ($i=0; $i<$ctr; $i++)
        {
            //if delivered_impressions[$i] == 0, just set $this->averageECPM to 0 to avoid division by zero.
            if($deliveredImpressions[$i] == 0)
            {
                $avgEPCM += 0;
            }
            else
            {
                //Sum individual eCPMs.
                $avgEPCM += $rev[$i] / ($deliveredImpressions[$i] / 1000);
            }
        }
        //Averaged summed eCPM by count
        $this->averageECPM = $avgEPCM / $ctr;

        //Check if expected revenue was met
        if($expectedRevenue == $this->totalRevenue)
        {
            $this->revenueTargetMet = true;
        }
        else
        {
            $this->revenueTargetMet = false;
        }

        $this->count = $ctr;

        return $this;
    }
}
