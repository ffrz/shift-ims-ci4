<?php

namespace App\Controllers;

use App\Entities\StockUpdate;

class DashboardController extends BaseController
{
    public function index()
    {
        $date = date('Y-m-d');
        $type = StockUpdate::UPDATE_TYPE_SALES_ORDER;
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');

        return view('dashboard', [
            'todaySales' => $this->_getTodaySales($type, $date),
            'activeServiceOrder' => $this->_getActiveServiceOrder(),
            'monthlySalesIncome' => $this->_getMonthlySalesIncome($type, $startDate, $endDate),
            'monthlyServiceIncome' => $this->_getMonthlyServiceIncome($startDate, $endDate),
            'sales_vs_cost_data' => $this->_getSalesVsCostData($type, $startDate, $endDate)
        ]);
    }

    /**
     * Untuk mengambil data total transaksi stock update pada hari yang ditentukan
     * @param $type jenis stok update
     * @param $date tanggal transaksi
     * @return float total penjualan
     */
    private function _getTodaySales($type, $date)
    {
        return (float)$this->db->query("
            select sum(total_price) total from stock_updates where type=$type
            and date(datetime)='$date'
        ")->getRow()->total;
    }

    /**
     * Untuk mengambil data total transaksi stock update pada hari yang ditentukan
     * @param $type jenis stok update
     * @param $date tanggal transaksi
     * @return int total servis yang sedang aktif
     */
    private function _getActiveServiceOrder()
    {
        return (int)$this->db->query(
            'select count(0) count from service_orders where status=1'
        )->getRow()->count;
    }

    private function _getMonthlySalesIncome($type, $startDate, $endDate)
    {
        return (float)$this->db->query("
            select sum(total_price) total from stock_updates where type=$type
            and (date(datetime) between '$startDate' and '$endDate')
        ")->getRow()->total;
    }

    private function _getMonthlyServiceIncome($startDate, $endDate)
    {
        return (float)$this->db->query("
            select sum(total_cost) total from service_orders where
            date between '$startDate' and '$endDate' and status=2
        ")->getRow()->total;
    }

    private function _getSalesVsCostData($type, $startDate, $endDate)
    {
        $sales_vs_cost_data = [];
        $sales_vs_cost_data['days'] = [];

        $result = $this->db->query("
            select date(datetime) as date, total_cost, total_price
            from stock_updates
            where type=$type and date(datetime) between '$startDate' and '$endDate'
        ")->getResultObject();

        $sales_by_dates = [];
        foreach ($result as $item) {
            $day = date('d', strtotime($item->date));
            if (!isset($sales_by_dates[$day])) {
                $sales_by_dates[$day] = [
                    'cost' => 0,
                    'price' => 0,
                    'revenue' => 0,
                ];
            }
            $sales_by_dates[$day]['cost'] += $item->total_cost;
            $sales_by_dates[$day]['price'] += $item->total_price;
            $sales_by_dates[$day]['revenue'] += ($item->total_price - $item->total_cost);
        }

        $dayCount = date('t');

        $sales_vs_cost_data['sales'] = [];
        $sales_vs_cost_data['costs'] = [];
        $sales_vs_cost_data['revenues'] = [];
        for ($i = 1; $i <= $dayCount; $i++) {
            $sales_vs_cost_data['days'][]  = $i;
            $sales_vs_cost_data['costs'][] = isset($sales_by_dates[$i]['cost']) ? $sales_by_dates[$i]['cost'] : 0;
            $sales_vs_cost_data['sales'][] = isset($sales_by_dates[$i]['price']) ? $sales_by_dates[$i]['price'] : 0;
            $sales_vs_cost_data['revenues'][] = isset($sales_by_dates[$i]['revenue']) ? $sales_by_dates[$i]['revenue'] : 0;
        }

        return $sales_vs_cost_data;
    }
}
