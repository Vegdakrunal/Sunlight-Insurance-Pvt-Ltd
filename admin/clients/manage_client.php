<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `client_list` where id = '{$_GET['id']}'");
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
    <form action="" id="client-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="firstname" class="control-label">First Name</label>
                    <input type="text" name="firstname" id="firstname" class="form-control form-control-sm form-control-border" placeholder="Enter First Name" value ="<?php echo isset($firstname) ? $firstname : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="middlename" class="control-label">Middle Name</label>
                    <input type="text" name="middlename" id="middlename" class="form-control form-control-sm form-control-border" placeholder="Enter Middle Name" value ="<?php echo isset($middlename) ? $middlename : '' ?>" placeholder="optional">
                </div>
                <div class="form-group">
                    <label for="lastname" class="control-label">Last Name</label>
                    <input type="text" name="lastname" id="lastname" class="form-control form-control-sm form-control-border" placeholder="Enter Last Name" value ="<?php echo isset($lastname) ? $lastname : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="gender" class="control-label">Gender</label>
                    <select name="gender" id="gender" class="form-control form-control-sm form-control-border" required>
                        <option <?= isset($gender) && $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option <?= isset($gender) && $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dob" class="control-label">Date of Birth</label>
                    <input type="date" name="dob" id="dob" class="form-control form-control-sm form-control-border" placeholder="Enter Date of Birth" value ="<?php echo isset($dob) ? $dob : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-sm form-control-border" placeholder="Enter Email" value ="<?php echo isset($email) ? $email : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact" class="control-label">Contact </label>
                    <input type="text" name="contact" id="contact" class="form-control form-control-sm form-control-border" placeholder="Enter Contact #" value ="<?php echo isset($contact) ? $contact : '' ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address" class="control-label">Address</label>
                    <textarea rows="3" name="address" id="address" class="form-control form-control-sm rounded-0" placeholder="Write Address here" required><?php echo isset($address) ? $address : '' ?></textarea>
                </div>
                <div class="form-group">
                    <label for="img" class="control-label">Photo</label>
                    <input type="file" accept = 'image/png, image/jpeg' name="img" id="img" class="form-control form-control-sm form-control-border"  onchange="displayImg(this,$(this))">
                </div>
                <div class="form-group d-flex justify-content-center">
                    <img src="<?php echo validate_image(isset($image_path) ? $image_path : "") ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                </div>
                <div class="form-group">
                    <label for="status" class="control-label">Status</label>
                    <select name="status" id="status" class="form-control form-control-sm form-control-border" required>
                        <option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
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
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('.select2').select2({
                placeholder:'Please select here',
                width:'100%',
                dropdownParent: $('#uni_modal')
            })
        })
        $('#uni_modal #client-form').submit(function(e){
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
                url:_base_url_+"classes/Master.php?f=save_client",
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
                        location.href = "./?page=clients/view_client&id="+resp.id;
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