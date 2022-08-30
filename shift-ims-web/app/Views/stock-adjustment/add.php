<?php
$this->title = 'Stok Opname';
$this->titleIcon = 'fa-sliders';
$this->navActive = 'stock-adjustment';
$this->menuActive = 'inventory';

$this->extend('_layouts/default'); ?>
<?= $this->section('headstyles') ?>
<style>
    #editor td input {
        max-width: 4rem;
        text-align: right !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-primary">
    <form method="post">
        <?= csrf_field() ?>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="datetime" class="">Waktu</label>
                    <input type="datetime" readonly class="form-control" name="datetime" value="<?= esc($data->datetime) ?>">
                </div>
                <div class="form-group col-md-8">
                    <label for="datetime" class="">Catatan</label>
                    <textarea class="form-control" name="notes"><?= esc($data->notes) ?></textarea>
                </div>
            </div>
            <?php if (!empty($error)): ?>
                <div class="text-danger">
                    <p><?= $error ?></p>
                </div>
            <?php endif ?>
            <div class="row mt-3">
                <div class="col-md-12">
                    <table id="editor" class="data-table display table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Satuan</th>
                                <th class="text-center" style="width:15%">Stok Terrekam</th>
                                <th class="text-center" style="width:15%">Stok Sebenarnya</th>
                                <th class="text-center" style="width:15%">Selisih Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item) : ?>
                                <tr>
                                    <td><?= $item->name ?></td>
                                    <td><?= $item->uom ?></td>
                                    <td class="text-right"><?= $item->stock ?></td>
                                    <td class="text-center">
                                        <input data-id="<?= $item->id ?>" data-stock="<?= $item->stock ?>" class="balance text-center" type="number" name="actual_quantities[<?= $item->id ?>]" value="<?= $item->stock ?>">
                                    </td>
                                    <td id="balance-<?= $item->id ?>" class="text-center">
                                        <input readonly type="number" class="text-center" tabindex="-1" name="balances[<?= $item->id ?>]" id="balance_<?= $item->id ?>" value="<?= $item->balance ?>" ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="<?= base_url('/stock-adjustments/') ?>" class="btn btn-default mr-2"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('footscript') ?>
<script>
    $(function() {
        $('input.balance').change(function() {
            var self = $(this);
            var id = self.data('id');
            var stock = parseInt(self.data('stock'));
            var qty = parseInt(
                self.val());
            console.log(stock, qty);
            var balance = qty - stock;
            $('#balance_' + id).val(balance);
        });
    });
</script>
<?= $this->endSection() ?>