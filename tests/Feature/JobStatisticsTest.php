<?php declare(strict_types=1);

namespace Tests\Feature;

use App\DeliveredRevenue;
use App\Job;
use App\StatisticsService;
use App\StatisticsServiceTest;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class JobStatisticsTest extends TestCase
{
    use DatabaseMigrations;

    public function testJobOne()
    {
        $this->seedJobs();

        /** @var StatisticsService $service */
        $service = $this->app->make(StatisticsServiceTest::class);

        $statistics = $service->getJobStatistics("J1");

        echo 'JobStatisticsTest::testJobOne Success: ' . PHP_EOL .
            'Records found: ' . $statistics->count . " " . PHP_EOL .
            'Job Code: ' . $statistics->jobCode . " " . PHP_EOL .
            'Job Name: ' . $statistics->jobName . " " . PHP_EOL .
            'Order ID: ' . $statistics->orderId . " " . PHP_EOL .
            'Order Name: ' . $statistics->orderName . " " . PHP_EOL .
            'Job ID: ' . $statistics->jobId . " " . PHP_EOL .
            'Total Revenue: ' . $statistics->totalRevenue . " " . PHP_EOL .
            'Total Impressions: ' . $statistics->totalImpressions . " " . PHP_EOL .
            'Total ECPM: ' . $statistics->totalECPM . " " . " " . PHP_EOL .
            'Average ECPM: ' . $statistics->averageECPM . " " . " " . PHP_EOL .
            'Revenue Met?: ' . $statistics->revenueTargetMet . " " . PHP_EOL . 
            'Month Range: ' . $statistics->monthOfServiceCount . " " . PHP_EOL .PHP_EOL;
        $this->assertNotNull($statistics, "JobStatisticsTest::testJobOne Failure: No existing data for this criteria.");
        $this->assertSame("J1", $statistics->jobCode);
        $this->assertSame("Seeded Job One", $statistics->jobName);
        $this->assertEqualsWithDelta(10.00, $statistics->totalECPM, 0.01);
        $this->assertEqualsWithDelta(10.00, $statistics->averageECPM, 0.01);
        $this->assertEqualsWithDelta(30.00, $statistics->totalRevenue, 0.01);
        $this->assertEquals(3000, $statistics->totalImpressions);
        $this->assertTrue($statistics->revenueTargetMet);
        $this->assertSame(2, $statistics->monthOfServiceCount);
    }

    public function testJobTwo()
    {
        $this->seedJobs();

        /** @var StatisticsService $service */
        $service = $this->app->make(StatisticsServiceTest::class);

        $statistics = $service->getJobStatistics("J2");

        echo 'JobStatisticsTest::testJobTwo Success: ' . PHP_EOL .
            'Records found: ' . $statistics->count . " " . PHP_EOL .
            'Job Code: ' . $statistics->jobCode . " " . PHP_EOL .
            'Job Name: ' . $statistics->jobName . " " . PHP_EOL .
            'Order ID: ' . $statistics->orderId . " " . PHP_EOL .
            'Order Name: ' . $statistics->orderName . " " . PHP_EOL .
            'Job ID: ' . $statistics->jobId . " " . PHP_EOL .
            'Total Revenue: ' . $statistics->totalRevenue . " " . PHP_EOL .
            'Total Impressions: ' . $statistics->totalImpressions . " " . PHP_EOL .
            'Total ECPM: ' . $statistics->totalECPM . " " . " " . PHP_EOL .
            'Average ECPM: ' . $statistics->averageECPM . " " . " " . PHP_EOL .
            'Revenue Met?: ' . $statistics->revenueTargetMet . " " . PHP_EOL . 
            'Month Range: ' . $statistics->monthOfServiceCount . " " . PHP_EOL .PHP_EOL;
        $this->assertNotNull($statistics);
        $this->assertSame("J2", $statistics->jobCode, "JobStatisticsTest::testJobOne Failure: No existing data for this criteria.");
        $this->assertSame("Seeded Job Two", $statistics->jobName);
        $this->assertEquals(null, $statistics->totalECPM);
        $this->assertEquals(null, $statistics->averageECPM);
        $this->assertEqualsWithDelta(30.00, $statistics->totalRevenue, 0.01);
        $this->assertEquals(0, $statistics->totalImpressions);
        $this->assertFalse($statistics->revenueTargetMet);
        $this->assertSame(1, $statistics->monthOfServiceCount);
    }

    public function testJobThreeDoesntExist()
    {
        $this->seedJobs();

        /** @var StatisticsService $service */
        $service = $this->app->make(StatisticsServiceTest::class);

        $statistics = $service->getJobStatistics("J3");

        echo 'JobStatisticsTest::testJobThreeDoesntExist Success: ' . PHP_EOL .
            'Records found: ' . $statistics->count . " " . PHP_EOL .
            'Job Code: ' . $statistics->jobCode . " " . PHP_EOL .
            'Job Name: ' . $statistics->jobName . " " . PHP_EOL .
            'Order ID: ' . $statistics->orderId . " " . PHP_EOL .
            'Order Name: ' . $statistics->orderName . " " . PHP_EOL .
            'Job ID: ' . $statistics->jobId . " " . PHP_EOL .
            'Total Revenue: ' . $statistics->totalRevenue . " " . PHP_EOL .
            'Total Impressions: ' . $statistics->totalImpressions . " " . PHP_EOL .
            'Total ECPM: ' . $statistics->totalECPM . " " . " " . PHP_EOL .
            'Average ECPM: ' . $statistics->averageECPM . " " . " " . PHP_EOL .
            'Revenue Met?: ' . $statistics->revenueTargetMet . " " . PHP_EOL . 
            'Month Range: ' . $statistics->monthOfServiceCount . " " . PHP_EOL .PHP_EOL;
        $this->assertNotNull($statistics, "JobStatisticsTest::ThreeDoesntExist Failure: No existing data for this criteria.");
        $this->assertSame(null, $statistics->jobCode);
        $this->assertSame(null, $statistics->jobName);
        $this->assertSame(null, $statistics->totalECPM);
        $this->assertSame(null, $statistics->averageECPM);
        $this->assertSame(null, $statistics->totalRevenue);
        $this->assertSame(null, $statistics->totalImpressions);
        $this->assertSame(null, $statistics->revenueTargetMet);
        $this->assertSame(null, $statistics->monthOfServiceCount);
    }

    private function seedJobs(): void
    {
        $job = Job::forceCreate([
            "name" => "Seeded Job One",
            "job_code" => "J1",
            "expected_revenue" => 30.00,
        ]);

        DeliveredRevenue::forceCreate([
            "order_id" => 1,
            "order_name" => "Seeded Order One",
            "month_of_service" => Carbon::parse("2020-01-01 00:00:00"),
            "delivered_impressions" => 1000,
            "revenue" => 10.00,
            "job_id" => $job->id,
        ]);

        DeliveredRevenue::forceCreate([
            "order_id" => 1,
            "order_name" => "Seeded Order One",
            "month_of_service" => Carbon::parse("2020-01-01 00:00:00"),
            "delivered_impressions" => 1000,
            "revenue" => 10.00,
            "job_id" => $job->id,
        ]);

        DeliveredRevenue::forceCreate([
            "order_id" => 1,
            "order_name" => "Seeded Order One",
            "month_of_service" => Carbon::parse("2020-02-01 00:00:00"),
            "delivered_impressions" => 1000,
            "revenue" => 10.00,
            "job_id" => $job->id,
        ]);

        $job = Job::forceCreate([
            "name" => "Seeded Job Two",
            "job_code" => "J2",
            "expected_revenue" => 40.00,
        ]);

        DeliveredRevenue::forceCreate([
            "order_id" => 2,
            "order_name" => "Seeded Order Two",
            "month_of_service" => Carbon::parse("2020-01-01 00:00:00"),
            "delivered_impressions" => 0,
            "revenue" => 30.00,
            "job_id" => $job->id,
        ]);
    }
}
