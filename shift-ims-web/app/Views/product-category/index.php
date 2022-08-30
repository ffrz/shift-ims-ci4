<?php
$this->title = 'Kategori Produk';
$this->titleIcon = 'fa-boxes';
$this->menuActive = 'inventory';
$this->navActive = 'product-category';
$this->addButtonLink = [
    'url' => '/product-categories/add',
    'icon' => 'fa-plus',
    'text' => 'Tambah Kategori Produk'
];
$this->extend('_layouts/default')
?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-body">
        <div class="row mt-3">
            <div class="col-md-12">
                <table class="data-table display table table-bordered table-striped table-condensed center-th">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah Produk</th>
                            <th style="max-width:10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= esc($item->name) ?></td>
                                <td class="text-center"><a href="<?= base_url("/products?category_id=$item->id") ?>"><?= format_number($item->count) ?> produk</a></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                    <a href="<?= base_url("/product-categories/edit/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="<?= base_url("/product-categories/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
            order: [[0, 'asc']],
            paging: true,
            "ordering": true,
            "info": true,
            "responsive": true,
            columnDefs: [{ orderable: false, targets: 2 }]
        });
    });
</script>
<?= $this->endSection() ?>