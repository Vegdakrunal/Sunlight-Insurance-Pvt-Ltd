<h1>Welcome to <?php echo $_settings->info('name') ?></h1>
<hr class="border-primary">
<style>
    #website-cover{
        width:100%;
        height:30em;
        object-fit:cover;
        object-position:center center;
    }
</style>
<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-th-list"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=categories" class="info-box-text">
                        <!-- <i class="nav-icon fas fa-list"></i> -->
                        <span class="info-box-text">Total Categories</span>
                      </a>
            <!-- <span class="info-box-text">Total Categories</span>/ -->
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `category_list` where delete_flag= 0 and `status` = 1 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-teal elevation-1"><i class="fas fa-file-alt"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=policies" >
                     <span class="info-box-text">Active Policies</span>
                      </a>
            <!-- <span class="info-box-text">Active Policies</span> -->
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `policy_list` where `status` = 1 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-maroon elevation-1"><i class="fas fa-file-alt"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=policies" >
                     <span class="info-box-text">Inctive Policies</span>
                      </a>
            
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `policy_list` where `status` = 1 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-user-tie"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=clients" >
                       
                        <span class="info-box-text">Clients</span>
                      </a>
            
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `client_list` ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-teal elevation-1"><i class="fas fa-car"></i></span>

            <div class="info-box-content">
            <a href="<?php echo base_url ?>admin/?page=insurances" >
                       <span class="info-box-text">Insured Vehicle</span>
                      </a>
            
                     <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `insurance_list` where `status` = 1 and date(expiration_date) > '".(date("Y-m-d"))."' ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-teal elevation-1"><i class="nav-icon fas fa-circle"></i></span>

            <div class="info-box-content">
                        <a href="<?php echo base_url ?>admin/?page=reports/date_wise_transaction" >
                          <!-- <i class="nav-icon fas fa-circle"></i> -->
                          <span class="info-box-text"> Date-wise Transactions</span>
                           </a>
                     <span class="info-box-number text-right">
                <?php 
                    // echo $conn->query("SELECT * FROM `insurance_list` where `status` = 1 and date(expiration_date) > '".(date("Y-m-d"))."' ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <img src="<?= validate_image($_settings->info('cover')) ?>" alt="Website Cover" class="img-fluid border w-100" id="website-cover">
    </div>
</div>
