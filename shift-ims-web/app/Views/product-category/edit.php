<?php
$this->title = (!$data->id ? 'Tambah' : 'Edit') . ' Kategori Produk';
$this->menuActive = 'inventory';
$this->navActive = 'product-category';
$this->extend('_layouts/default')
?>

<?= $this->section('content') ?>
<div class="card card-primary">
    <form class="form-horizontal quick-form" method="POST">
        <div class="card-body">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="name">Nama Kategori</label>
                    <input type="text" class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" autofocus id="name" placeholder="Nama" name="name" value="<?= esc($data->name) ?>">
                    <?php if (!empty($errors['name'])) : ?>
                        <span class="error form-error">
                            <?= $errors['name'] ?>
                        </span>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div>
                <a href="<?= base_url('/product-categories/') ?>" class="btn btn-default mr-2"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            </div>
        </div>
    </form>
</div>
</div>
<?= $this->endSection() ?>