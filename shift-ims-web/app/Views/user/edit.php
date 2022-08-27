<?php
$this->title = (!$data->id ? 'Tambah' : 'Edit') . ' Pengguna';
$this->titleIcon = 'fa-user';
$this->menuActive = 'users';
$this->navActive = 'users';
$this->extend('_layouts/default')
?>

<?= $this->section('content') ?>
<div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST">
    <?= csrf_field() ?>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="username">Username <span class="text-muted">(tidak bisa diganti)</span></label>
                    <input type="text" <?= $data->id ? 'readonly' : '' ?> class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>"
                        autofocus id="username" placeholder="Username" name="username" value="<?= esc($data->username) ?>">
                    <?php if (!empty($errors['username'])) : ?>
                        <span class="error form-error">
                            <?= $errors['username'] ?>
                        </span>
                    <?php endif ?>
                </div>
                <div class="form-group col-md-4">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" class="form-control <?= !empty($errors['fullname']) ? 'is-invalid' : '' ?>"
                        id="fullname" placeholder="Nama Lengkap" name="fullname" value="<?= esc($data->fullname) ?>">
                    <?php if (!empty($errors['fullname'])) : ?>
                        <span class="error form-error">
                            <?= $errors['fullname'] ?>
                        </span>
                    <?php endif ?>
                </div>
                <div class="form-group col-md-4">
                    <label for="password">Kata Sandi <span class="text-muted">(Isi untuk mengganti kata sandi.)</span></label>
                    <input type="text" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>"
                        id="password" placeholder="Kata Sandi" name="password" value="<?= esc($data->password) ?>">
                    <?php if (!empty($errors['password'])) : ?>
                        <span class="error form-error">
                            <?= $errors['password'] ?>
                        </span>
                    <?php endif ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input " id="active" name="active" value="1" <?= $data->active ? 'checked="checked"' : '' ?>>
                        <label class="custom-control-label" for="active">Aktif</label>
                    </div>
                </div>
                <div class="form-group col-md-2">
                <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input " id="is_admin" name="is_admin" value="1" <?= $data->is_admin ? 'checked="checked"' : '' ?>>
                        <label class="custom-control-label" for="is_admin">Administrator</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div>
                <a href="<?= base_url('/users/') ?>" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                <button type="submit" class="btn btn-primary float-right"><i class="fas fa-save mr-1"></i> Simpan</button>
            </div>
        </div>
    </form>
</div>
</div>
<?= $this->endSection() ?>