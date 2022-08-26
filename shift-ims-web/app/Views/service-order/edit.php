<?php
if ($duplicate) {
    $this->title = 'Duplikat Servis';
} else {
    $this->title = (!$data->id ? 'Tambah' : 'Edit') . ' Servis';
}
$this->titleIcon = 'fa-hand-holding-medical';
$this->navActive = 'service-order';
$this->menuActive = 'add-service-order';
?>

<?php $this->extend('_layouts/default') ?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST">
        <div class="card-body">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $data->id ?>">
            <div class="row">
                <h5 class="col-md-12">Info Order</h5>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="order_id">Order #</label>
                    <input type="text" class="form-control" id="order_id" name="id" value="<?= esc($data->id) ?>" placeholder="Otomatis" readonly>
                </div>
                <div class="form-group col-md-4">
                    <label for="date">Tanggal</label>
                    <div class="input-group date" id="date" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#date"
                            name="date" value="<?= esc($data->date) ?>"/>
                        <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status</label>
                    <select class="custom-select" id="status" name="status">
                        <option value="1" <?= $data->status == 1 ? 'selected' : '' ?>>Aktif</option>
                        <option value="2" <?= $data->status == 2 ? 'selected' : '' ?>>Selesai</option>
                        <option value="3" <?= $data->status == 3 ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <h5 class="col-md-12">Info Pelanggan</h5>
            </div>
            <input type="hidden" id="customer_id" name="customer_id" value="<?= $data->customer_id ?>">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="customer_name" class="">Pelanggan</label>
                    <select class="custom-select select2" id="customer_name" name="customer_name">
                        <option <?= $data->customer_id == 0 ? 'selected' : '' ?>
                            data-id="0"
                            data-contacts=""
                            data-address="">-- Pilih Pelanggan --</option>
                        <?php foreach ($customers as $customer) : ?>
                            <option value="<?= $customer->name ?>" <?= $data->customer_name == $customer->name ? 'selected' : '' ?>
                                data-id="<?= $customer->id ?>"
                                data-contacts="<?= esc($customer->contacts) ?>"
                                data-address="<?= esc($customer->address) ?>">
                                <?= esc($customer->name) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <?php if (!empty($errors['customer_name'])) : ?>
                        <span class="error form-error">
                            <?= $errors['customer_name'] ?>
                        </span>
                    <?php endif ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="customer_contacts" class="">Kontak</label>
                    <input type="text" class="form-control <?= !empty($errors['customer_contacts']) ? 'is-invalid' : '' ?>" id="customer_contacts" name="customer_contacts" value="<?= esc($data->customer_contacts) ?>" placeholder="Kontak">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="customer_address">Alamat</label>
                    <textarea class="form-control" id="customer_address" name="customer_address"><?= esc($data->customer_address) ?></textarea>
                </div>
            </div>
            <div class="row">
                <h5 class="col-md-12">Info Perangkat</h5>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="device" class="">Perangkat</label>
                    <input type="text" class="form-control" id="device" name="device" value="<?= esc($data->device) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="accessories" class="">Aksesoris</label>
                    <input type="text" class="form-control" id="accessories" name="accessories" value="<?= esc($data->accessories) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="problems" class="">Keluhan</label>
                    <input type="text" class="form-control" id="problems" name="problems" value="<?= esc($data->problems) ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="damages" class="">Kerusakan</label>
                    <input type="text" class="form-control" id="damages" name="damages" value="<?= esc($data->damages) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="actions" class="">Tindakan</label>
                    <input type="text" class="form-control" id="actions" name="actions" value="<?= esc($data->actions) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="service_status" class="">Status Servis</label>
                    <select class="custom-select" id="service_status" name="service_status">
                        <option value="0" <?= $data->service_status == 0 ? 'selected' : '' ?>>Diterima</option>
                        <option value="1" <?= $data->service_status == 1 ? 'selected' : '' ?>>Sedang Diperiksa</option>
                        <option value="2" <?= $data->service_status == 2 ? 'selected' : '' ?>>Sedang Diperbaiki</option>
                        <option value="3" <?= $data->service_status == 3 ? 'selected' : '' ?>>Selesai: Sukses</option>
                        <option value="4" <?= $data->service_status == 4 ? 'selected' : '' ?>>Selesai: Gagal</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <h5 class="col-md-12">Biaya Awal</h5>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="estimated_cost" class="">Biaya Perkiraan</label>
                    <input type="text" class="form-control" id="estimated_cost" name="estimated_cost" value="<?= esc($data->estimated_cost) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="down_payment" class="">Uang Muka</label>
                    <input type="text" class="form-control" id="down_payment" name="down_payment" value="<?= esc($data->down_payment) ?>">
                </div>
            </div>
            <div class="row">
                <h5 class="col-md-12">Biaya Real</h5>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="parts_cost" class="">Biaya Peralatan</label>
                    <input type="number" lang="id" class="form-control" id="parts_cost" name="parts_cost" value="<?= esc($data->parts_cost) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="service_cost" class="">Biaya Servis</label>
                    <input type="number" lang="id" class="form-control" id="service_cost" name="service_cost" value="<?= esc($data->service_cost) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="other_cost" class="">Biaya Lain-lain</label>
                    <input type="number" lang="id" class="form-control" id="other_cost" name="other_cost" value="<?= esc($data->other_cost) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="total_cost" class="">Jumlah Biaya</label>
                    <input type="number" lang="id" class="form-control" id="total_cost" name="total_cost" value="<?= esc($data->total_cost) ?>" readonly>
                </div>
                <div class="form-group col-md-2">
                    <label for="payment_status" class="">Status Pembayaran</label>
                    <select class="custom-select" id="payment_status" name="payment_status">
                        <option value="0" <?= $data->payment_status == 0 ? 'selected' : '' ?>>Belum Dibayar</option>
                        <option value="1" <?= $data->payment_status == 1 ? 'selected' : '' ?>>Dibayar Sebagian</option>
                        <option value="2" <?= $data->payment_status == 2 ? 'selected' : '' ?>>Lunas</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="notes">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes"><?= esc($data->notes) ?></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="<?= base_url('/service-orders') ?>" class="btn btn-default"><i class="fas fa-arrow-left mr-2"></i> Batal</a>
            <button type="submit" class="btn btn-primary float-right"><i class="fas fa-save mr-2"></i> Simpan</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('footscript') ?>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
        $('.date').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $('#customer_name').change(function() {
            var selected = $(this).find('option:selected');
            $('#customer_contacts').val(selected.data('contacts'));
            $('#customer_address').text(selected.data('address'));
            $('#customer_id').val(selected.data('id'));
        });

        function update_total_cost() {
            var a = parseInt($('#parts_cost').val());
            var b = parseInt($('#service_cost').val());
            var c = parseInt($('#other_cost').val());
            var total = a + b + c;
            $('#total_cost').val(total);
        }

        $('#parts_cost').change(update_total_cost);
        $('#service_cost').change(update_total_cost);
        $('#other_cost').change(update_total_cost);
    });
</script>
<?= $this->endSection() ?>