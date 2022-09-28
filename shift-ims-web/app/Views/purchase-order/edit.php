<?php

use App\Entities\StockUpdate;

$this->title = 'Tambah Order Pembelian';
$this->navActive = 'purchase-order';
$this->extend('_layouts/default')
?>

<?= $this->section('headstyles') ?>
<link rel="stylesheet" href="<?= base_url('plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
<style>
#items-tbody input {
    width:100px;
    text-align:right;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-primary">
    <form method="post">
        <div class="card-body">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="datetime">Waktu:</label>
                    <div class="input-group date" id="datetime" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetime"
                            name="datetime" value="<?= esc(format_datetime($data->datetime)) ?>"/>
                        <div class="input-group-append" data-target="#datetime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="status">Status Order:</label>
                    <select class="custom-select" id="status" disabled>
                        <option value="<?= StockUpdate::STATUS_SAVED ?>" <?= $data->status == StockUpdate::STATUS_SAVED ? 'selected' : '' ?>>Disimpan</option>
                        <option value="<?= StockUpdate::STATUS_COMPLETED ?>" <?= $data->status == StockUpdate::STATUS_COMPLETED ? 'selected' : '' ?>>Selesai</option>
                        <option value="<?= StockUpdate::STATUS_CANCELED ?>" <?= $data->status == StockUpdate::STATUS_CANCELED ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="code">Kode:</label>
                    <input type="text" id="code" class="form-control" value="<?= format_stock_update_code($data->type, $data->code) ?>" readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="party_id">Nama Pemasok:</label>
                    <select class="form-control custom-select select2" id="party_id" name="party_id">
                        <option value="" <?= !$data->party_id ? 'selected' : '' ?>>Tidak Ditentukan</option>
                        <?php foreach ($suppliers as $supplier) : ?>
                            <option value="<?= $supplier->id ?>" <?= $data->party_id == $supplier->id ? 'selected' : '' ?>
                                data-contacts="<?= esc($supplier->contacts)?>"
                                data-address="<?= esc($supplier->address) ?>">
                                <?= esc($supplier->name) ?>
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
                <div class="form-group col-md-12">
                    <label for="product">Pilih Produk:</label>
                    <select id="product" class="form-control custom-select select2">
                        <option value="0">-- Pilih Produk --</option>
                        <?php foreach ($products as $product) : ?>
                            <option data-price=<?= $product->price ?> data-stock="<?= $product->stock ?>" data-uom="<?= $product->uom ?>" data-cost="<?= $product->cost ?>" value="<?= $product->id ?>"><?= esc($product->name) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-row mt-2">
                <button id="add-button" class="btn btn-md btn-warning" type="button"><i class="fa fas fa-plus"></i> Tambahkan</button>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 table-responsive">
                    <table id="" class="data-table display table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Kwantitas</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Harga Beli</th>
                                <th class="text-center">Harga Jual</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">-</th>
                            </tr>
                        </thead>
                        <tbody id="items-tbody">
                            <tr id="empty-items">
                                <td class="text-center font-italic" colspan="7">Item pembelian masih kosong.</td>
                            </tr>
                            <tfoot>
                                <tr>
                                    <th class="text-right" colspan="6">Total Pembelian</th>
                                    <th id="total" class="text-right">0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="order_via">Order Lewat:</label>
                    <input  id="order_via" list="order_options" type="text" name="order_via" class="form-control" value="<?= esc($data->order_via) ?>" autocomplete="off">
                    <datalist id="order_options">
                        <?php foreach ($orderOptions as $option): ?>
                            <option value="<?= esc($option->order_via) ?>">
                        <?php endforeach ?>
                    </datalist>
                </div>
                <div class="form-group col-md-3">
                    <label for="external_ref_code">No Ref / Nota:</label>
                    <input  id="external_ref_code" type="text" name="external_ref_code" class="form-control" value="<?= esc($data->external_ref_code) ?>" autocomplete="off">
                </div>
                <div class="form-group col-md-2">
                    <label for="expedition_cost">Biaya Ekspedisi:</label>
                    <input onfocus="this.select();" id="expedition_cost" type="number" name="expedition_cost" class="form-control text-right" value="<?= $data->expedition_cost ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="other_cost">Biaya Lainnya:</label>
                    <input onfocus="this.select();" id="other_cost" type="number" name="other_cost" class="form-control text-right" value="<?= $data->other_cost ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="grand_total">Grand Total:</label>
                    <input id="grand_total" type="text" readonly name="grand_total" class="form-control text-right" value="0">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="notes" class="">Catatan</label>
                    <textarea id="notes" name="notes" class="form-control"><?= esc($data->notes) ?></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="btn-group mr-2 mt-2">
                        <button type="submit" name="action" value="save" class="btn btn-default"><i class="fas fa-save mr-2"></i> Simpan</button>
                    </div>
                    <div class="btn-group mt-2">
                        <button onclick="return confirm('Selesaikan pesanan?')" type="submit" name="action" value="complete" class="btn btn-primary"><i class="fas fa-check mr-2"></i> Selesaikan</button>
                        <button onclick="return confirm('Batalkan pesanan?')" type="submit" name="action" value="cancel" class="btn btn-danger"><i class="fas fa-cancel mr-2"></i> Batalkan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('footscript') ?>
<script>
    var items = <?= json_encode($items); ?>;

    class NumberParser {
    constructor(locale) {
        const format = new Intl.NumberFormat(locale);
        const parts = format.formatToParts(12345.6);
        const numerals = Array.from({ length: 10 }).map((_, i) => format.format(i));
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
    $('.date').datetimepicker({
        format: DATETIME_FORMAT
    });
    $(function() {
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
        }

        function setFocus(e) {
            setTimeout(function(){ e.focus(); }, 5);
        }

        function updateTotal() {
            var total = 0;
            for (const id in itemByIds) {
                const subtotal = parser.parse($(`#item-subtotal-${id}`).text());
                total += subtotal;
            }
            $('#total').text(formatNumber(total));
            $('#grand_total').val(formatNumber(total + parseInt($('#expedition_cost').val()) + parseInt($('#other_cost').val())));
        }

        function updateSubtotal() {
            var id = $(this).attr('id').split('-')[2];            
            var self = $(this);
            var qtyElmt = $(`#item-quantity-${id}`);
            var costElmt = $(`#item-cost-${id}`);
            var subtotal = parseFloat(qtyElmt.val()) * parseFloat(costElmt.val());

            $(`#item-subtotal-${id}`).text(formatNumber(subtotal));
            updateTotal();
        }
        function addItem(item) {
            tableBody.append(
                `<tr id="item-${item.id}" class="items">`
                + `<td>${num++}</td>`
                + `<td>${item.name}</td>`
                + `<td class="text-center"><input style="width:5rem;text-align:right;" type="number" id="item-quantity-${item.id}" name=quantities[${item.id}] value="${item.quantity}" min="1" max="99999999"></td>`
                + `<td>${item.uom}</td>`
                + `<td class="text-center"><input style="width:7rem;text-align:right;" type="number" id="item-cost-${item.id}" name=costs[${item.id}] value="${item.cost}" max="999999999999"></td>`
                + `<td class="text-center"><input style="width:7rem;text-align:right;" type="number" id="item-price-${item.id}" name=prices[${item.id}] value="${item.price}" max="999999999999"></td>`
                + `<td id="item-subtotal-${item.id}" class="text-right">${formatNumber(item.cost)}</td>`                
                + `<td><button id="remove-item-${item.id}" class="btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i></button></td>`
                + `</tr>`
            );
            $(`#remove-item-${item.id}`).click(removeRow);
            $(`#item-quantity-${item.id}`).change(updateSubtotal);
            $(`#item-cost-${item.id}`).change(updateSubtotal);
        }

        function resetProductSelect() {
            setTimeout(function() {
                $('#product').val('0').trigger('change');
                $('#product').showPopup();
            }, 5);
        }

        addButton.click(function() {
            var selected = $('#product').find(":selected");
            var id = selected.val();
            if (id == 0) {
                return;
            }
                        
            if (itemByIds.hasOwnProperty(id)) {
                var qty = parseInt(qtyElement.val());
                var cost = parseFloat($(`#item-cost-${id}`).val());
                
                qty += 1;
                var subtotal = qty * cost;

                qtyElement.val(qty);
                $(`#item-subtotal-${id}`).text(formatNumber(subtotal));
                setFocus(qtyElement);
                updateTotal();
                qtyElement.select();
                resetProductSelect();
                return;
            }

            itemByIds[id] = id;

            var cost = parseFloat(selected.data('cost'));
            addItem({
                id: id,
                name: selected.text(),
                quantity: 1,
                uom: selected.data('uom'),
                cost: selected.data('cost'),
                price: selected.data('price'),
            })

            var qtyElement = $(`#item-quantity-${id}`);
            setFocus(qtyElement);
            $('#empty-items').hide();
            updateTotal();
            qtyElement.select();
            resetProductSelect();
        });

        productSelect.change();

        if (items.length > 0) {
            items.forEach(function(item){
                itemByIds[item.id] = item.id;
                addItem(item);
            });
            updateTotal();
            $('#empty-items').hide();
        }

        $('#expedition_cost').change(updateTotal);
        $('#other_cost').change(updateTotal);

        $('#party_id').change(function() {
            var selected = $(this).find('option:selected');
            $('#party_contacts').val(selected.data('contacts'));
            $('#party_address').val(selected.data('address'));
        });
    });
</script>
<?= $this->endSection() ?>