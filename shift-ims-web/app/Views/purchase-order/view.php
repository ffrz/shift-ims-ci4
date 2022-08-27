<?php
$orderCode = format_stock_update_code($data->type, $data->code);
$this->title = 'Order Pembelian #' . $orderCode;
$this->navActive = 'purchase-order';
$this->menuActive = 'purchase-order';
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div>
                    <table style="width:100%;">
                        <tr>
                            <td style="width:25%;">Pemasok</td>
                            <td style="width:5%;">:</td>
                            <td>
                                <?php if ($data->supplier): ?>
                                    <a href="<?= base_url("suppliers/view/{$data->supplier->id}") ?>"><?= esc($data->supplier->name) ?></a>
                                <?php else: ?>
                                    -
                                <?php endif ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Kontak</td>
                            <td>:</td>
                            <td>
                                <?php if ($data->supplier) : ?>
                                    <a href="<?= wa_send_url($data->supplier->contacts) ?>" target="_blank"><?= esc($data->supplier->contacts) ?></a>
                                <?php endif ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Url</td>
                            <td>:</td>
                            <td><?= esc($data->supplier ? $data->supplier->url : '-') ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td><?= esc($data->supplier ? $data->supplier->address : '-') ?></td>
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
                <table class="table table-bordered table-striped table-condensed">
                    <thead style="text-align:center;">
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Kwantitas</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Jumlah</th>
                            <th>Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_cost = $total_price = 0 ?>
                        <?php foreach ($data->items as $item) : ?>
                            <?php $subtotal_cost = abs($item->quantity) * $item->cost ?>
                            <?php $subtotal_price = abs($item->quantity) * $item->price ?>
                            <tr>
                                <td class="text-right"><?= $item->id ?></td>
                                <td><?= esc($item->name) ?></td>
                                <td class="text-right"><?= format_number(abs($item->quantity)) ?></td>
                                <td><?= esc($item->uom) ?></td>
                                <td class="text-right"><?= format_number($item->cost) ?></td>
                                <td class="text-right"><?= format_number($subtotal_cost) ?></td>
                                <td class="text-right"><?= format_number($item->price) ?></td>
                            </tr>
                            <?php $total_cost += $subtotal_cost ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-right">Total</th>
                            <th class="text-right"><?= format_number($total_cost) ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>Catatan:<br><?= $data->notes ?></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>