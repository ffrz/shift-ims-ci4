<?php

$this->title = 'Laporan Penjualan berdasarkan Kategori';
$this->menuActive = 'report';
$this->navActive = 'sales-by-category';
$this->extend('_layouts/default')

?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-body">
        <form method="GET">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Tanggal:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" name="daterange" class="form-control float-right" id="daterange"
                            value="<?= format_date($filter->dateStart) . ' - ' . format_date($filter->dateEnd) ?>">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter mr-2"></i> Terapkan</button>
                </div>
            </div>
        </form>

        <div class="row mt-3">
            <div class="col-md-12">
            <div class="table-responsive">
                <table class="data-table display table table-bordered table-striped table-condensed center-th">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Modal (Rp.)</th>
                            <th>Omzet (Rp.)</th>
                            <th>Laba / Rugi (Rp.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $total_cost = 0;
                            $total_price = 0;
                            $total_profit = 0;
                        ?>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <?php
                                    $total_cost += $item->cost;
                                    $total_price += $item->price;
                                    $total_profit += $item->profit;
                                ?>
                                <td><?= $item->name ?></td>
                                <td class="text-right"><?= format_number($item->cost) ?></td>
                                <td class="text-right"><?= format_number($item->price) ?></td>
                                <td class="text-right"><?= format_number($item->profit) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center">Total</th>
                            <th class="text-right"><?= format_number($total_cost) ?></th>
                            <th class="text-right"><?= format_number($total_price) ?></th>
                            <th class="text-right"><?= format_number($total_profit) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('footscript') ?>
<script>
    $(function() {
        $('#daterange').daterangepicker({locale: {
            format: 'DD-MM-YYYY'
        }});
    });
</script>
<?= $this->endSection() ?>