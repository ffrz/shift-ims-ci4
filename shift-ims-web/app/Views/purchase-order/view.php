<?php

use App\Entities\StockUpdate;

$orderCode = format_stock_update_code($data->type, $data->code);
$this->title = 'Pembelian #' . $orderCode;
$this->navActive = 'purchase-order';
$this->menuActive = 'purchase-order';
$this->extend('_layouts/default');
?>
<?= $this->section('content') ?>
<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="po-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tabcontent1-tab1" data-toggle="pill" href="#tabcontent1" role="tab" aria-controls="tabcontent1-tab1" aria-selected="false">Info Pelanggan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabcontent2-tab" data-toggle="pill" href="#tabcontent2" role="tab" aria-controls="tabcontent2-tab" aria-selected="true">Riwayat Pembayaran</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="po-tabContent">
            <div class="tab-pane fade show active table-responsive" id="tabcontent1" role="tabpanel" aria-labelledby="tabcontent1-tab1">
                <table class="table table-condensed table-striped">
                    <tr>
                        <td style="width:10rem;">No Invoice:</td>
                        <td style="width:0.5rem;">:</td>
                        <td>#<?= $orderCode ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal / Jam</td>
                        <td>:</td>
                        <td><?= format_datetime($data->datetime) ?></td>
                    </tr>
                    <tr>
                        <td>Status Pesanan</td>
                        <td>:</td>
                        <td><?= format_stock_update_status($data->status) ?></td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran</td>
                        <td>:</td>
                        <td><?= (int)$data->total_paid == (int)$data->total_price ? 'Lunas' : 'Belum Lunas' ?></td>
                    </tr>
                    <tr>
                        <td>Nama Pemasok</td>
                        <td>:</td>
                        <td><?= esc($data->supplier ? $data->supplier->name : '-') ?></td>
                    </tr>
                    <tr>
                        <td>Kontak</td>
                        <td>:</td>
                        <td>
                            <?php if (!empty($data->supplier)) : ?>
                                <a href="<?= wa_send_url($data->supplier->contacts) ?>" target="_blank"><?= esc($data->supplier->contacts) ?></a>
                            <?php else : ?>
                                <i>Tidak ada</i>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><?= esc($data->supplier ? $data->supplier->address : '-') ?></td>
                    </tr>
                    <tr>
                        <td>Catatan</td>
                        <td>:</td>
                        <td><?= esc($data->notes) ?></td>
                    </tr>
                    <tr>
                        <td>Order Lewat</td>
                        <td>:</td>
                        <td><?= esc($data->order_via) ?></td>
                    </tr>
                    <tr>
                        <td>No Nota</td>
                        <td>:</td>
                        <td><?= esc($data->external_ref_code) ?></td>
                    </tr>
                </table>
                <table class="table table-striped">
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
                        <?php if (empty($data->items)) : ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted font-italic">Belum ada item yang ditambahkan.</td>
                            </tr>
                        <?php endif ?>
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
                            <th class="text-right">Total Tagihan</th>
                            <th class="text-right"><?= format_number($data->total_price) ?></th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-right">Biaya Ekspedisi</th>
                            <th class="text-right"><?= format_number($data->expedition_cost) ?></th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-right">Biaya Lainnya</th>
                            <th class="text-right"><?= format_number($data->other_cost) ?></th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-right">Grand Total</th>
                            <th class="text-right"><?= format_number((float)($data->total_price + $data->expedition_cost + $data->other_cost)) ?></th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-right">Jumlah Bayar</th>
                            <th class="text-right"><?= format_number((float)$data->total_paid) ?></th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th class="text-right">Sisa Tagihan</th>
                            <th class="text-right"><?= format_number($data->total_price - $data->total_paid) ?></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="mt-3">
                    <div class="col-md-12">
                        <small>
                        <?php if ($data->created_by) : ?>
                            <div class="text-muted">Dibuat oleh <?= $data->created_by ?> pada <?= format_datetime($data->created_at) ?></div>
                        <?php endif ?>
                        <?php if ($data->lastmod_by) : ?>
                            <div class="text-muted">Diperbarui terakhir kali oleh <?= $data->lastmod_by ?> <?= format_datetime($data->lastmod_at) ?></div>
                        <?php endif ?>
                        </small>
                    </div>
                </div>
                <div class="mt-3">
                    <?php if ($data->status == StockUpdate::STATUS_COMPLETED) : ?>
                        <a href="<?= base_url("purchase-orders/view/$data->id?print=1") ?>" rel="noopener" target="_blank" class="btn btn-default mr-2"><i class="fas fa-print mr-1"></i> Print</a>
                    <?php endif ?>
                    <a onclick="return confirm('Hapus?')" href="<?= base_url("purchase-orders/delete/$data->id") ?>" class="btn btn-danger mr-2"><i class="fas fa-trash mr-1"></i> Hapus</a>
                </div>
            </div><!-- tab-pane -->
            <div class="tab-pane fade table-responsive" id="tabcontent2" role="tabpanel" aria-labelledby="tabcontent2-tab2">
            <table class="table table-striped">
                    <thead style="text-align:center;">
                        <tr>
                            <th>Kode TRX</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data->items)) : ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted font-italic">Belum ada item yang ditambahkan.</td>
                            </tr>
                        <?php endif ?>
                        <?php $total = 0 ?>
                        <?php foreach ($data->items as $item) : ?>
                            <?php $subtotal = abs($item->quantity) * $item->price ?>
                            <tr>
                                <td class="text-right"><?= $item->id ?></td>
                                <td><?= esc($item->name) ?></td>
                                <td class="text-right"><?= format_number(abs($item->quantity)) ?></td>
                                <td><?= esc($item->uom) ?></td>
                            </tr>
                            <?php $total += $subtotal ?>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2"></th>
                            <th class="text-right">Total Tagihan</th>
                            <th class="text-right"><?= format_number($data->total_price) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>