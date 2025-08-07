<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT i.*,CONCAT(p.code,' - ', p.name) as `policy`, p.duration, p.doc_path, c.name as category FROM `insurance_list` i inner join  policy_list p on i.policy_id = p.id inner join category_list c on p.category_id = c.id where i.id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
        if(isset($client_id)):
            $client_qry = $conn->query("SELECT *,CONCAT(lastname, ', ', firstname, ' ', middlename) as fullname FROM `client_list` where id = '{$client_id}'");
            if($client_qry->num_rows > 0){
                $res = $client_qry->fetch_array();
                foreach($res as $k => $v){
                    if(!is_numeric($k))
                    $client[$k] = $v;
                }
            }
        endif;
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
            <h5 class="card-title">Insurance Ref. Code - <?= isset($code) ? $code : '' ?></h5>
            <div class="card-tools">
                <button class="btn btn-light btn-flat btn-sm bg-gradient-light border" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-default btn-flat btn-sm bg-gradient-navy" type="button" id="edit_data"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-default btn-flat btn-sm bg-gradient-danger" type="button" id="delete_data"><i class="fa fa-delete"></i> Delete</button>
                <a class="btn btn-light btn-flat btn-sm border" href="./?page=insurances"><i class="fa fa-angle-left"></i> Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid" id="outprint">
                
                <fieldset>
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-4 col-sm-12 text-center">
                            <img src="<?= validate_image(isset($client['image_path']) ? $client['image_path'] : "") ?>" alt="Client Image" id="img-thumb-path" class="img-tumbnail bg-gradient-gray">
                        </div>
                        <div class="col-lg-10 col-md-8 col-sm-12">
                            <legend class="text-muted h4">Client Information</legend>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Code</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1"><b><?= isset($client['code']) ? $client['code'] : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Name</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1"><b><?= isset($client['fullname']) ? $client['fullname'] : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Gender</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($client['gender']) ? $client['gender'] : "" ?></b></div>
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Birthday</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($client['dob']) ? date("M d, Y", strtotime($client['dob'])) : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Email</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($client['email']) ? $client['email'] : "" ?></b></div>
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Contact #</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($client['contact']) ? $client['contact'] : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Address</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1"><b><?= isset($client['address']) ? $client['address'] : "" ?></b></div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-4 col-sm-12 text-center">
                        </div>
                        <div class="col-lg-10 col-md-8 col-sm-12">
                            <legend class="text-muted h4">Vehicle's Insurance Information</legend>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Ref. Code</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1"><b><?= isset($code) ? $code : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Insurance Policy</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1"><b><?= isset($policy) ? $policy.(isset($doc_path) && !empty($doc_path) ? "<a class='ml-2 text-muted text-decoration-none policy_link' href='".base_url.$doc_path."' target='_blank'><i class='fa fa-external-link-alt'></i></a>" : '') : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Category</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1"><b><?= isset($category) ? $category : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Registration Date</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($registration_date) ? date("M d, Y", strtotime($registration_date)) : "" ?></b></div>
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Expiration Date</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($expiration_date) ? date("M d, Y", strtotime($expiration_date)) : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Duration</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($duration) ? $duration." year".($duration > 1? 's': '') : "" ?></b></div>
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Cost</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($cost) ? format_num($cost) : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Vehicle Reg. No.</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($registration_no) ? $registration_no : "" ?></b></div>
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Chassis No.</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($chassis_no) ? $chassis_no : "" ?></b></div>
                            </div>
                            <div class="row">
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Vehicle Model</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%"><b><?= isset($vehicle_model) ? $vehicle_model : "" ?></b></div>
                                <div class="border col-auto text-muted px-3" style="min-width:20%"><b>Status</b></div>
                                <div class="border col-auto flex-grow-1 flex-shrink-1" style="min-width:30%">
                                    <?php 
                                    $status = isset($status) ? $status : '';
                                    if(isset($expiration_date) && strtotime($expiration_date) < time()):
                                        echo '<span class="rounded-pill badge badge-danger bg-gradient-danger px-3">Expired</span>';
                                    else:
                                        switch ($status){
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
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<noscript id="print-header">
<div class="row align-items-center">
    <div class="col-2 text-center">
        <img src="<?= validate_image($_settings->info('logo')) ?>" alt="System Logo" style="width:150px; height:150px;object-fit:scale-down;object-position:center center;background:#fff" class="img-fluid rounded-circle">
    </div>
    <div class="col-10">
        <h3 class="text-center"><b><?= $_settings->info('name') ?></b></h3>
        <h4 class="text-center"><b>Vehicle's Insurance</b></h4>
    </div>
</div>
<hr>
</noscript>
<script>
    $(function(){
        $('#edit_data').click(function(){
			uni_modal("Update insurance Details","insurances/manage_insurance.php?id=<?= isset($id) ? $id : '' ?>",'mid-large')
		})
		$('#delete_data').click(function(){
			_conf("Are you sure to delete this insurance permanently?","delete_insurance",['<?= isset($id) ? $id : '' ?>'])
		})
        $('#print').click(function(){
            var _h = $('head').clone()
            var _p = $('#outprint').clone()
            var el = $('<div>')
            _h.find('title').text('Client Vehicle\'s Insurance Details - Print View')
            el.append(_h)
            el.append($($('noscript#print-header').html()).clone())
            _p.find('.policy_link').remove()
            el.append(_p)
            start_loader()
            var nw = window.open('','_blank','top=50,left=150,width=1000,height=750')
                nw.document.write($(el).html())
                nw.document.close()
            setTimeout(() => {
                nw.print()
                setTimeout(() => {
                    nw.close()
                    end_loader()
                }, 200);
            }, 500);

        })
    })
    function delete_insurance($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_insurance",
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
					location.replace('./?page=insurances');
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>

