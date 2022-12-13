<?php
$orderCode = format_service_order_code($data->id);
$this->title = 'Service Order #' . $orderCode;
$this->navActive = 'service-order';
?>
<?php $this->extend('_layouts/print-invoice') ?>
<?= $this->section('content') ?>
<?php for ($i = 1; $i <= 2; $i++): ?>
<section class="invoice mb-4">
    <div class="row">
        <div class="col-12">
            <h4 style="margin:0;">
                <i class="fas fa-laptop-code"></i> <?= esc($settings->storeName) ?>
                <small class="float-right">Penerimaan Servis #<?= $orderCode ?></small>
            </h4>
            <p class="font-italic" style="margin:0;"><?= esc($settings->storeAddress) ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-2">
            <div class="table-responsive">
                <table class="invoice-table pad-xs">
                    <tr>
                        <th style="width:10rem">Tanggal Diterima</th>
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
                        <th>Biaya Perkiraan</th>
                        <td>:</td>
                        <td>Rp. <?= format_number($data->estimated_cost) ?></td>
                    </tr>
                    <tr>
                        <th>Uang Muka</th>
                        <td>:</td>
                        <td>Rp. <?= format_number($data->down_payment) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="invoice-table pad-xs">

                    <tr>
                        <th style="width:10rem">Perangkat</th>
                        <td style="width:1rem">:</td>
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
    <script>
        window.addEventListener("load", window.print());
    </script>
</section>
<?php endfor ?>
<?= $this->endSection() ?>