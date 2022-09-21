<?php
$this->title = (!$data->id ? 'Tambah' : 'Edit') . ' Akun / Rekening';
$this->menuActive = 'finance';
$this->navActive = 'cash-account';

$this->extend('_layouts/default');
?>
<?= $this->section('content') ?>
<div class="col-md-8">
    <div class="card card-primary">
        <form class="form-horizontal quick-form" method="POST">
            <?= csrf_field() ?>
            <div class="card-body">
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Nama Akun</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" autofocus id="name" placeholder="Nama Akun / Rek" name="name" value="<?= esc($data->name) ?>">
                        <?php if (!empty($errors['name'])) : ?>
                            <span class="error form-error">
                                <?= $errors['name'] ?>
                            </span>
                        <?php endif ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="balance" class="col-sm-3 col-form-label">Saldo</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control text-right select-all-on-focus <?= !empty($errors['balance']) ? 'is-invalid' : '' ?>" id="balance" name="balance" value="<?= format_number((float)$data->balance) ?>">
                    </div>
                    <?php if (!empty($errors['balance'])) : ?>
                        <span class="offset-sm-2 col-sm-10 error form-error">
                            <?= $errors['balance'] ?>
                        </span>
                    <?php endif ?>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>