<?php

$this->title = 'Rincian <code>#' . format_stock_update_code($data->type, $data->code) . '</code>';
$this->navActive = 'stock-adjustment';
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-body">
        <div class="form-row">
        <div class="form-group col-md-4">
                <label for="id" class="">Kode</label>
                <input type="text" id="id" class="form-control" readonly
                    value="<?= format_stock_update_code($data->type, $data->code) ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="datetime" class="">Waktu</label>
                <input type="text" id="datetime" class="form-control" readonly value="<?= esc($data->datetime) ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="type" class="">Jenis Penyesuaian</label>
                <input type="text" id="type" class="form-control" readonly value="<?= format_stock_update_type($data->type) ?>">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <table id="" class="data-table display table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Selish</th>
                            <th class="text-center">Modal</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Subtotal<br>Modal</th>
                            <th class="text-center">Subtotal<br>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="7" class="text-center font-italic">Tidak ada rincian</td>
                            </tr>
                        <?php endif ?>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= $item->name ?></td>
                                <td><?= $item->uom ?></td>
                                <td class="text-center"><?= $item->quantity > 0 ? '+' . $item->quantity : $item->quantity ?></td>
                                <td class="text-right"><?= format_number($item->cost) ?></td>
                                <td class="text-right"><?= format_number($item->price) ?></td>
                                <td class="text-right"><?= format_number($item->quantity * $item->cost) ?></td>
                                <td class="text-right"><?= format_number($item->quantity * $item->price) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5">Total Selisih</th>
                            <th class="text-right"><?= format_number($data->total_cost) ?></th>
                            <th class="text-right"><?= format_number($data->total_price) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="form-group col-md-12">
                <label for="datetime" class="">Catatan</label>
                <textarea readonly class="form-control"><?= esc($data->notes) ?></textarea>
            </div>
            <div class="form-group col-md-12">
                <form method="post" action="<?= base_url("stock-updates/delete/$data->id") ?>">
                <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $data->id ?>">
                    <input type="hidden" name="goto" value="<?= base_url('/stock-updates/') ?>">
                    <button type="submit" name="action" value="delete_revert" class="btn btn-danger mr-2"><i class="fa fas fa-trash mr-2"></i>Hapus (Revert Stok)</button>
                    <button type="submit" name="action" value="delete_ignore" class="btn btn-danger"><i class="fa fas fa-trash mr-2"></i>Hapus (Biarkan Stok)</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>