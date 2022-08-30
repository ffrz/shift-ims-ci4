<?php
$this->title = 'Penjualan';
$this->titleIcon = 'fa-cart-shopping';
$this->navActive = 'sales-order';
$this->menuActive = 'sales-order';
$this->addButtonLink = [
    'url' => '/sales-orders/add',
    'icon' => 'fa-plus',
    'text' => 'Tambah Penjualan'
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
                <table class="data-table display table table-bordered table-striped table-condensed center-th">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Waktu</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Catatan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= format_stock_update_code($item->type, $item->code) ?></td>
                                <td class="text-center"><?= $item->datetime ?></td>
                                <td><?= $item->party_name ?></td>
                                <td class="text-right"><?= format_number($item->total_price) ?></td>
                                <td><?= $item->notes ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url("/sales-orders/view/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
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
    DATATABLES_OPTIONS.order = [[0, 'desc']];
    $(function() {
        $('#daterange').daterangepicker({locale: {
            format: DATE_FORMAT
        }});
        $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
</script>
<?= $this->endSection() ?>