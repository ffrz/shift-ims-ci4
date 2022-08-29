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
                        <th>Tanggal:</th>
                        <td><?= format_date($data->date) ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">Nama Pelanggan:</th>
                        <td><?= esc($data->customer_name) ?></td>
                    </tr>
                    <tr>
                        <th>Kontak:</th>
                        <td><?= esc($data->customer_contacts) ?></td>
                    </tr>
                    <tr>
                        <th>Alamat:</th>
                        <td><?= esc($data->customer_address) ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">Biaya Perkiraan:</th>
                        <td><?= format_number($data->estimated_cost) ?></td>
                    </tr>
                    <tr>
                        <th>Uang Muka:</th>
                        <td><?= format_number($data->down_payment) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table">

                    <tr>
                        <th style="width:50%">Perangkat:</th>
                        <td><?= esc($data->device) ?></td>
                    </tr>
                    <tr>
                        <th>Aksesoris:</th>
                        <td><?= esc($data->accessories) ?></td>
                    </tr>
                    <tr>
                        <th>Keluhan:</th>
                        <td><?= esc($data->problems) ?></td>
                    </tr>
                    <tr>
                        <th>Kerusakan:</th>
                        <td><?= esc($data->damages) ?></td>
                    </tr>
                    <tr>
                        <th>Tindakan:</th>
                        <td><?= esc($data->actions) ?></td>
                    </tr>
                    <tr>
                        <th>Catatan:</th>
                        <td><?= esc($data->notes) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php if($print !== '1'): ?>
    <div class="row no-print mt-3">
        <div class="col-12">
            <a href="<?= base_url("service-orders/view/$data->id?print=1") ?>" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
        </div>
    </div>
    <?php else: ?>
        <script>
            window.addEventListener("load", window.print());
        </script>
    <?php endif ?>
</div>
<?= $this->endSection() ?>