<?php

$this->title = 'Riwayat Stok';
$this->titleIcon = 'fa-clock-rotate-left';
$this->menuActive = 'inventory';
$this->navActive = 'stock-update';
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<?= $this->section('right-menu') ?>
<li class="nav-item">
    <button class="btn btn-default plus-btn mr-2" data-toggle="modal" data-target="#modal-sm" title="Saring"><i class="fa fa-filter"></i></button>
</li>
<?= $this->endSection() ?>
<form method="GET" class="form-horizontal">
    <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Penyaringan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="daterange" class="col-sm-2 col-form-label">Tanggal</label>
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
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table id="customer-table" class="data-table display table table-bordered table-striped table-condensed center-th" style="width:100%">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Waktu</th>
                            <th>Jenis</th>
                            <th>Modal</th>
                            <th>Harga</th>
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
                                    <a href="<?= base_url("stock-updates/view/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
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
        DATATABLES_OPTIONS.order = [[1, 'DESC']];
        DATATABLES_OPTIONS.columnDefs = [{ orderable: false, targets: 6 }];
        $('#daterange').daterangepicker({locale: { format: DATE_FORMAT }});
        $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
</script>
<?= $this->endSection() ?>