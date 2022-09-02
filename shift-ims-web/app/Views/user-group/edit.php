<?php

use \App\Entities\Acl;

$acl_resources = [
    'Pengelolaan Grup Pengguna' => [
        Acl::VIEW_USER_GROUPS => 'Mengakses Pengelolaan Grup Pengguna',
        Acl::VIEW_USER_GROUP => 'Melihat Grup Pengguna',
        Acl::ADD_USER_GROUP => 'Menambahkan Grup Pengguna',
        Acl::EDIT_USER_GROUP => 'Mengubah Grup Pengguna',
        Acl::DELETE_USER_GROUP => 'Menghapus Grup Pengguna',
    ]
];

$this->title = (!$data->id ? 'Tambah' : 'Edit') . ' Pengguna';
$this->titleIcon = 'fa-user';
$this->menuActive = 'users';
$this->navActive = 'user-group';
$this->extend('_layouts/default')
?>

<?= $this->section('content') ?>
<div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST">
        <?= csrf_field() ?>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="name">Nama Grup</label>
                    <input type="text" <?= $data->id ? 'readonly' : '' ?> class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" autofocus id="name" placeholder="Masukkan Nama Grup" name="name" value="<?= esc($data->name) ?>">
                    <?php if (!empty($errors['name'])) : ?>
                        <span class="error form-error">
                            <?= $errors['name'] ?>
                        </span>
                    <?php endif ?>
                </div>
                <div class="form-group col-md-8">
                    <label for="description">Deskripsi</label>
                    <input type="text" class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>" id="description" placeholder="Masukkan deskripsi grup" name="description" value="<?= esc($data->description) ?>">
                    <?php if (!empty($errors['description'])) : ?>
                        <span class="error form-error">
                            <?= $errors['description'] ?>
                        </span>
                    <?php endif ?>
                </div>
            </div>
            <style>custom-control label.acl{font-weight:normal;}</style>
            <div class="form-row">
                <h4>Hak Akses</h4>
            </div>
            
            <?php foreach ($acl_resources as $category => $resource): ?>
            <div class="form-row">
                <b>Pengelolaan Grup Pengguna:</b>
            </div>
            <?php foreach ($resource as $name => $label): ?>
            <div class="form-row">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox"
                        class="custom-control-input" id="<?= $name ?>"
                        name="<?= $name ?>" value="1" <?= 1 ? 'checked="checked"' : '' ?>>
                    <label class="custom-control-label" style="font-weight:normal" for="<?= $name ?>"><?= $label ?></label>
                </div>
            </div>
            <?php endforeach ?>
            <?php endforeach ?>
            
            
        </div>
        <div class="card-footer">
            <div>
                <a href="<?= base_url('/users/') ?>" class="btn btn-default mr-2"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            </div>
        </div>
    </form>
</div>
</div>
<?= $this->endSection() ?>