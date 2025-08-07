<style>
    .img-thumb-path{
        width:100px;
        height:80px;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<div class="card card-outline card-primary rounded-0 shadow">
	<div class="card-header">
		<h3 class="card-title">List of Transactions</h3>
		<div class="card-tools">
			<a href="./?page=transcations/manage_transction" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span>  Add New Transaction</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-primary text-light">
						<th>#</th>
						<th>Date Created</th>
						<th>Transaction Code</th>
						<th>Client</th>
						<th>Payment Status</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT * from `transaction_list` order by `status` asc,unix_timestamp(date_created) asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td class=""><p class="m-0 truncate-1"><?php echo $row['code'] ?></p></td>
							<td class=""><p class="m-0 truncate-1"><?php echo $row['client_name'] ?></p></td>
							<td class="text-center">
								<?php 
									switch ($row['payment_status']){
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
							</td>
							<td class="text-center">
								<?php 
									switch ($row['status']){
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
							</td>
							<td align="center">
								 <a href="./?page=transactions/view_transaction&id=<?= $row['id'] ?>" class="btn btn-flat btn-default btn-sm border"><i class="fa fa-eye"></i> View</a>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
	})
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
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>