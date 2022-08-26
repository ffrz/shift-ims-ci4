<?php

namespace App\Controllers;

use App\Entities\Product;
use App\Entities\StockUpdate;
use DateTime;
use stdClass;

class ReportsController extends BaseController
{
    public function dailyIncomeStatement()
    {
        $dateRange = (string)$this->request->getGet('daterange');
        
        if (!($dateRange = extract_daterange($dateRange))) {
            $dateRange = [date('Y-m-01'), date('Y-m-t')];
        }

        $sales = $this->db->query('
            select date(su.datetime) date,
            total_cost cost, total_price price, total_price-total_cost profit
            from stock_updates su
            where type=' . StockUpdate::UPDATE_TYPE_SALES_ORDER . '
            and (date(datetime) between :date1: and :date2:)
            order by date(datetime) asc
        ', [
            'date1' => $dateRange[0],
            'date2' => $dateRange[1],
        ])->getResultObject();

        $items = [];
        foreach ($sales as $item) {
            if (!isset($items[$item->date])) {
                $a = new stdClass();
                $a->date = $item->date;
                $a->cost = 0;
                $a->price = 0;
                $a->profit = 0;
                $items[$item->date] = $a;
            }

            $a = $items[$item->date];
            $a->cost += $item->cost;
            $a->price += $item->price;
            $a->profit += $item->price - $item->cost;
        }

        return view('reports/daily-income-statement', [
            'daterange' => "$$dateRange[0] - $dateRange[1]",
            'items' => $items
        ]);
    }

    public function salesByCategory()
    {
        $dateRange = (string)$this->request->getGet('daterange');
        
        if (!($dateRange = extract_daterange($dateRange))) {
            $dateRange = [date('Y-m-01'), date('Y-m-t')];
        }

        $sales_details = $this->db->query('
            select
            pc.id, pc.name, sud.quantity, sud.cost, sud.price
            from stock_update_details sud
            inner join stock_updates su on su.id = sud.parent_id
            inner join products p on p.id = sud.product_id
            left join product_categories pc on pc.id = p.category_id
            where su.type=' . StockUpdate::UPDATE_TYPE_SALES_ORDER . '
            and (date(su.datetime) between :date1: and :date2:)
            order by pc.name asc
        ', [
            'date1' => $dateRange[0],
            'date2' => $dateRange[1],
        ])->getResultObject();

        $noCategory = new stdClass;
        $noCategory->id = 0;
        $noCategory->name = 'Tanpa Kategori';

        $categories = $this->db->query('select * from product_categories order by name asc')->getResultObject();
        array_unshift($categories, $noCategory);

        foreach ($categories as $category) {
            $category->quantity = 0;
            $category->cost = 0;
            $category->price = 0;
            $category->profit = 0;
            $categoriesByIds[$category->id] = $category;
        }

        foreach ($sales_details as $detail) {
            $item = $categoriesByIds[(int)$detail->id];
            $item->quantity += abs($detail->quantity);
            $item->cost += abs($detail->cost * $detail->quantity);
            $item->price += abs($detail->price * $detail->quantity);
            $item->profit += abs(($detail->price * $detail->quantity) - ($detail->cost * $detail->quantity));
        }

        return view('reports/sales-by-category', [
            'daterange' => "$$dateRange[0] - $dateRange[1]",
            'items' => $categories
        ]);
    }

    public function stockAssets()
    {
        $items = $this->db->query('
            select * from products
            where active=1 and type=' . Product::TYPE_STOCKED . '
            order by name asc
            ')->getResultObject();
        return view('reports/stock-assets', [
            'items' => $items
        ]);
    }
}
