<?php

use App\Entities\StockUpdate;

$this->title = 'Penyesuaian Stok';
$this->titleIcon = 'fa-sliders';
$this->menuActive = 'inventory';
$this->navActive = 'stock-adjustment';
$this->addButtonLink = [
    'url' => '/stock-adjustments/add',
    'icon' => 'fa-plus',
    'text' => 'Sesuaikan Stok'
];
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-body">
        <form method="GET">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="daterange" >Tanggal:</label>
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
                <div class="form-group col-md-3">
                    <label for="status">Status:</label>
                    <select class="custom-select" id="status" name="status">
                        <option value="-1" <?= $filter->status == -1 ? 'selected' : '' ?>>Semua Status</option>
                        <option value="0" <?= $filter->status == 0 ? 'selected' : '' ?>>
                            <?= format_stock_update_status(0) ?>
                        </option>
                        <option value="1" <?= $filter->status == 1 ? 'selected' : '' ?>>
                            <?= format_stock_update_status(1) ?>
                        </option>
                        <option value="2" <?= $filter->status == 2 ? 'selected' : '' ?>>
                            <?= format_stock_update_status(2) ?>
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter mr-2"></i> Terapkan</button>
                </div>
            </div>
        </form>
        <div class="row mt-3">
            <div class="col-md-12 table-responsive">
                <table id="customer-table" class="data-table display table table-bordered table-striped table-condensed center-th" style="width:100%">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Waktu</th>
                            <th>Jenis</th>
                            <th>Selisih Modal</th>
                            <th>Selisih Harga</th>
                            <th>Catatan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= format_stock_update_code($item->type, $item->code) ?></td>
                                <td class="text-center"><?= $item->datetime ?></td>
                                <td class="text-center"><?= format_stock_update_type($item->type) ?></td>
                                <td class="text-right"><?= format_number($item->total_cost) ?></td>
                                <td class="text-right"><?= format_number($item->total_price) ?></td>
                                <td><?= $item->notes ?></td>
                                <td class="text-center">
                                    <?php if ($item->status == StockUpdate::STATUS_SAVED): ?>
                                        <a href="<?= base_url("/stock-adjustments/edit/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></a>
                                    <?php else: ?>
                                        <a href="<?= base_url("/stock-adjustments/view/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('footscript') ?>
<script>
    $(function() {
        DATATABLES_OPTIONS.order = [[1, 'desc']];
        DATATABLES_OPTIONS.columnDefs = [{ orderable: false, targets: 6 }];
        $('#daterange').daterangepicker({locale: { format: DATE_FORMAT }});
        $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
</script>
<?= $this->endSection() ?>