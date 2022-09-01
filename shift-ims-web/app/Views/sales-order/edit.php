<?php

use App\Entities\StockUpdate;

$this->title = 'Penjualan #-' . format_stock_update_code($data->type, $data->code);
$this->titleIcon = 'fa-cart-plus';
$this->menuActive = 'sales-order';
$this->navActive = 'add-sales-order';
$this->extend('_layouts/default')
?>

<?= $this->section('headstyles') ?>
<style>
    #items-tbody input {
        width: 100px;
        text-align: right;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<form method="post">
    <?= csrf_field() ?>
    <div class="card card-primary">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="datetime">Waktu:</label>
                    <div class="input-group date" id="datetime" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetime" name="datetime" value="<?= esc(format_datetime($data->datetime)) ?>" />
                        <div class="input-group-append" data-target="#datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="status">Status Order:</label>
                    <select class="custom-select" id="status" name="status" disabled>
                        <option value="0" <?= $data->status == StockUpdate::STATUS_SAVED ? 'selected' : '' ?>>Disimpan</option>
                        <option value="1" <?= $data->status == StockUpdate::STATUS_COMPLETED ? 'selected' : '' ?>>Selesai</option>
                        <option value="2" <?= $data->status == StockUpdate::STATUS_CANCELED ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="code">Kode:</label>
                    <input type="text" id="code" class="form-control" value="<?= format_stock_update_code($data->type, $data->code) ?>" readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="party_id">Nama Pelanggan:</label>
                    <select class="form-control custom-select select2" id="party_id" name="party_id">
                        <option value="" <?= !$data->party_id ? 'selected' : '' ?>>Tidak Ditentukan</option>
                        <?php foreach ($customers as $customer) : ?>
                            <option value="<?= $customer->id ?>" <?= $data->party_id == $customer->id ? 'selected' : '' ?> data-contacts="<?= esc($customer->contacts) ?>" data-address="<?= esc($customer->address) ?>">
                                <?= esc($customer->name) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="party_contacts">Kontak:</label>
                    <input type="text" id="party_contacts" readonly class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="party_address">Alamat:</label>
                    <input type="text" id="party_address" readonly class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-inline">
                        <select id="product" class="custom-select select2 form-control col-md-4">
                            <option value="0">--- PILIH PRODUK ---</option>
                            <?php foreach ($products as $product) : ?>
                                <option data-price=<?= $product->price ?> data-stock="<?= $product->stock ?>" data-uom="<?= $product->uom ?>" data-cost="<?= $product->cost ?>" value="<?= $product->id ?>"><?= esc($product->name) ?></option>
                            <?php endforeach ?>
                        </select>
                        <button id="add-button" class="ml-2 btn btn-md btn-warning" type="button"><i class="fa fas fa-plus"></i> Tambahkan</button>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 table-responsive">
                    <table class="data-table display table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Kwantitas</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">-</th>
                            </tr>
                        </thead>
                        <tbody id="items-tbody">
                            <tr id="empty-items">
                                <td class="text-center font-italic" colspan="6">Item penjualan masih kosong.</td>
                            </tr>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="5">Total</th>
                                <th id="total" class="text-right">0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="datetime" class="">Catatan</label>
                    <textarea class="form-control" name="notes"><?= esc($data->notes) ?></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div>
                <p>Dibuat oleh <?= $data->created_by ?> <?= format_datetime($data->created_at) ?> | Diubah oleh <?= $data->lastmod_by ?> <?= format_datetime($data->lastmod_at) ?></p>
            </div>
            <div class="row">
                <div class="btn-group mr-4">
                    <a href="<?= base_url('/sales-orders/') ?>" class="btn btn-default"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
                    <button type="submit" name="action" value="save" class="btn btn-default"><i class="fas fa-save mr-2"></i> Simpan</button>
                </div>
                <div class="btn-group">
                    <button onclick="return confirm('Selesaikan pesanan?')" type="submit" name="action" value="complete" class="btn btn-primary"><i class="fas fa-check mr-2"></i> Selesai</button>
                    <button onclick="return confirm('Batalkan pesanan?')" type="submit" name="action" value="cancel" class="btn btn-danger"><i class="fas fa-cancel mr-2"></i> Batalkan</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('footscript') ?>
<script>
    var items = <?= json_encode($items); ?>;

    class NumberParser {
        constructor(locale) {
            const format = new Intl.NumberFormat(locale);
            const parts = format.formatToParts(12345.6);
            const numerals = Array.from({
                length: 10
            }).map((_, i) => format.format(i));
            const index = new Map(numerals.map((d, i) => [d, i]));
            this._group = new RegExp(`[${parts.find(d => d.type === "group").value}]`, "g");
            this._decimal = new RegExp(`[${parts.find(d => d.type === "decimal").value}]`);
            this._numeral = new RegExp(`[${numerals.join("")}]`, "g");
            this._index = d => index.get(d);
        }
        parse(string) {
            return (string = string.trim()
                .replace(this._group, "")
                .replace(this._decimal, ".")
                .replace(this._numeral, this._index)) ? +string : NaN;
        }
    }

    $('.select2').select2();
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });
    $('.date').datetimepicker({
        format: 'DD-MM-YYYY HH:mm:ss'
    });
    $(function() {
        $('.date').attr('readonly');

        var itemByIds = {};
        var addButton = $('#add-button');
        var productSelect = $('#product');
        var tableBody = $('#items-tbody');
        var num = 1;
        var formatter = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        });
        const parser = new NumberParser('id-ID');

        function formatNumber(number) {
            return formatter.format(number);
        }

        function strToNumber(number) {
            return parseFloat(number.replace('.', ''));
        }

        function removeRow() {
            var id = $(this).attr('id').split('-')[2];
            $(`#item-${id}`).remove();
            delete itemByIds[id];

            var rows = tableBody.children('*');
            for (num = 1; num < rows.length; num++) {
                rows[num].childNodes[0].textContent = num;
            }

            updateTotal();
            if (tableBody.children('*').length == 1)
                $('#empty-items').show();
        }

        function setFocus(e) {
            setTimeout(function() {
                e.focus();
                e.select();
            }, 5);
        }

        function updateTotal() {
            var total = 0;
            for (const id in itemByIds) {
                const subtotal = parser.parse($(`#item-subtotal-${id}`).text());
                total += subtotal;
            }
            $('#total').text(formatNumber(total));
        }

        function updateSubtotal() {
            var id = $(this).attr('id').split('-')[2];
            var self = $(this);
            var qtyElmt = $(`#item-quantity-${id}`);
            var priceElmt = $(`#item-price-${id}`);
            var subtotal = parseFloat(qtyElmt.val()) * parseFloat(priceElmt.val());

            $(`#item-subtotal-${id}`).text(formatNumber(subtotal));
            updateTotal();
        }

        function addItem(item) {
            itemByIds[item.id] = item.id;
            tableBody.append(
                `<tr id="item-${item.id}" class="items">` +
                `<td>${num++}</td>` +
                `<td>${item.name}</td>` +
                `<td class="text-center"><input type="number" class="text-right" id="item-quantity-${item.id}" name=quantities[${item.id}] value="${item.quantity}" min="1" max="99999999" style="width:5rem"></td>` +
                `<td>${item.uom}</td>` +
                `<td class="text-center"><input type="number" class="text-right" id="item-price-${item.id}" name=prices[${item.id}] value="${item.price}" max="999999999999" style="width:7rem"></td>` +
                `<td id="item-subtotal-${item.id}" class="text-right">${formatNumber(item.price)}</td>` +
                `<td class="text-center"><button id="remove-item-${item.id}" class="btn btn-sm btn-danger" type="button" title="Hapus item dari daftar."><i class="fa fa-trash"></i></button></td>` +
                `</tr>`
            );
            $(`#remove-item-${item.id}`).click(removeRow);
            $(`#item-quantity-${item.id}`).change(updateSubtotal);
            $(`#item-price-${item.id}`).change(updateSubtotal);
        }

        function resetProductSelect() {
            setTimeout(function() {
                $('#product').val('0').trigger('change');
            }, 5);
        }

        addButton.click(function() {
            var selected = $('#product').find(":selected");
            var id = selected.val();
            if (id == 0) {
                $('#product').focus();
                $('#product').click();
                return;
            }

            if (itemByIds.hasOwnProperty(id)) {
                var qtyElement = $(`#item-quantity-${id}`);
                var qty = parseInt(qtyElement.val());
                var price = parseFloat($(`#item-price-${id}`).val());

                qty += 1;
                var subtotal = qty * price;

                qtyElement.val(qty);
                $(`#item-subtotal-${id}`).text(formatNumber(subtotal));
                setFocus(qtyElement);
                updateTotal();
                resetProductSelect();
                return;
            }

            addItem({
                id: id,
                name: selected.text(),
                quantity: 1,
                uom: selected.data('uom'),
                price: selected.data('price'),
            })

            setFocus($(`#item-quantity-${id}`));
            $('#empty-items').hide();
            updateTotal();
            resetProductSelect();
        });

        productSelect.change();

        if (items.length > 0) {
            items.forEach(function(item) {
                addItem(item);
            });
            updateTotal();
            $('#empty-items').hide();
        }

        $('#party_id').change(function() {
            var selected = $(this).find('option:selected');
            $('#party_contacts').val(selected.data('contacts'));
            $('#party_address').val(selected.data('address'));
        });
        $('#party_id').change();
    });
</script>
<?= $this->endSection() ?>