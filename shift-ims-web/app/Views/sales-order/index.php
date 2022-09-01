<?php
use App\Entities\StockUpdate;
$this->title = 'Penjualan';
$this->navActive = 'sales-order';
$this->menuActive = 'sales-order';
$this->extend('_layouts/default')
?>
<?= $this->section('right-menu') ?>
<li class="nav-item">
    <a href="<?= base_url('sales-orders/add') ?>" class="btn plus-btn btn-primary mr-1" title="Baru"><i class="fa fa-plus"></i></a>
    <button class="btn btn-default plus-btn mr-2" data-toggle="modal" data-target="#modal-sm" title="Saring"><i class="fa fa-filter"></i></button>
</li>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<form method="GET" class="form-horizontal">
    <div class="modal fade" id="modal-sm">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Penyaringan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="daterange" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" name="daterange" class="form-control float-right" id="daterange" value="<?= format_date($filter->dateStart) . ' - ' . format_date($filter->dateEnd) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="status" class="col-sm-3 col-form-label">Status Order</label>
                        <div class="col-sm-9">
                            <select class="custom-select" id="status" name="status">
                                <option value="all" <?= $filter->status == 'all' ? 'selected' : '' ?>>Semua Status</option>
                                <option value="0" <?= $filter->status == 0 ? 'selected' : '' ?>>Disimpan</option>
                                <option value="1" <?= $filter->status == 1 ? 'selected' : '' ?>>Selesai</option>
                                <option value="2" <?= $filter->status == 2 ? 'selected' : '' ?>>Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2"></i> Terapkan</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="card card-primary">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="data-table display table table-bordered table-striped table-condensed center-th" style="width:100%">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Waktu</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <?php
                            $badge_class = '';
                            $url = 'view';
                            if ($item->status == StockUpdate::STATUS_CANCELED) {
                                $badge_class = 'badge-danger';
                            }
                            else if ($item->status == StockUpdate::STATUS_COMPLETED) {
                                $badge_class = 'badge-success';
                            }
                            else if ($item->status == StockUpdate::STATUS_SAVED) {
                                $badge_class = 'badge-warning';
                                $url = 'edit';
                            }
                            ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url("/sales-orders/$url/$item->id") ?>">
                                        <?= format_stock_update_code($item->type, $item->code) ?>
                                    </a>
                                    <span class="badge <?= $badge_class ?>"><?= format_stock_update_status($item->status) ?></span>
                                </td>
                                <td class="text-center"><?= $item->datetime ?> </td>
                                <td><?= $item->party_name ?></td>
                                <td class="text-right"><?= format_number($item->total_price) ?></td>
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
    DATATABLES_OPTIONS.order = [[0, 'desc']];
    $(function() {
        $('#daterange').daterangepicker({locale: { format: DATE_FORMAT }});
        $('.data-table').DataTable(DATATABLES_OPTIONS);
    });
</script>
<?= $this->endSection() ?>