<?php
$this->title = (!$data->id ? 'Tambah' : 'Edit') . ' Pelanggan';
$this->titleIcon = 'fa-user-plus';
$this->navActive = 'edit-customer';
$this->menuActive = 'sales-order';
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST">
        <div class="card-body">
            <?= csrf_field() ?>
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" id="name" placeholder="Nama" name="name" value="<?= esc($data->name) ?>">
                </div>
                <?php if (!empty($errors['name'])) : ?>
                    <span class="offset-sm-2 col-sm-10 error form-error">
                        <?= $errors['name'] ?>
                    </span>
                <?php endif ?>
            </div>
            <div class="form-group row">
                <label for="contacts" class="col-sm-2 col-form-label">Kontak</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="contacts" placeholder="Kontak" name="contacts" value="<?= esc($data->contacts) ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="address" placeholder="Alamat" name="address"><?= esc($data->address) ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" value="1" <?= $data->active ? 'checked="checked"' : '' ?>>
                        <label class="custom-control-label" for="active">Aktif</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save mr-2"></i> Simpan</button>
            <a href="<?= base_url('/suppliers/') ?>" class="btn btn-default"><i class="fas fa-arrow-left mr-2"></i> Batal</a>
        </div>
    </form>
</div>
</div>
<?= $this->endSection() ?>