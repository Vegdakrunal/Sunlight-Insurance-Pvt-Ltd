<style>
    .img-thumb-path{
        width:100px;
        height:80px;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<?php
$from = isset($_GET['from']) ? $_GET['from'] : date("Y-m-d",strtotime(date("Y-m-d")." -1 week")); 
$to = isset($_GET['to']) ? $_GET['to'] : date("Y-m-d",strtotime(date("Y-m-d"))); 
?>
<div class="card card-outline card-primary rounded-0 shadow">
	<div class="card-header">
		<h3 class="card-title">Date-wise Transaction Report</h3>
		<div class="card-tools">
		</div>
	</div>
	<div class="card-body">
		<div class="callout border-primary">
			<fieldset>
				<legend>Filter</legend>
					<form action="" id="filter">
						<div class="row align-items-end">
							<div class="form-group col-md-3">
								<label for="from" class="control-label">Date From</label>
                                <input type="date" name="from" id="from" value="<?= $from ?>" class="form-control form-control-sm rounded-0">
							</div>
							<div class="form-group col-md-3">
								<label for="to" class="control-label">Date To</label>
                                <input type="date" name="to" id="to" value="<?= $to ?>" class="form-control form-control-sm rounded-0">
							</div>
							<div class="form-group col-md-4">
                                <button class="btn btn-primary btn-flat btn-sm"><i class="fa fa-filter"></i> date insurance</button>
			                    <button class="btn btn-sm btn-flat btn-success" type="button" id="print"><i class="fa fa-print"></i> Print</button>
							</div>
						</div>
					</form>
			</fieldset>
		</div>
		<div id="outprint">
			<style>
				#sys_logo{
					object-fit:cover;
					object-position:center center;
					width: 6.5em;
					height: 6.5em;
				}
			</style>
        <div class="container-fluid">
			<div class="row">
				<div class="col-2 d-flex justify-content-center align-items-center">
					<img src="<?= validate_image($_settings->info('logo')) ?>" class="img-circle" id="sys_logo" alt="System Logo">
				</div>
				<div class="col-8">
					<h4 class="text-center"><b><?= $_settings->info('name') ?></b></h4>
					<h3 class="text-center"><b>Date-wise Insurance Transaction Report</b></h3>
					<h5 class="text-center"><b>as of</b></h5>
					<?php if($from == $to): ?>
					<h5 class="text-center"><b><?= date("F d, Y", strtotime($from)) ?></b></h5>
					<?php else: ?>
					<h5 class="text-center"><b><?= date("F d, Y", strtotime($from)). " - ".date("F d, Y", strtotime($to)) ?></b></h5>
					<?php endif; ?>
				</div>
				<div class="col-2"></div>
			</div>
			<table class="table table-bordered table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="20%">
					<col width="20%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-primary text-light">
						<th>#</th>
						<th>Ref.Code</th>
						<th>Client</th>
						<th>Policy</th>
						<th>Vehicle Reg. No.</th>
						<th>Registration</th>
						<th>Expiration</th>
						<th>Cost</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$total = 0 ;
						$qry = $conn->query("SELECT i.*,CONCAT(p.code,' - ', p.name) as `policy`, p.duration, p.doc_path, c.name as category,CONCAT(cc.code,' - ',cc.lastname, ', ', cc.firstname, ' ', cc.middlename) as client FROM `insurance_list` i inner join  policy_list p on i.policy_id = p.id inner join category_list c on p.category_id = c.id inner join client_list cc on i.client_id = cc.id where date(i.registration_date) between '{$from}' and '{$to}' order by date(i.registration_date) asc ");
						while($row = $qry->fetch_assoc()):
							$total += $row['cost'];
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><p class="m-0"><?php echo $row['code'] ?></p></td>
							<td class=""><p class="m-0"><?php echo $row['client'] ?></p></td>
							<td class=""><p class="m-0"><?php echo $row['policy'] ?></p></td>
							<td class=""><p class="m-0"><?php echo $row['registration_no'] ?></p></td>
							<td class=""><?php echo date("M d, Y",strtotime($row['registration_date'])) ?></td>
							<td class=""><?php echo date("M d, Y",strtotime($row['expiration_date'])) ?></td>
							<td class="text-right"><?= format_num($row['cost']) ?></td>
						</tr>
					<?php endwhile; ?>
					<?php if($qry->num_rows <= 0): ?>
						<tr>
							<th class="py-1 text-center" colspan="8">No Data.</th>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('.select2').select2({
            width:'100%'
        })
        $('#filter').submit(function(e){
            e.preventDefault();
            location.href= './?page=reports/date_wise_transaction&'+$(this).serialize();
        })
       $('#print').click(function(){
		   start_loader()
		   var _p = $('#outprint').clone()
		   var _h = $('head').clone()
		   var _el = $('<div>')
		   _h.find("title").text("Date-wise Transaction Report - Print View")
		   _p.find('tr.text-light').removeClass('text-light bg-gradient-primary bg-lightblue')
		   _el.append(_h)
		   _el.append(_p)
		   var nw = window.open("","_blank","width=1000,height=900,left=300,top=50")
		   	nw.document.write(_el.html())
			nw.document.close()
			setTimeout(() => {
				nw.print()
				setTimeout(() => {
					nw.close()
					end_loader()
				}, 300);
			}, 750);
	   })
	})
</script>