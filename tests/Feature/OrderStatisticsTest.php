<?php declare(strict_types=1);

namespace Tests\Feature;

use App\DeliveredRevenue;
use App\Job;
use App\StatisticsService;
use App\StatisticsServiceTest;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OrderStatisticsTest extends TestCase
{
    use DatabaseMigrations;

    public function testOrderOne(): void
    {
        $this->seedOrders();

        /** @var StatisticsService $service */
        $service = $this->app->make(StatisticsServiceTest::class);

        $statistics = $service->getOrderStatistics(1, Carbon::parse("2020-01-01 00:00:00"));

        echo 'OrderStatisticsTest::testOrderOne Success: ' . PHP_EOL .
            'Records found: ' . $statistics->count . " " . PHP_EOL .
            'Order ID: ' . $statistics->orderId . " " . PHP_EOL .
            'Order Name: ' . $statistics->orderName . " " . PHP_EOL .
            'Job ID: ' . $statistics->jobId . " " . PHP_EOL .
            'Total Revenue: ' . $statistics->totalRevenue . " " . PHP_EOL .
            'Total Impressions: ' . $statistics->totalImpressions . " " . PHP_EOL .
            'Total ECPM: ' . $statistics->totalECPM . " " . " " . PHP_EOL .
            'Average ECPM: ' . $statistics->averageECPM . " " . PHP_EOL . PHP_EOL;
        $this->assertNotNull($statistics, "OrderStatisticsTest::testOrderOne Failure: No existing data for this criteria.");
        $this->assertSame(1, $statistics->orderId);
        $this->assertSame("Seeded Order One", $statistics->orderName);
        $this->assertEqualsWithDelta(10.00, $statistics->totalECPM, 0.01);
        $this->assertEqualsWithDelta(10.00, $statistics->averageECPM, 0.01);
        $this->assertEqualsWithDelta(30.00, $statistics->totalRevenue, 0.01);
        $this->assertEquals(3000, $statistics->totalImpressions);
    }

    public function testOrderTwo(): void
    {
        $this->seedOrders();

        /** @var StatisticsService $service */
        $service = $this->app->make(StatisticsServiceTest::class);

        $statistics = $service->getOrderStatistics(2, Carbon::parse("2020-01-01 00:00:00"));

        echo 'OrderStatisticsTest::testOrderTwo Success: ' . PHP_EOL .
            'Records found: ' . $statistics->count . " " . PHP_EOL .
            'Order ID: ' . $statistics->orderId . " " . PHP_EOL .
            'Order Name: ' . $statistics->orderName . " " . PHP_EOL .
            'Job ID: ' . $statistics->jobId . " " . PHP_EOL .
            'Total Revenue: ' . $statistics->totalRevenue . " " . PHP_EOL .
            'Total Impressions: ' . $statistics->totalImpressions . " " . PHP_EOL .
            'Total ECPM: ' . $statistics->totalECPM . " " . " " . PHP_EOL .
            'Average ECPM: ' . $statistics->averageECPM . " " . PHP_EOL . PHP_EOL;
        $this->assertNotNull($statistics, "OrderStatisticsTest::testOrderTwo Failure: No existing data for this criteria.");
        $this->assertSame(2, $statistics->orderId);
        $this->assertSame("Seeded Order Two", $statistics->orderName);
        $this->assertEqualsWithDelta(10.66, $statistics->totalECPM, 0.01);
        $this->assertEqualsWithDelta(7.34, $statistics->averageECPM, 0.01);
        $this->assertEqualsWithDelta(1028.38, $statistics->totalRevenue, 0.01);
        $this->assertEquals(96500, $statistics->totalImpressions);
    }

    public function testOrderThree()
    {
        $this->seedOrders();

        /** @var StatisticsService $service */
        $service = $this->app->make(StatisticsServiceTest::class);

        $statistics = $service->getOrderStatistics(3, Carbon::parse("2020-01-01 00:00:00"));

        echo 'OrderStatisticsTest::testOrderThree Success: ' . PHP_EOL .
            'Records found: ' . $statistics->count . " " . PHP_EOL .
            'Order ID: ' . $statistics->orderId . " " . PHP_EOL .
            'Order Name: ' . $statistics->orderName . " " . PHP_EOL .
            'Job ID: ' . $statistics->jobId . " " . PHP_EOL .
            'Total Revenue: ' . $statistics->totalRevenue . " " . PHP_EOL .
            'Total Impressions: ' . $statistics->totalImpressions . " " . PHP_EOL .
            'Total ECPM: ' . $statistics->totalECPM . " " . " " . PHP_EOL .
            'Average ECPM: ' . $statistics->averageECPM . " " . PHP_EOL . PHP_EOL;
        $this->assertNotNull($statistics, "OrderStatisticsTest::testOrderThree Failure: No existing data for this criteria.");
        $this->assertSame(3, $statistics->orderId);
        $this->assertSame("Seeded Order Three", $statistics->orderName);
        $this->assertEqualsWithDelta(0.00, $statistics->totalECPM, 0.01);
        $this->assertEqualsWithDelta(0.00, $statistics->averageECPM, 0.01);
        $this->assertEqualsWithDelta(0.00, $statistics->totalRevenue, 0.01);
        $this->assertEquals(500, $statistics->totalImpressions);
    }

    public function testOrderFour()
    {
        $this->seedOrders();

        /** @var StatisticsService $service */
        $service = $this->app->make(StatisticsServiceTest::class);

        $statistics = $service->getOrderStatistics(4, Carbon::parse("2020-01-01 00:00:00"));

        echo 'OrderStatisticsTest::testOrderFour Success: ' . PHP_EOL .
            'Records found: ' . $statistics->count . " " . PHP_EOL .
            'Order ID: ' . $statistics->orderId . " " . PHP_EOL .
            'Order Name: ' . $statistics->orderName . " " . PHP_EOL .
            'Job ID: ' . $statistics->jobId . " " . PHP_EOL .
            'Total Revenue: ' . $statistics->totalRevenue . " " . PHP_EOL .
            'Total Impressions: ' . $statistics->totalImpressions . " " . PHP_EOL .
            'Total ECPM: ' . $statistics->totalECPM . " " . " " . PHP_EOL .
            'Average ECPM: ' . $statistics->averageECPM . " " . PHP_EOL . PHP_EOL;
        $this->assertNotNull($statistics, "OrderStatisticsTest::testOrderFour Failure: No existing data for this criteria.");
        $this->assertSame(4, $statistics->orderId);
        $this->assertSame("Seeded Order Four", $statistics->orderName);
        $this->assertEquals(null, $statistics->totalECPM);
        $this->assertEquals(null, $statistics->averageECPM);
        $this->assertEqualsWithDelta(100.00, $statistics->totalRevenue, 0.01);
        $this->assertEquals(0, $statistics->totalImpressions);
    }

    public function testOrderFiveDoesntExist()
    {
        $this->seedOrders();

        /** @var StatisticsService $service */
        $service = $this->app->make(StatisticsServiceTest::class);

        $statistics = $service->getOrderStatistics(5, Carbon::parse("2020-01-01 00:00:00"));

        echo 'OrderStatisticsTest::testOrderFiveDoesntExist Success: ' . PHP_EOL .
            'Records found: ' . $statistics->count . " " . PHP_EOL .
            'Order ID: ' . $statistics->orderId . " " . PHP_EOL .
            'Order Name: ' . $statistics->orderName . " " . PHP_EOL .
            'Job ID: ' . $statistics->jobId . " " . PHP_EOL .
            'Total Revenue: ' . $statistics->totalRevenue . " " . PHP_EOL .
            'Total Impressions: ' . $statistics->totalImpressions . " " . PHP_EOL .
            'Total ECPM: ' . $statistics->totalECPM . " " . " " . PHP_EOL .
            'Average ECPM: ' . $statistics->averageECPM . " " . PHP_EOL . PHP_EOL;
        $this->assertNotNull($statistics, "OrderStatisticsTest::testOrderFiveDoesntExist Failure: No existing data for this criteria.");
        $this->assertSame(null, $statistics->orderId);
        $this->assertSame(null, $statistics->orderName);
        $this->assertSame(null, $statistics->totalECPM);
        $this->assertSame(null, $statistics->averageECPM);
        $this->assertSame(null, $statistics->totalRevenue);
        $this->assertSame(null, $statistics->totalImpressions);
    }

    private function seedOrders(): void
    {
        $job = Job::forceCreate([
            "name" => "Seeded Job",
            "job_code" => "J1",
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
            "month_of_service" => Carbon::parse("2020-01-01 00:00:00"),
            "delivered_impressions" => 1000,
            "revenue" => 10.00,
            "job_id" => $job->id,
        ]);

        DeliveredRevenue::forceCreate([
            "order_id" => 2,
            "order_name" => "Seeded Order Two",
            "month_of_service" => Carbon::parse("2020-01-01 00:00:00"),
            "delivered_impressions" => 90000,
            "revenue" => 1005.60,
            "job_id" => $job->id,
        ]);

        DeliveredRevenue::forceCreate([
            "order_id" => 2,
            "order_name" => "Seeded Order Two",
            "month_of_service" => Carbon::parse("2020-01-01 00:00:00"),
            "delivered_impressions" => 6500,
            "revenue" => 22.78,
            "job_id" => $job->id,
        ]);

        DeliveredRevenue::forceCreate([
            "order_id" => 3,
            "order_name" => "Seeded Order Three",
            "month_of_service" => Carbon::parse("2020-01-01 00:00:00"),
            "delivered_impressions" => 500,
            "revenue" => 0.00,
            "job_id" => $job->id,
        ]);

        DeliveredRevenue::forceCreate([
            "order_id" => 4,
            "order_name" => "Seeded Order Four",
            "month_of_service" => Carbon::parse("2020-01-01 00:00:00"),
            "delivered_impressions" => 0,
            "revenue" => 100.00,
            "job_id" => $job->id,
        ]);
    }
}
