<?php
$this->title = 'Laporan';
$this->menuActive = 'report';
$this->navActive = 'income-statement-report';
$this->extend('_layouts/default');
?>
<?= $this->section('right-menu') ?>
<li class="nav-item dropdown">
    <button class="btn" data-toggle="modal" data-target="#modal-sm"><i class="fa fa-filter"></i></button>
</li>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<form method="GET" class="form-horizontal">
    <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Filter</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" name="daterange" class="form-control float-right" id="daterange" value="<?= format_date($filter->dateStart) . ' - ' . format_date($filter->dateEnd) ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2"></i> Terapkan</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="card card-primary">
    <div class="card-body">
        <h4>Laporan Laba Rugi</h4>
        <p>Periode: <?= format_date($filter->dateStart) . ' - ' . format_date($filter->dateEnd) ?></p>
        <div class="row mt-3">
            <div class="col-md-12 table-responsive">
                <table class="data-table display table table-bordered table-striped table-condensed center-th">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Modal (Rp.)</th>
                            <th>Omset (Rp.)</th>
                            <th>Laba / Rugi (Rp.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_cost = 0;
                        $total_price = 0;
                        $total_profit = 0;
                        $profits = [];
                        $costs = [];
                        $prices = []
                        ?>
                        <?php foreach ($items as $item) : ?>
                            <?php
                            $total_cost += $item->cost;
                            $total_price += $item->price;
                            $total_profit += $item->profit;

                            $profits[] = $item->profit;
                            $costs[] = $item->cost;
                            $prices[] = $item->price;
                            ?>
                            <tr>
                                <td><?= format_date($item->date) ?></td>
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
                        <tr>
                            <th class="text-center">Rata-rata</th>
                            <th class="text-right"><?= empty($items) ? 0 : format_number($total_cost / count($items)) ?></th>
                            <th class="text-right"><?= empty($items) ? 0 : format_number($total_price / count($items)) ?></th>
                            <th class="text-right"><?= empty($items) ? 0 : format_number($total_profit / count($items)) ?></th>
                        </tr>
                        <tr>
                            <th class="text-center">Minimum</th>
                            <th class="text-right"><?= empty($costs) ? 0 : format_number(min($costs)) ?></th>
                            <th class="text-right"><?= empty($prices) ? 0 : format_number(min($prices)) ?></th>
                            <th class="text-right"><?= empty($profits) ? 0 : format_number(min($profits)) ?></th>
                        </tr>
                        <tr>
                            <th class="text-center">Maksimum</th>
                            <th class="text-right"><?= empty($costs) ? 0 : format_number(max($costs)) ?></th>
                            <th class="text-right"><?= empty($prices) ? 0 : format_number(max($prices)) ?></th>
                            <th class="text-right"><?= empty($profits) ? 0 : format_number(max($profits)) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('footscript') ?>
<script>
    $(function() {
        $('#daterange').daterangepicker({
            locale: {
                format: DATE_FORMAT
            }
        });
    });
</script>
<?= $this->endSection() ?>