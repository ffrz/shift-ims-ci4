<?php
$this->title = 'Produk';
$this->titleIcon = 'fa-cubes';
$this->menuActive = 'inventory';
$this->navActive = 'product';
$this->addButtonLink = [
    'url' => '/products/edit/0',
    'icon' => 'fa-plus',
    'text' => 'Tambah Produk'
];
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-body">
            <form class="form-horizontal quick-form" method="GET">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="type">Jenis</label>
                        <select class="custom-select" id="type" name="type">
                            <option value="all" <?= $filter->type == 'all' ? 'selected' : '' ?>>Semua Jenis</option>
                            <option value="0" <?= $filter->type == 0 ? 'selected' : '' ?>>Non Stok</option>
                            <option value="1" <?= $filter->type == 1 ? 'selected' : '' ?>>Stok</option>
                            <option value="2" <?= $filter->type == 2 ? 'selected' : '' ?>>Jasa</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="active">Status</label>
                        <select class="custom-select" id="active" name="active">
                            <option value="all" <?= $filter->active == 'all' ? 'selected' : '' ?>>Semua Status</option>
                            <option value="1" <?= $filter->active == 1 ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= $filter->active == 0 ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                    <label for="name">Kategori</label>
                        <select class="custom-select select2" id="category_id" name="category_id">
                            <option value="all" <?= $filter->category_id == 'all' ? 'selected' : '' ?>>Semua Kategori</option>
                            <option value="0" <?= $filter->category_id == 0 ? 'selected' : '' ?>>Tidak Ditentukan</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category->id ?>" <?= $filter->category_id == $category->id ? 'selected' : '' ?>>
                                    <?= esc($category->name) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">Pemasok</label>
                        <select class="custom-select select2" id="supplier_id" name="supplier_id">
                            <option value="all" <?= $filter->supplier_id == 'all' ? 'selected' : '' ?>>Semua Supplier</option>
                            <option value="0" <?= $filter->supplier_id == 0 ? 'selected' : '' ?>>Tidak Ditentukan</option>
                            <?php foreach ($suppliers as $supplier) : ?>
                                <option value="<?= $supplier->id ?>" <?= $filter->supplier_id == $supplier->id ? 'selected' : '' ?>>
                                    <?= esc($supplier->name) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fas fa-filter mr-2"></i> Terapkan</button>
                    </div>
                </div>
            </form>

        <div class="row mt-3">
            <div class="col-md-12">
                <table class="data-table display table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Pemasok</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Modal</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= esc($item->name) ?></td>
                                <td><?= esc($item->supplier_name) ?></td>
                                <td><?= format_product_type($item->type) ?></td>
                                <td><?= esc($item->category_name) ?></td>
                                <td class="text-right"><?= format_number($item->stock) ?></td>
                                <td><?= esc($item->uom) ?></td>
                                <td class="text-right"><?= format_number($item->cost) ?></td>
                                <td class="text-right"><?= format_number($item->price) ?></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Actions">
                                    <a href="<?= base_url("/products/view/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                    <a href="<?= base_url("/products/duplicate/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-copy"></i></a>
                                    <a href="<?= base_url("/products/edit/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="<?= base_url("/products/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('footscript') ?>
<script>
    $(function() {
        $('.data-table').DataTable({
            paging: true,
            "pageLength": 50,
            "ordering": true,
            "info": true,
            "responsive": true,
            columnDefs: [{ orderable: false, targets: 8 }]
        });
    });
    $(document).ready(function() {
        $('.select2').select2();
    });
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });
</script>
<?= $this->endSection() ?>