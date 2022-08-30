<?php

$this->title = 'Riwayat Stok';
$this->titleIcon = 'fa-clock-rotate-left';
$this->menuActive = 'inventory';
$this->navActive = 'stock-update';
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
                <table id="customer-table" class="data-table display table table-bordered table-striped table-condensed center-th">
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
                                    <a href="<?= base_url("stock-updates/view/$item->id") ?>" class="btn btn-primary btn-sm mr-2"><i class="fa fa-eye"></i></a>
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
            scrollY: 400,
            length: 50,
            "ordering": true,
            "info": true,
            "responsive": true,
        });
    });
</script>
<?= $this->endSection() ?>