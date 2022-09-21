<?php
$this->title = (!$data->id ? 'Tambah' : 'Edit') . ' Biaya';
$this->menuActive = 'finance';
$this->navActive = 'cash-transaction';
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<div class="col-md-8">
    <div class="card card-primary">
        <form class="form-horizontal quick-form" method="POST">
            <?= csrf_field() ?>
            <div class="card-body">
                <div class="form-group row">
                    <label for="datetime" class=" col-form-label col-sm-3">Tanggal</label>
                    <div class="col-sm-3">
                        <div class="input-group date" id="datetime" data-target-input="nearest">
                            <input autofocus type="text" class="form-control datetimepicker-input<?= !empty($errors['datetime']) ? 'is-invalid' : '' ?>"
                                data-target="#datetime" name="datetime" value="<?= format_date($data->datetime) ?>" />
                            <div class="input-group-append" data-target="#datetime" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <?php if (!empty($errors['datetime'])) : ?>
                            <span class="error form-error">
                                <?= $errors['datetime'] ?>
                            </span>
                        <?php endif ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="account_id" class="col-sm-3 col-form-label">Akun / Rekening</label>
                    <div class="col-sm-9">
                        <select class="custom-select select2" id="account_id" name="account_id">
                            <option value="" <?= !$data->account_id ? 'selected' : '' ?>>-- Akun / Rek --</option>
                            <?php foreach ($accounts as $account) : ?>
                                <option value="<?= $account->id ?>" <?= $data->account_id == $account->id ? 'selected' : '' ?>>
                                    <?= esc($account->name) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="category_id" class="col-sm-3 col-form-label">Kategori</label>
                    <div class="col-sm-9">
                        <select class="custom-select select2" id="category_id" name="category_id">
                            <option value="" <?= !$data->category_id ? 'selected' : '' ?>>-- Kategori --</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category->id ?>" <?= $data->category_id == $category->id ? 'selected' : '' ?>>
                                    <?= esc($category->name) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>"
                            id="description" placeholder="Deskripsi" name="description" value="<?= esc($data->description) ?>">
                        <?php if (!empty($errors['description'])) : ?>
                            <span class="error form-error">
                                <?= $errors['description'] ?>
                            </span>
                        <?php endif ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="amount" class="col-sm-3 col-form-label">Jumlah (+/-) (Rp.) </label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control text-right <?= !empty($errors['amount']) ? 'is-invalid' : '' ?>" id="amount" name="amount" value="<?= $data->amount ?>">
                        <?php if (!empty($errors['amount'])) : ?>
                            <span class="error form-error">
                                <?= $errors['amount'] ?>
                            </span>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('footscript') ?>
<script>
    $(document).ready(function() {
        $('.date').datetimepicker({
            format: 'DD-MM-YYYY'
        });
        $('.select2').select2();
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    });
</script>
<?= $this->endSection() ?>