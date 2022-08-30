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
<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mt-3" id="filter">
                <form class="form-horizontal quick-form" method="GET">
                <?= csrf_field() ?>
                    <div>
                        <?= csrf_field() ?>
                        <div class="form-group row">
                            <label for="order_status" class="col-sm-2 col-form-label">Status Order</label>
                            <div class="col-sm-2">
                            <select class="custom-select" id="order_status" name="order_status">
                                <option value="-1" <?= $filter->order_status == -1 ? 'selected' : '' ?>>Semua Status</option>
                                <option value="1" <?= $filter->order_status == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="2" <?= $filter->order_status == 2 ? 'selected' : '' ?>>Selesai</option>
                                <option value="3" <?= $filter->order_status == 3 ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                            </div>
                            <label for="service_status" class="col-sm-2 col-form-label">Status Servis</label>
                            <div class="col-sm-2">
                            <select class="custom-select" id="service_status" name="service_status">
                                <option value="-1" <?= $filter->service_status == -1 ? 'selected' : '' ?>>Semua Status</option>
                                <option value="0" <?= $filter->service_status == 0 ? 'selected' : '' ?>>Diterima</option>
                                <option value="1" <?= $filter->service_status == 1 ? 'selected' : '' ?>>Sedang Diperiksa</option>
                                <option value="2" <?= $filter->service_status == 2 ? 'selected' : '' ?>>Sedang Diperbaiki</option>
                                <option value="3" <?= $filter->service_status == 3 ? 'selected' : '' ?>>Selesai: Sukses</option>
                                <option value="4" <?= $filter->service_status == 4 ? 'selected' : '' ?>>Selesai: Gagal</option>
                            </select>
                            </div>
                            <label for="payment_status" class="col-sm-2 col-form-label">Status Pembayaran</label>
                            <div class="col-sm-2">
                            <select class="custom-select" id="payment_status" name="payment_status">
                                <option value="-1" <?= $filter->payment_status == -1 ? 'selected' : '' ?>>Semua Status</option>
                                <option value="0" <?= $filter->payment_status == 0 ? 'selected' : '' ?>>Belum Dibayar</option>
                                <option value="1" <?= $filter->payment_status == 1 ? 'selected' : '' ?>>Dibayar Sebagian</option>
                                <option value="2" <?= $filter->payment_status == 2 ? 'selected' : '' ?>>Lunas</option>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter mr-2"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <table id="customer-table" class="data-table display table table-bordered table-striped table-condensed center-th">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Perangkat</th>
                            <th>Status Servis</th>
                            <th>Aksi</th>
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
    $(function() {
        $('.data-table').DataTable({
            order: [[0, 'desc']],
            paging: true,
            length: 50,
            "ordering": true,
            "info": true,
            "responsive": true,
        });
    });
</script>
<?= $this->endSection() ?>