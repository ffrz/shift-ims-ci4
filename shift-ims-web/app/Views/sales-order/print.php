<?php
$orderCode = format_stock_update_code($data->type, $data->code);
$this->title = 'Order Penjualan #' . $orderCode;
?>
<?= $this->extend('_layouts/print-invoice') ?>
<?= $this->section('content') ?>
<section class="invoice">
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-laptop-code"></i> <?= esc($settings->storeName) ?>
                <small class="float-right">Invoice #<?= $orderCode ?></small>
            </h4>
            <p class="text-muted font-italic"><?= esc($settings->storeAddress) ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div>
                <table style="width:100%;">
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
                                <?= esc($data->customer->contacts) ?>
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
        <div class="col-12">
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
    <div class="row">
        <div class="col-md-6">
            <p>Catatan:<br><?= $data->notes ?></p>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3 text-center">
            Hormat kami,
            <br><br><br>
            <hr>
        </div>
    </div>
    <script>
        window.addEventListener("load", window.print());
    </script>
</section>
<?= $this->endSection() ?>