<?php
$orderCode = format_service_order_code($data->id);
$this->title = 'Service Order #' . $orderCode;
$this->navActive = 'service-order';
?>
<?php $this->extend('_layouts/default') ?>
<?= $this->section('right-menu') ?>
    <li class="nav-item">
        <a href="<?= base_url('service-orders/edit/0') ?>" class="btn plus-btn btn-primary mr-1" title="Baru"><i class="fa fa-plus"></i></a>
    </li>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-laptop-code"></i> <?= esc($settings->storeName) ?>
            </h4>
            <p class="text-muted font-italic"><?= esc($settings->storeAddress) ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h4>Order</h4>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:8rem">No. Order</th>
                        <td style="width:1rem">:</td>
                        <td>#<?= $orderCode ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>:</td>
                        <td><?= format_date($data->date) ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:</td>
                        <td><?= format_service_order_status($data->status) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Pelanggan</h4>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:8rem">Nama</th>
                        <td style="width:1rem">:</td>
                        <td>
                            <a href="<?= base_url("customers/view/$data->customer_id") ?>">
                            <?= esc($data->customer_name) ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Kontak</th>
                        <td>:</td>
                        <td><a href="<?= esc(wa_send_url($data->customer_contacts)) ?>" target="blank"><?= esc($data->customer_contacts) ?></a></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>:</td>
                        <td><?= esc($data->customer_address) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Perangkat</h4>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:8rem">Perangkat</th>
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
                        <th>Status</th>
                        <td>:</td>
                        <td><?= format_service_status($data->service_status) ?></td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>:</td>
                        <td><?= esc($data->notes) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Biaya-biaya</h4>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:10rem">Biaya Perkiraan</th>
                        <td style="width:1rem">:</td>
                        <td>Rp. <?= format_number($data->estimated_cost) ?></td>
                    </tr>
                    <tr>
                        <th>Uang Muka</th>
                        <td>:</td>
                        <td>Rp. <?= format_number($data->down_payment) ?></td>
                    </tr>
                    <tr>
                        <th>Biaya Peralatan</th>
                        <td>:</td>
                        <td>Rp. <?= format_number($data->parts_cost) ?></td>
                    </tr>
                    <tr>
                        <th>Biaya Servis</th>
                        <td>:</td>
                        <td>Rp. <?= format_number($data->service_cost) ?></td>
                    </tr>
                    <tr>
                        <th>Biaya Lain-lain</th>
                        <td>:</td>
                        <td>Rp. <?= format_number($data->other_cost) ?></td>
                    </tr>
                    <tr>
                        <th>Total Biaya</th>
                        <td>:</td>
                        <td>Rp. <?= format_number($data->total_cost) ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:</td>
                        <td><?= format_service_order_payment_status($data->payment_status) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <a href="<?= base_url("service-orders/view/$data->id?print=1") ?>" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>