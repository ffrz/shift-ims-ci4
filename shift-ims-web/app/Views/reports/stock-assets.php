<?php
$this->title = 'Laporan';
$this->menuActive = 'report';
$this->navActive = 'stock-report';
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-body">
        <h4>Laporan Stok Aktual</h4>
        <div class="row mt-3">
            <div class="col-md-12 table-responsive">
                <table class="data-table display table table-bordered table-striped table-condensed center-th">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th colspan="2">Stok</th>
                            <th>Modal</th>
                            <th>Subtotal Modal</th>
                            <th>Harga</th>
                            <th>Subtotal Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_cost = 0;
                        $total_price = 0;
                        ?>
                        <?php foreach ($items as $item) : ?>
                            <?php
                                $total_cost += $item->cost * $item->stock;    
                                $total_price += $item->price * $item->stock;
                            ?>
                            <tr>
                                <td><?= esc($item->name) ?></td>
                                <td class="text-right"><?= format_number($item->stock) ?></td>
                                <td><?= esc($item->uom) ?></td>
                                <td class="text-right"><?= format_number($item->cost) ?></td>
                                <td class="text-right"><?= format_number($item->cost * $item->stock) ?></td>
                                <td class="text-right"><?= format_number($item->price) ?></td>
                                <td class="text-right"><?= format_number($item->price * $item->stock) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3"></th>
                            <th class="text-right">Total Modal</th>
                            <th class="text-right"><?= format_number($total_cost) ?></th>
                            <th class="text-right">Total Harga</th>
                            <th class="text-right"><?= format_number($total_price) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>