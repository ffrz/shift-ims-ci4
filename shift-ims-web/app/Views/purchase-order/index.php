<?php

$this->title = 'Order Pembelian';
$this->titleIcon = 'fa-truck-ramp-box';
$this->menuActive = 'purchase-order';
$this->navActive = 'purchase-order';
$this->addButtonLink = [
    'url' => '/purchase-orders/add',
    'icon' => 'fa-plus',
    'text' => 'Tambah Order Pembelian'
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
                <table id="customer-table" class="data-table display table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th class="text-center">Pemasok</th>
                            <th class="text-center">Waktu</th>
                            <th class="text-right">Total</th>
                            <th>Catatan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><a href="<?= base_url("/purchase-orders/view/$item->id") ?>"><?= format_stock_update_code($item->type, $item->code) ?></a></td>
                                <td><?= $item->party_name ?></td>
                                <td class="text-center"><?= format_datetime($item->datetime) ?></td>
                                <td class="text-right"><?= format_number($item->total_cost) ?></td>
                                <td><?= $item->notes ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url("/purchase-orders/view/$item->id") ?>" class="btn btn-default btn-sm mr-2"><i class="fa fa-eye"></i></a>
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
        $('#daterange').daterangepicker({locale: {
            format: 'DD-MM-YYYY'
        }});
        $('.data-table').DataTable({
            paging: true,
            length: 50,
            "ordering": true,
            "info": true,
            "responsive": true,
        });
    });
</script>
<?= $this->endSection() ?>