<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `insurance_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
$policy_arr = [];
?>
<style>
	img#cimg{
		height: 17vh;
		width: 25vw;
		object-fit: scale-down;
	}
    img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: scale-down;
	}
</style>
<div class="container-fluid">
    <form action="" id="insurance-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="cost" value="<?php echo isset($cost) ? $cost : '' ?>">
        <div class="form-group">
            <label for="client_id" class="control-label">Client</label>
            <select name="client_id" id="client_id" class="form-control form-control-sm form-control-border select2" required>
                <option value="" disabled <?= !isset($client_id) ? "selected" : "" ?>></option>
                <?php 
                $client = $conn->query("SELECT *,CONCAT(code,' - ',lastname,', ', firstname,' ', middlename) as fullname FROM `client_list` where delete_flag = 0 and status =1 ".(isset($client_id) ? " or id = '{$client_id}'" : "")." order by CONCAT(lastname,', ', firstname,' ', middlename) asc");
                while($row = $client->fetch_assoc()):
                ?>
                <option value="<?= $row['id'] ?>" <?= isset($client_id) && $client_id == $row['id'] ? "selected" : "" ?>><?= $row['fullname'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="policy_id" class="control-label">Insurance Policy</label>
            <select name="policy_id" id="policy_id" class="form-control form-control-sm form-control-border select2" required>
                <option value="" disabled <?= !isset($policy_id) ? "selected" : "" ?>></option>
                <?php 
                $policy = $conn->query("SELECT *,CONCAT(code,' - ', `name`) as `policy` FROM `policy_list` where delete_flag = 0 and status =1 ".(isset($policy_id) ? " or id = '{$policy_id}'" : "")." order by `name` asc");
                while($row = $policy->fetch_assoc()):
                    unset($row['description']);
                    $policy_arr[$row['id']] = $row;
                ?>
                <option value="<?= $row['id'] ?>" <?= isset($policy_id) && $policy_id == $row['id'] ? "selected" : "" ?>><?= $row['policy'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-auto pr-4"><span class="text-muted">Cost:</span> <span id="policy_cost">0.00</span></div>
                <div class="col-auto flex-grow-1 flex-shrink-1 pr-4"><span class="text-muted">Duration:</span> <span id="policy_duration">N/A</span></div>
            </div>
        </div>
        <div class="form-group">
            <label for="registration_date" class="control-label">Date of Registration</label>
            <input type="date" name="registration_date" id="registration_date" class="form-control form-control-sm form-control-border" value ="<?php echo isset($registration_date) ? $registration_date : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="expiration_date" class="control-label">Expiry Date</label>
            <input type="date" name="expiration_date" id="expiration_date" class="form-control form-control-sm form-control-border" value ="<?php echo isset($expiration_date) ? $expiration_date : '' ?>" required readonly>
        </div>
        <div class="form-group">
            <label for="registration_no" class="control-label">Vehicle Registraion No.</label>
            <input type="registration_no" name="registration_no" id="registration_no" class="form-control form-control-sm form-control-border" placeholder="Enter Vehicle Registraion No." value ="<?php echo isset($registration_no) ? $registration_no : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="chassis_no" class="control-label">Vehicle Chassis No</label>
            <input type="text" name="chassis_no" id="chassis_no" class="form-control form-control-sm form-control-border" placeholder="Vehicle Chassis No" value ="<?php echo isset($chassis_no) ? $chassis_no : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="vehicle_model" class="control-label">Vehicle Model</label>
            <input type="text" name="vehicle_model" id="vehicle_model" class="form-control form-control-sm form-control-border" placeholder="Vehicle Model" value ="<?php echo isset($vehicle_model) ? $vehicle_model : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="status" class="control-label">Status</label>
            <select name="status" id="status" class="form-control form-control-sm form-control-border" required>
                <option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
    </form>
</div>
<script>
    var policy = $.parseJSON('<?= json_encode($policy_arr) ?>');
    function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }else{
                $('#cimg').attr('src','<?php echo validate_image(isset($image_path) ? $image_path : "") ?>');
        }
	}
    function cal_xpyr(){
        var id = $('#policy_id').val();
        var duration = !!policy[id] ? policy[id].duration : 0;
        var registration_date = $('#registration_date').val();
        if(id > 0 && registration_date !=''){
            $.ajax({
                url:_base_url_+"classes/Master.php?f=get_expiration",
                method:'POST',
                data:{registration_date: registration_date, duration: duration},
                dataType:'json',
                error:err=>{
                    console.log(err)
                    alert_toast("Unable to get expiration date.",'error')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        $('#expiration_date').val(resp.value)
                    }else{
                        alert_toast("Unable to get expiration date.",'error')
                    }
                }
            })
        }else{
            $('#expiration_date').val('')
        }
    }
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('.select2').select2({
                placeholder:'Please select here',
                width:'100%',
                dropdownParent: $('#uni_modal')
            })
        })
        $('#policy_id').change(function(){
            var id = $(this).val()
            var cost = !!policy[id] ? policy[id].cost : 0;
            var duration = !!policy[id] ? policy[id].duration : 0;
            $('[name="cost"]').val(cost)
            $('#policy_cost').text(parseFloat(cost).toLocaleString('en-US'))
            $('#policy_duration').text(parseFloat(duration).toLocaleString('en-US')+" year"+(duration > 1 ? "s" : ''))
            cal_xpyr()
        })
        $('#registration_date').change(function(){
            cal_xpyr()
        })
        $('#uni_modal #insurance-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            if(_this[0].checkValidity() == false){
                _this[0].reportValidity();
                return false;
            }
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_insurance",
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
                        location.href = "./?page=insurances/view_insurance&id="+resp.id;
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