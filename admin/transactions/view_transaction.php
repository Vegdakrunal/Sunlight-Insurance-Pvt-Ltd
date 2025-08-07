<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `transaction_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<div class="content py-4">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h5 class="card-title">Transaction Details</h5>
            <div class="card-tools">
                <?php if(isset($status) && $status != 2): ?>
                <a class="btn btn-sm btn-primary btn-flat" href="./?page=transactions/manage_transaction&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
                <button class="btn btn-sm btn-danger btn-flat" id="delete_transaction"><i class="fa fa-trash"></i> Delete</button>
                <?php endif; ?>
                <?php if(isset($balance) && $balance > 0): ?>
                <button class="btn btn-sm btn-navy bg-navy btn-flat" type="button" id="add_payment"><i class="fa fa-plus"></i> Add Payment</button>
                <?php endif; ?>
                <button class="btn btn-sm btn-info bg-info btn-flat" type="button" id="update_status">Updated Status</button>
                <a href="./?page=transactions" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back to List</a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-muted">Transaction Code</label>
                            <div class="pl-4"><?= isset($code) ? $code : 'N/A' ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-muted">Payment Status</label>
                            <div class="pl-4">
                                <?php 
                                    switch ($payment_status){
                                        case 0:
                                            echo '<span class="rounded-pill badge badge-secondary bg-gradient-secondary px-3">Unpaid</span>';
                                            break;
                                        case 1:
                                            echo '<span class="rounded-pill badge badge-primary bg-gradient-primary px-3">Partially Paid</span>';
                                            break;
                                        case 2:
                                            echo '<span class="rounded-pill badge badge-teal bg-gradient-teal px-3 text-light">Paid</span>';
                                            break;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label text-muted">Status</label>
                            <div class="pl-4">
                                <?php 
                                    switch ($status){
                                        case 0:
                                            echo '<span class="rounded-pill badge badge-secondary bg-gradient-secondary px-3">Pending</span>';
                                            break;
                                        case 1:
                                            echo '<span class="rounded-pill badge badge-primary bg-gradient-primary px-3">On-Progress</span>';
                                            break;
                                        case 2:
                                            echo '<span class="rounded-pill badge badge-teal bg-gradient-teal px-3 text-light">Done</span>';
                                            break;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <fieldset>
                    <legend class="text-muted">Client Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-muted">Name</label>
                                <div class="pl-4"><?= isset($client_name) ? $client_name : 'N/A' ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-muted">Contact #</label>
                                <div class="pl-4"><?= isset($client_contact) ? $client_contact : 'N/A' ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label text-muted">Address</label>
                                <div class="pl-4"><?= isset($client_address) ? $client_address : 'N/A' ?></div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="clear-fix my-3"></div>
                <fieldset>
                    <div class="row">
                        <div class="col-md-8">
                            <legend class="text-muted">Items</legend>
                            <table class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="30%">
                                    <col width="25%">
                                    <col width="25%">
                                    <col width="25%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-gradient-primary text-light">
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
                                        <td class=" align-middle px-2 py-1">
                                            <p class="m-0 item_name"><?= $row['category']." - ".$row['size'] ?></p>
                                        </td>
                                        <td class=" align-middle px-2 py-1 text-right price"><?= number_format($row['price'],2) ?></td>
                                        <td class=" align-middle px-2 py-1 text-right"><?= number_format($row['quantity']) ?></td>
                                        <td class=" align-middle px-2 py-1 text-right total"><?= number_format($row['total'],2) ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gradient-secondary">
                                        <th class="py-1 text-center" colspan='3'><b>Total<b></th>
                                        <th class="px-2 py-1 text-right total_amount"><?= isset($total_amount) ? number_format($total_amount,2) : 0 ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <legend class="text-muted">Payment History</legend>
                            <table class="table table-stripped table-bordered">
                                <colgroup>
                                    <col width="30%">
                                    <col width="50%">
                                    <col width="20%">
                                </colgroup>
                                <thead>
                                    <tr class="bg-gradient-primary">
                                        <th class="py-1 text-center">DateTime</th>
                                        <th class="py-1 text-center">Amount</th>
                                        <th class="py-1 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($id)): ?>
                                    <?php 
                                    $history = $conn->query("SELECT * FROM `payment_history` where transaction_id ='{$id}' order by unix_timestamp(date_created) asc");
                                    while($row = $history->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="px-2 py-1 align-middle"><?= date("Y-m-d h:i A", strtotime($row['date_created'])) ?></td>
                                        <td class="px-2 py-1 text-right align-middle"><?= number_format($row['amount'],2) ?></td>
                                        <td class="px-2 py-1 align-middle text-center">
                                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item edit_payment" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                                <a class="dropdown-item delete_payment" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gradient-secondary">
                                        <th class="px-2 py-1 text-center" colspan="2">Total</th>
                                        <th class="px-2 py-1 text-right"><?= isset($paid_amount) ? number_format($paid_amount,2) : "0.00" ?></th>
                                    </tr>
                                    <tr class="bg-gradient-secondary">
                                        <th class="px-2 py-1 text-center" colspan="2">Balance</th>
                                        <th class="px-2 py-1 text-right"><?= isset($balance) ? number_format($balance,2) : "0.00" ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#update_status').click(function(){
            uni_modal("Update Status of <b><?= isset($code) ? $code : "" ?></b>","transactions/update_status.php?transaction_id=<?= isset($id) ? $id : "" ?>")
        })
        $('#add_payment').click(function(){
            uni_modal("Add Payment for <b><?= isset($code) ? $code : "" ?></b>","transactions/manage_payment.php?transaction_id=<?= isset($id) ? $id : "" ?>")
        })
        $('.edit_payment').click(function(){
            uni_modal("Edit Payment for <b><?= isset($code) ? $code : "" ?></b>","transactions/manage_payment.php?transaction_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'))
        })
        $('.delete_payment').click(function(){
			_conf("Are you sure to delete this transaction's payment?","delete_payment",[$(this).attr('data-id')])
		})
        $('#delete_transaction').click(function(){
			_conf("Are you sure to delete this transaction?","delete_transaction",['<?= isset($id) ? $id : '' ?>'])
		})
        $('.view_data').click(function(){
			uni_modal("Report Details","transactions/view_report.php?id="+$(this).attr('data-id'),"mid-large")
		})
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
    })
    function delete_payment($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_payment",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
    function delete_transaction($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_transaction",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.href="./?page=transactions";
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
