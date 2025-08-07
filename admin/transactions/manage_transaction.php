<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `transaction_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
        $amount = $conn->query("SELECT amount from `payment_history` where transaction_id = '{$id}' order by unix_timestamp(date_created) asc limit 1")->fetch_array()[0];
        $amount = $amount > 0 ? $amount : 0;
    }
}
$price_arr = [];
?>
<div class="content py-3">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header">
            <h3 class="card-title"><b><?= isset($id) ? "Update Transaction Details - ". $code : "New Transaction" ?></b></h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="transaction_form">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                    <fieldset class="border-bottom">
                        <legend class="text-muted">Client Information</legend>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="client_name" class="control-label">Name</label>
                                <input type="text" name="client_name" id="client_name" autofocus value="<?= isset($client_name) ? $client_name : "Guest" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="client_contact" class="control-label">Contact #</label>
                                <input type="text" name="client_contact" id="client_contact" value="<?= isset($client_contact) ? $client_contact : "N/A" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="client_address" class="control-label">Address</label>
                                <textarea rows="3" name="client_address" id="client_address" class="form-control form-control-sm rounded-0" required><?= isset($client_address) ? $client_address : "N/A" ?></textarea>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend class="text-muted">Printing Information</legend>
                        <div class="row align-items-end">
                            <div class="form-group col-md-4">
                                <label for="price_id" class="control-label">Item</label>
                                <select id="price_id" class="custom-select form-control-sm rounded-0">
                                    <option value="" disabled selected></option>
                                    <?php 
                                    $prices = $conn->query("SELECT p.*,c.name as category FROM `price_list` p inner join category_list c on p.category_id = c.id where p.delete_flag = 0 and p.status = 1 order by c.name asc, p.size asc ");
                                    while($row = $prices->fetch_assoc()):
                                        $price_arr[$row['id']] = $row;
                                    ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['category']." - ".$row['size'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <button class="btn btn-flat btn-primary" type="button" id="add_to_list"><i class="fa fa-plus"></i> Add Item</button>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered" id="item-list">
                            <colgroup>
                                <col width="5%">
                                <col width="30%">
                                <col width="25%">
                                <col width="15%">
                                <col width="25%">
                            </colgroup>
                            <thead>
                                <tr class="bg-gradient-primary text-light">
                                    <th class="py-1 text-center"></th>
                                    <th class="py-1 text-center">Item</th>
                                    <th class="py-1 text-center">Price</th>
                                    <th class="py-1 text-center">Qty</th>
                                    <th class="py-1 text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($id)): ?>
                                <?php 
                                $items = $conn->query("SELECT t.*,p.size, c.name as category FROM `transaction_items` t inner join `price_list` p on t.price_id = p.id inner join category_list c on p.category_id = c.id where t.transaction_id = '{$id}'");  
                                $i = 1;
                                while($row = $items->fetch_assoc()):  
                                ?>
                                <tr>
                                    <td class=" align-middle px-2 py-1 text-center">
                                        <button class="btn btn-outline-danger btn-flat btn-sm btn-rem-item" type="button"><i class="fa fa-times"></i></button>
                                    </td>
                                    <td class=" align-middle px-2 py-1">
                                        <input type="hidden" name="price_id[]" value="<?= $row['price_id'] ?>">
                                        <input type="hidden" name="price[]" value="<?= $row['price'] ?>">
                                        <input type="hidden" name="total[]" value="<?= $row['total'] ?>">
                                        <p class="m-0 item_name"><?= $row['category']." - ".$row['size'] ?></p>
                                    </td>
                                    <td class=" align-middle px-2 py-1 text-right price"><?= number_format($row['price'],2) ?></td>
                                    <td class=" align-middle px-2 py-1"><input type="number" name="quantity[]" min='1' value="<?= $row['quantity'] ?>" class="form-control form-control-border rounded-0"></td>
                                    <td class=" align-middle px-2 py-1 text-right total"><?= number_format($row['total'],2) ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gradient-secondary">
                                    <th class="py-1 text-center" colspan='4'><b>Total<b><input type="hidden" name="total_amount" value="<?= isset($total_amount) ? $total_amount : 0 ?>"></th>
                                    <th class="px-2 py-1 text-right total_amount"><?= isset($total_amount) ? number_format($total_amount,2) : 0 ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </fieldset>
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="amount" class="control-label">Payment/Partial Payment</label>
                                <input type="number" name="amount" id="amount" value="<?= isset($amount) ? $amount : 0 ?>" class="form-control form-control-sm rounded-0 text-right" required>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-flat btn-primary btn-sm" type="submit" form="transaction_form">Save Transaction</button>
            <a href="./?page=transactions" class="btn btn-flat btn-default border btn-sm">Cancel</a>
        </div>
    </div>
</div>
<noscript id="item-clone">
<tr>
    <td class=" align-middle px-2 py-1 text-center">
        <button class="btn btn-outline-danger btn-flat btn-sm btn-rem-item" type="button"><i class="fa fa-times"></i></button>
    </td>
    <td class=" align-middle px-2 py-1">
        <input type="hidden" name="price_id[]">
        <input type="hidden" name="price[]">
        <input type="hidden" name="total[]">
        <p class="m-0 item_name"></p>
    </td>
    <td class=" align-middle px-2 py-1 text-right price">0.00</td>
    <td class=" align-middle px-2 py-1"><input type="number" name="quantity[]" min='1' value="1" class="form-control form-control-border rounded-0"></td>
    <td class=" align-middle px-2 py-1 text-right total">0.00</td>
</tr>
</noscript>
<script>
    var price_arr = $.parseJSON('<?= json_encode($price_arr) ?>')
    window.calc_total = function(){
        var total_amount = 0;
        $('#item-list tbody tr').each(function(){
            var price = $(this).find('input[name="price[]"]').val()
            var qty = $(this).find('input[name="quantity[]"]').val()
            qty = qty > 0 ? qty : 0;
            var total = parseFloat(price) * parseFloat(qty);
            $(this).find('.total').text(parseFloat(total).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
            $(this).find('input[name="total[]"]').val(total)
            total_amount += parseFloat(total)
        })
        $('input[name="total_amount"]').val(total_amount)
        $('.total_amount').text(parseFloat(total_amount).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
    }
    $(function(){
        $('#price_id').select2({
            placeholder:'Please Select Item Here',
            width:'100%'
        })
        $('.btn-rem-item').click(function(){
            $(this).closest('tr').remove()
            calc_total()
        })
        $('#add_to_list').click(function(){
            var price_id = $('#price_id').val()
            if(price_id == ''){
                alert_toast('Select Item First','warning')
                return false;
            }
            if(!!price_arr[price_id]){
                var data = price_arr[price_id];
                var tr = $($('noscript#item-clone').html())
                tr.find('.item_name').text(data.category+" - "+data.size)
                tr.find('input[name="price_id[]"]').val(data.id)
                tr.find('input[name="price[]"]').val(data.price)
                tr.find('.price').text(parseFloat(data.price).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
                $('#item-list tbody').append(tr)
                tr.find('.btn-rem-item').click(function(){
                    $(this).closest('tr').remove()
                    calc_total()
                })
                tr.find('input[name="quantity[]"]').on('keydown keypress change input',function(){
                    calc_total()
                })
                calc_total()
                $('#price_id').val('').trigger('change')
            }else{
                alert_toast('Unknown Item','warning')
                return false;
            }
        })
        $('#transaction_form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            if($('#item-list tbody tr').length <= 0){
                alert_toast(" Please add atleast 1 item first.",'warning')
                return false;
            }
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_transaction",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        location.href="./?page=transactions/view_transaction&id="+resp.tid;
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body,.modal').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>