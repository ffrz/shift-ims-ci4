<?php
$orderCode = format_stock_update_code($data->type, $data->code);
$this->title = 'Order Penjualan #' . $orderCode;
$this->navActive = 'sales-order';
$this->menuActive = 'sales-order';
$this->extend('_layouts/default');
?>
<?= $this->section('content') ?>
<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-md-6">
            <div class="table-responsive">
                <table>
                    <tr>
                        <td style="width:25%;">Atas Nama</td>
                        <td style="width:5%;">:</td>
                        <td><?= esc($data->customer ? $data->customer->name : '-') ?></td>
                    </tr>
                    <tr>
                        <td>Kontak</td>
                        <td>:</td>
                        <td>
                            <?php if ($data->customer) : ?>
                                <a href="<?= wa_send_url($data->customer->contacts) ?>" target="_blank"><?= esc($data->customer->contacts) ?></a>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><?= esc($data->customer ? $data->customer->address : '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <table style="width:100%;">
                    <tr>
                        <td style="width:25%;">No Invoice:</td>
                        <td style="width:5%;">:</td>
                        <td>#<?= $orderCode ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td><?= format_date($data->datetime) ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>Selesai</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 table-responsive">
            <table class="table">
                <thead style="text-align:center;">
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Kwantitas</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Jumlah Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0 ?>
                    <?php foreach ($data->items as $item) : ?>
                        <?php $subtotal = abs($item->quantity) * $item->price ?>
                        <tr>
                            <td class="text-right"><?= $item->id ?></td>
                            <td><?= esc($item->name) ?></td>
                            <td class="text-right"><?= format_number(abs($item->quantity)) ?></td>
                            <td><?= esc($item->uom) ?></td>
                            <td class="text-right"><?= format_number($item->price) ?></td>
                            <td class="text-right"><?= format_number($subtotal) ?></td>
                        </tr>
                        <?php $total += $subtotal ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4"></th>
                        <th class="text-right">Total</th>
                        <th class="text-right"><?= format_number($total) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row no-print mt-3">
        <div class="col-12">
            <a href="<?= base_url("sales-orders/view/$data->id?print=1") ?>" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>