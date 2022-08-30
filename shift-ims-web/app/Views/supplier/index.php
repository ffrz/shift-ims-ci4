<?php
$this->title = 'Pemasok';
$this->titleIcon = 'fa-truck';
$this->menuActive = 'purchase-order';
$this->navActive = 'supplier';
$this->addButtonLink = [
    'url' => '/suppliers/edit/0',
    'icon' => 'fa-plus',
    'text' => 'Tambah Pemasok'
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
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>URL</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= esc($item->name) ?></td>
                                <td><?= esc($item->contacts) ?></td>
                                <td><?= esc($item->address) ?></td>
                                <td><?= esc($item->url) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                    <a href="<?= base_url("/suppliers/view/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                                    <a href="<?= base_url("/suppliers/edit/$item->id") ?>" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="<?= base_url("/suppliers/delete/$item->id") ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
            columnDefs: [{ orderable: false, targets: 4 }]
        });
    });
</script>
<?= $this->endSection() ?>