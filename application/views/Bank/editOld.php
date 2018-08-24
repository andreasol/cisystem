<?php

$bankId = '';
$name = '';
$address = '';
$email = '';
$contact = '';
$swiftcode = '';
$ibancode = '';

if(!empty($bankInfo))
{
    foreach ($bankInfo as $uf)
    {
        $bankId = $uf->bankId;
        $name = $uf->name;
        $address = $uf->address;
        $email = $uf->email;
        $contact = $uf->contact;
        $swiftcode = $uf->swiftcode;
        $ibancode = $uf->ibancode;
    }
}


?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-calculator"></i> bank Management
        <small>Add / Edit bank</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter bank Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>Bank/editbank" method="post" id="editbank" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="bname">bank Name</label>
                                        <input type="text" class="form-control" id="bname" placeholder="bank Name" name="bname" value="<?php echo $name; ?>" maxlength="128">
                                        <input type="hidden" value="<?php echo $bankId; ?>" name="bankId" id="bankId" />    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="address" class="form-control required digits" value="<?php echo $address; ?>" id="address" name="address" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?php echo $email; ?>" maxlength="128">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Contact Name</label>
                                        <input type="text" class="form-control required" id="contact" value="<?php echo $contact; ?>" name="contact" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="swiftcode">Swift Code</label>
                                        <input type="text" class="form-control required" id="swiftcode" value="<?php echo $swiftcode; ?>" name="swiftcode" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ibancode">IBAN Code</label>
                                        <input type="text" class="form-control required" id="ibancode" value="<?php echo $ibancode; ?>" name="ibancode" maxlength="10">
                                    </div>
                                </div>  
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
</div>

<script src="<?php echo base_url(); ?>assets/js/editbank.js" type="text/javascript"></script>