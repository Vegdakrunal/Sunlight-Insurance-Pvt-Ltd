<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `policy_list` where id = '{$_GET['id']}'");
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
</style>
<div class="container-fluid">
    <form action="" id="policy-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="category_id" class="control-label">Category</label>
                    <select name="category_id" id="category_id" class="form-control form-control-sm form-control-border select2" required>
                        <option value="" <?= !isset($category_id) ? 'selected' : "" ?> disabled></option>
                        <?php 
                        $categories = $conn->query("SELECT * FROM `category_list` where `status` = 1 and `delete_flag` = 0 ".(isset($category_id) ? " or id = '{$category_id}' " : "")." order by `name` asc ");
                        while($row = $categories->fetch_assoc()):
                        ?>
                            <option value="<?= $row['id'] ?>" <?= isset($category_id) && $category_id == $row['id'] ? "selected" : "" ?>><?= $row['name'] ?><?= ($row['delete_flag'] == 1) ? " <small class='text-muted'>(deleted)</small>" : "" ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="code" class="control-label">Code</label>
                    <input type="text" pattern="[A-Za-z0-9_-]+" name="code" id="code" class="form-control form-control-sm form-control-border" placeholder="Enter Code" value ="<?php echo isset($code) ? $code : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="name" class="control-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm form-control-border" placeholder="Enter Name" value ="<?php echo isset($name) ? $name : '' ?>" required>
                </div>
                <div class="form-group">
                    <!-- <label for="description" class="control-label">Description</label> -->
                    <!-- <textarea rows="3" name="description" id="description" class="form-control form-control-sm rounded-0" placeholder="Write Description here" required><?php echo isset($description) ? $description : '' ?></textarea> -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="duration" class="control-label">Duration <small><em>(Year)</em></small></label>
                    <input type="number" min = '1' name="duration" id="duration" class="form-control form-control-sm form-control-border text-right" placeholder="Enter Duration" value ="<?php echo isset($duration) ? $duration : 0 ?>" required>
                </div>
                <div class="form-group">
                    <label for="cost" class="control-label">Cost</label>
                    <input type="number" step="any" min = '1' name="cost" id="cost" class="form-control form-control-sm form-control-border text-right" placeholder="Enter Cost" value ="<?php echo isset($cost) ? $cost : 0 ?>" required>
                </div>
                <div class="form-group">
                    <label for="doc" class="control-label">Policy Document</label>
                    <input type="file" accept = 'application/pdf' name="doc" id="doc" class="form-control form-control-sm form-control-border" required>
                    <?php if(isset($doc_path) && !empty($doc_path)): ?>
                        <small><span class="text-muted">Current File: </span><a href="<?= base_url.$doc_path ?>" target="_blank"><i class="fa fa-external-link-alt"></i> <?= $id.'.png' ?></a></small>
                    <?php endif; ?>
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
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('.select2').select2({
                placeholder:'Please select here',
                width:'100%',
                dropdownParent: $('#uni_modal')
            })
        })
        $('#uni_modal #policy-form').submit(function(e){
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
                url:_base_url_+"classes/Master.php?f=save_policy",
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
                        location.reload();
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