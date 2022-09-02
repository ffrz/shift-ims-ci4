<?php
$this->title = (!$data->id ? 'Tambah' : 'Edit') . ' Pengguna';
$this->titleIcon = 'fa-user';
$this->menuActive = 'users';
$this->navActive = 'users';
$this->extend('_layouts/default')
?>

<?= $this->section('content') ?>
<div class="card card-primary col-md-8">
    <form class="form-horizontal quick-form" method="POST">
        <?= csrf_field() ?>
        <div class="card-body">
            <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <input type="text" <?= $data->id ? 'readonly' : '' ?> class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>" autofocus id="username" placeholder="Username" name="username" value="<?= esc($data->username) ?>">
                    <?php if (!$data->id) : ?>
                        <span class="text-muted">(tidak bisa diganti)</span>
                        <?php if (!empty($errors['username'])) : ?>
                            <span class="error form-error">
                                <?= $errors['username'] ?>
                            </span>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="fullname" class="col-sm-4 col-form-label">Nama Lengkap</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control <?= !empty($errors['fullname']) ? 'is-invalid' : '' ?>" id="fullname" placeholder="Nama Lengkap" name="fullname" value="<?= esc($data->fullname) ?>">
                    <?php if (!empty($errors['fullname'])) : ?>
                        <span class="error form-error">
                            <?= $errors['fullname'] ?>
                        </span>
                    <?php endif ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="group_id" class="col-sm-4 col-form-label">Grup Pengguna</label>
                <div class="col-sm-8">
                    <select class="custom-select select2" id="group_id" name="group_id">
                        <option value="" <?= !$data->group_id ? 'selected' : '' ?>>-- Pilih Grup Pengguna --</option>
                        <?php foreach ($userGroups as $group) : ?>
                            <option value="<?= $group->id ?>" <?= $data->group_id == $group->id ? 'selected' : '' ?> title="<?= esc($group->description) ?>">
                                <?= esc($group->name) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">Kata Sandi</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" id="password" placeholder="Kata Sandi" name="password" value="<?= esc($data->password) ?>">
                    <p class="text-muted">Isi untuk mengganti kata sandi.</p>
                    <?php if (!empty($errors['password'])) : ?>
                        <span class="error form-error">
                            <?= $errors['password'] ?>
                        </span>
                    <?php endif ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">Status</label>
                <div class="col-sm-8">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input " id="active" name="active" value="1" <?= $data->active ? 'checked="checked"' : '' ?>>
                        <label class="custom-control-label" for="active" title="Akun aktif dapat login">Aktif</label>
                    </div>
                    <p class="text-muted">Akun aktif dapat login.</p>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">Hak Akses</label>
                <div class="col-sm-8">
                <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input " id="is_admin" name="is_admin" value="1" <?= $data->is_admin ? 'checked="checked"' : '' ?>>
                        <label class="custom-control-label" for="is_admin" title="Akun pengguna pengelola">Administrator</label>
                    </div>
                    <p class="text-muted">Akun administrator memiliki hak akses penuh pada sistem.</p>
                </div>
            </div>
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