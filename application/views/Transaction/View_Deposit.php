<!DOCTYPE html>
<html>
	<head>
		<title>Deposit</title>
	</head>
	<body>
		<?php $this->load->view('Shared/AdminMaster'); ?>
		<h1>Deposit</h1>
		<span style="color:red">
			<?php echo $message;?>
		</span><br/>
		<form method="post">
			<table style="border:1px solid lightgray" cellspacing="5" cellpadding="5">
							
				<tr>
					<td>Account No</td>
                    <td> <input type="text" name="accountNo" value="<?php echo set_value('accountno');?>"/></td>
					<td> 
						<select name="accountNo" >
							<option value="">Select Account</option>
							<?php
							foreach($accounts as $account){
								echo '<option value="'.$account['accountNo'].'" '.set_select('accountNo',$account['accountNo'] ).'>'.$account['accountNo'].'</option>';
							}
							?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td>Amount</td>
					<td> <input type="text" name="amount" value="<?php echo set_value('amount');?>"/></td>
				</tr>
				<tr>
					<td></td>
					<td> <input type="submit" name="buttonSubmit" value="Deposit"/></td>
				</tr>
			</table>
			<br/>
		</form>
	</body>

</html>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-bank"></i> Deposito
        <small>Deposito</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Bank Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addbank" action="<?php echo base_url() ?>Bank/addNewBank" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="bname">Bank Name</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('bname'); ?>" id="bname" name="bname" maxlength="128">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="address" class="form-control required digits" value="<?php echo set_value('address'); ?>" id="address" name="address" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="text" class="form-control required email" id="email" value="<?php echo set_value('email'); ?>" name="email" maxlength="128">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Contact Name</label>
                                        <input type="text" class="form-control required" id="contact" value="<?php echo set_value('contact'); ?>" name="contact" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="swiftcode">Swift Code</label>
                                        <input type="text" class="form-control required" id="swiftcode" value="<?php echo set_value('swiftcode'); ?>" name="swiftcode" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ibancode">IBAN Code</label>
                                        <input type="text" class="form-control required" id="ibancode" value="<?php echo set_value('ibancode'); ?>" name="ibancode" maxlength="10">
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
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>
