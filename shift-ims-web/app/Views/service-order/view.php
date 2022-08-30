<?php
$orderCode = format_service_order_code($data->id);
$this->title = 'Service Order #' . $orderCode;
$this->navActive = 'service-order';
?>
<?php $this->extend('_layouts/default') ?>
<?= $this->section('content') ?>
<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-laptop-code"></i> <?= esc($settings->storeName) ?>
                <small class="float-right">Service Order #<?= $orderCode ?></small>
            </h4>
            <p class="text-muted font-italic"><?= esc($settings->storeAddress) ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Tanggal Penerimaan</th>
                        <td style="width:1rem">:</td>
                        <td><?= format_date($data->date) ?></td>
                    </tr>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <td>:</td>
                        <td><?= esc($data->customer_name) ?></td>
                    </tr>
                    <tr>
                        <th>Kontak</th>
                        <td>:</td>
                        <td><?= esc($data->customer_contacts) ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>:</td>
                        <td><?= esc($data->customer_address) ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">Biaya Perkiraan</th>
                        <td>:</td>
                        <td><?= format_number($data->estimated_cost) ?></td>
                    </tr>
                    <tr>
                        <th>Uang Muka</th>
                        <td>:</td>
                        <td><?= format_number($data->down_payment) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table">

                    <tr>
                        <th style="width:50%">Perangkat</th>
                        <td>:</td>
                        <td><?= esc($data->device) ?></td>
                    </tr>
                    <tr>
                        <th>Aksesoris</th>
                        <td>:</td>
                        <td><?= esc($data->accessories) ?></td>
                    </tr>
                    <tr>
                        <th>Keluhan</th>
                        <td>:</td>
                        <td><?= esc($data->problems) ?></td>
                    </tr>
                    <tr>
                        <th>Kerusakan</th>
                        <td>:</td>
                        <td><?= esc($data->damages) ?></td>
                    </tr>
                    <tr>
                        <th>Tindakan</th>
                        <td>:</td>
                        <td><?= esc($data->actions) ?></td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>:</td>
                        <td><?= esc($data->notes) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row no-print mt-3">
        <div class="col-12">
            <a href="<?= base_url("service-orders/view/$data->id?print=1") ?>" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>