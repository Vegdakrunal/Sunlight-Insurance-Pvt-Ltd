<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT *,CONCAT(lastname, ', ', firstname, ' ', middlename) as fullname FROM `client_list` where id = '{$_GET['id']}' and delete_flag = 0");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
    #img-thumb-path{
        width:calc(100%);
        height:20vh;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<div class="content py-3">
    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h5 class="card-title">Client - <?= isset($code) ? $code : '' ?></h5>
            <div class="card-tools">
                <button class="btn btn-default btn-flat btn-sm bg-gradient-navy" type="button" id="edit_data"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-default btn-flat btn-sm bg-gradient-danger" type="button" id="delete_data"><i class="fa fa-delete"></i> Delete</button>
                <a class="btn btn-light btn-flat btn-sm border" href="./?page=clients"><i class="fa fa-angle-left"></i> Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <fieldset>
                    <legent class="text-muted h4">Client Information</legent>
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-4 col-sm-12 text-center">
                            <img src="<?= validate_image(isset($image_path) ? $image_path : "") ?>" alt="Client Image" id="img-thumb-path" class="img-tumbnail bg-gradient-gray">
                        </div>
                        <div class="col-lg-10 col-md-8 col-sm-12">
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Code</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1"><b><?= isset($code) ? $code : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Name</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1"><b><?= isset($fullname) ? $fullname : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Gender</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($gender) ? $gender : "" ?></b></div>
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Birthday</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($dob) ? date("M d, Y", strtotime($dob)) : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Email</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($email) ? $email : "" ?></b></div>
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Contact </b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($contact) ? $contact : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Address</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1"><b><?= isset($address) ? $address : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Status</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1">
                                    <?php 
                                    $status = isset($status) ? $status : '';
                                        switch ($status){
                                            case 1:
                                                echo '<span class="rounded-pill badge badge-success bg-gradient-teal px-3">Active</span>';
                                                break;
                                            case 0:
                                                echo '<span class="rounded-pill badge badge-danger bg-gradient-danger px-3">Inactive</span>';
                                                break;
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend class="text-muted">Active Insurance(s)</legend>
                    <table class="table table-striped table-bordered">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="20%">
                            <col width="15%">
                            <col width="20%">
                            <col width="15%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Ref. Code</th>
                                <th class="text-center">Policy</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Date Insured</th>
                                <th class="text-center">Duration</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(isset($id)):
                            $i = 1;
                            $vh = $conn->query("SELECT i.*,CONCAT(p.code,' - ', p.name) as `policy`, p.duration, p.doc_path, c.name as category FROM `insurance_list` i inner join  policy_list p on i.policy_id = p.id inner join category_list c on p.category_id = c.id where i.client_id = '{$id}' order by date(i.registration_date) asc ");
                            while($row = $vh->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-cemter"><?= $i++; ?></td>
                                <td><a href="./?page=insurances/view_insurance&id=<?= $row['id'] ?>" title="View Vehicle's Insurance" target='_blank'><?= $row['code'] ?></a></td>
                                <td><?= $row['policy'].(isset($row['doc_path']) && !empty($row['doc_path']) ? "<a class='ml-2 text-muted text-decoration-none policy_link' href='".base_url.$row['doc_path']."' target='_blank'><i class='fa fa-external-link-alt'></i></a>" : '') ?></td>
                                <td><?= $row['category'] ?></td>
                                <td><?= date("F d, Y", strtotime($row['registration_date'])) ?></td>
                                <td><?= date("F d, Y", strtotime($row['expiration_date'])) ?></td>
                                <td class="text-center">
                                    <?php 
                                    $row['status'] = isset($row['status']) ? $row['status'] : '';
                                    if(isset($row['expiration_date']) && strtotime($row['expiration_date']) < time()):
                                        echo '<span class="rounded-pill badge badge-danger bg-gradient-danger px-3">Expired</span>';
                                    else:
                                        switch ($row['status']){
                                            case 1:
                                                echo '<span class="rounded-pill badge badge-success bg-gradient-teal px-3">Active</span>';
                                                break;
                                            case 0:
                                                echo '<span class="rounded-pill badge badge-danger bg-gradient-danger px-3">Inactive</span>';
                                                break;
                                            default:
                                                echo '<span class="rounded-pill badge badge-light bg-gradient-light border px-3">N/A</span>';
                                                break;
                                        }
                                    endif;
                                    ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#edit_data').click(function(){
			uni_modal("Update Client Details","clients/manage_client.php?id=<?= isset($id) ? $id : '' ?>",'large')
		})
		$('#delete_data').click(function(){
			_conf("Are you sure to delete this Client permanently?","delete_client",['<?= isset($id) ? $id : '' ?>'])
		})
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
        });
    })
    function delete_client($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_client",
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
					location.replace('./?page=clients');
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>

