<?php
$this->title = 'Servis';
$this->titleIcon = 'fa-screwdriver-wrench';
$this->menuActive = 'service-order';
$this->navActive = 'service-order';
$this->addButtonLink = [
    'url' => '/service-orders/edit/0',
    'icon' => 'fa-plus',
    'text' => 'Tambah Servis'
];
$this->navActive = 'service-order';
$this->extend('_layouts/default');
?>
<?= $this->section('right-menu') ?>
<li class="nav-item">
<a href="<?= base_url('service-orders/edit/0') ?>" class="btn plus-btn btn-primary mr-1" title="Baru"><i class="fa fa-plus"></i></a>
    <button class="btn plus-btn btn-default mr-2" data-toggle="modal" data-target="#modal-sm" title="Saring"><i class="fa fa-filter"></i></button>
</li>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
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
                        <label for="order_status" class="col-sm-3 col-form-label">Status Order</label>
                            <div class="col-sm-9">
                            <select class="custom-select" id="order_status" name="order_status">
                                <option value="-1" <?= $filter->order_status == -1 ? 'selected' : '' ?>>Semua Status</option>
                                <option value="1" <?= $filter->order_status == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="2" <?= $filter->order_status == 2 ? 'selected' : '' ?>>Selesai</option>
                                <option value="3" <?= $filter->order_status == 3 ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                            </div>
                    </div>
                    <div class="form-group row">
                    <label for="service_status" class="col-sm-3 col-form-label">Status Servis</label>
                            <div class="col-sm-9">
                            <select class="custom-select" id="service_status" name="service_status">
                                <option value="-1" <?= $filter->service_status == -1 ? 'selected' : '' ?>>Semua Status</option>
                                <option value="0" <?= $filter->service_status == 0 ? 'selected' : '' ?>>Diterima</option>
                                <option value="1" <?= $filter->service_status == 1 ? 'selected' : '' ?>>Sedang Diperiksa</option>
                                <option value="2" <?= $filter->service_status == 2 ? 'selected' : '' ?>>Sedang Diperbaiki</option>
                                <option value="3" <?= $filter->service_status == 3 ? 'selected' : '' ?>>Selesai: Sukses</option>
                                <option value="4" <?= $filter->service_status == 4 ? 'selected' : '' ?>>Selesai: Gagal</option>
                            </select>
                            </div>
                    </div>
                    <div class="form-group row">
                    <label for="payment_status" class="col-sm-3 col-form-label">Status Pembayaran</label>
                            <div class="col-sm-9">
                            <select class="custom-select" id="payment_status" name="payment_status">
                                <option value="-1" <?= $filter->payment_status == -1 ? 'selected' : '' ?>>Semua Status</option>
                                <option value="0" <?= $filter->payment_status == 0 ? 'selected' : '' ?>>Belum Dibayar</option>
                                <option value="1" <?= $filter->payment_status == 1 ? 'selected' : '' ?>>Dibayar Sebagian</option>
                                <option value="2" <?= $filter->payment_status == 2 ? 'selected' : '' ?>>Lunas</option>
                            </select>
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
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Perangkat</th>
                            <th>Status Servis</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= format_service_order_code($item->id) ?></td>
                                <td><?= $item->date ?></td>
                                <td><?= esc($item->customer_name) ?></td>
                                <td><?= esc($item->device) ?></td>
                                <td><?= format_service_status($item->service_status) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= base_url("/service-orders/view/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                        <a href="<?= base_url("/service-orders/duplicate/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-copy"></i></a>
                                        <a href="<?= base_url("/service-orders/edit/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="<?= base_url("/service-orders/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </div>
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
    DATATABLES_OPTIONS.columnDefs = [{ orderable: false, targets: 5 }];
    $(function() {
        $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
</script>
<?= $this->endSection() ?>