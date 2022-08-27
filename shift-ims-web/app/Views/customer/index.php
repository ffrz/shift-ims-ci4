<?php
$this->title = 'Daftar Pelanggan';
$this->titleIcon = 'fa-users';
$this->navActive = 'customer';
$this->addButtonLink = [
    'url' => '/customers/edit/0',
    'icon' => 'fa-plus',
    'text' => 'Tambah Pelanggan'
];
?>
<?= $this->extend('_layouts/default') ?>
<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-body">
        <div class="row mt-3">
            <div class="col-md-12">
                <table class="data-table display table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td><?= esc($item->name) ?></td>
                                <td><?= esc($item->contacts) ?></td>
                                <td><?= esc($item->address) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= base_url("/customers/view/$item->id") ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>
                                        <a href="<?= base_url("/customers/edit/$item->id") ?>" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="<?= base_url("/customers/delete/$item->id") ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
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
            scrollY: 400,
            "ordering": true,
            "info": true,
            "responsive": true,
            columnDefs: [{ orderable: false, targets: 3 }]
        });
    });
</script>
<?= $this->endSection() ?>