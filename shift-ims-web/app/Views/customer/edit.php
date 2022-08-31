<?php
$this->title = (!$data->id ? 'Tambah' : 'Edit') . ' Pelanggan';
$this->titleIcon = 'fa-user-plus';
$this->navActive = 'edit-customer';
$this->menuActive = 'customer';
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST">
        <div class="card-body">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" id="name" placeholder="Nama" name="name" value="<?= esc($data->name) ?>">
                    <?php if (!empty($errors['name'])) : ?>
                        <span class="error form-error">
                            <?= $errors['name'] ?>
                        </span>
                    <?php endif ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="contacts">Kontak</label>
                    <input type="text" class="form-control" id="contacts" placeholder="Kontak" name="contacts" value="<?= esc($data->contacts) ?>">
                </div><?php /*
                <div class="form-group col-md-4">
                    <label for="url">URL</label>
                    <input type="text" class="form-control" id="url" placeholder="URL" name="url" value="<?= esc($data->url) ?>">
                </div> */ ?>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="address">Alamat</label>
                    <textarea class="form-control" id="address" placeholder="Alamat" name="address"><?= esc($data->address) ?></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-12">
                <label for="active">Status</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" value="1" <?= $data->active ? 'checked="checked"' : '' ?>>
                        <label class="custom-control-label" for="active">Aktif</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="<?= base_url('/suppliers/') ?>" class="btn btn-default mr-2"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i> Simpan</button>
        </div>
    </form>
</div>
</div>
<?= $this->endSection() ?>