<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-skrill" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
	<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	<?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-coinfide" class="form-horizontal">
		  <ul class="nav nav-tabs">
			<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
			<li><a href="#tab-order-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
		  </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="tab-general">
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="coinfide_username" value="<?php echo $coinfide_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />

				  <?php if ($error_email) { ?>
					  <div class="text-danger"><?php echo $error_email; ?></div>
				  <?php }else { ?>
					<span class="help-block"><?php echo $help_username; ?></span>
					<?php } ?>
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-api-username"><?php echo $entry_api_username; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="coinfide_api_username" value="<?php echo $coinfide_api_username; ?>" placeholder="<?php echo $entry_api_username; ?>" id="input-api-username" class="form-control" />
				  <?php if ($error_api_username) { ?>
					  <div class="text-danger"><?php echo $error_api_username; ?></div>
				  <?php } ?>
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-api-password"><?php echo $entry_api_password; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="coinfide_api_password" value="<?php echo $coinfide_api_password; ?>" placeholder="<?php echo $entry_api_password; ?>" id="input-api-password" class="form-control" />
				  <?php if ($error_api_pass) { ?>
					  <div class="text-danger"><?php echo $error_api_pass; ?></div>
				  <?php } ?>
				</div>
			  </div>
				<div class="form-group required">
					<label class="col-sm-2 control-label" for="input-secret"><?php echo $entry_secret; ?></label>
					<div class="col-sm-10">
						<input type="text" name="coinfide_secret" value="<?php echo $coinfide_secret; ?>" placeholder="<?php echo $entry_secret; ?>" id="input-secret" class="form-control" />
						<?php if ($error_secret) { ?>
						<div class="text-danger"><?php echo $error_secret; ?></div>
						<?php } ?>
					</div>
				</div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-environment"><?php echo $entry_environment; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_environment" class="form-control" id="input-environment">
					<?php if ($coinfide_environment=='prod') { ?>
						<option value="prod" selected="selected"><?php echo $coinfide_environment_live; ?></option>
						<option value="demo"><?php echo $coinfide_environment_test; ?></option>
					<?php } else { ?>
						<option value="prod"><?php echo $coinfide_environment_live; ?></option>
						<option value="demo" selected="selected"><?php echo $coinfide_environment_test; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-total"><?php echo $entry_total; ?> </label>
				<div class="col-sm-10">
				  <input type="text" name="coinfide_total" value="<?php echo $coinfide_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
				  <span class="help-block"><?php echo $help_total; ?></span> </div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_geo_zone_id" id="input-geo-zone" class="form-control">
					<option value="0"><?php echo $text_all_zones; ?></option>
					<?php foreach ($geo_zones as $geo_zone) { ?>
						<?php if ($geo_zone['geo_zone_id'] == $coinfide_geo_zone_id) { ?>
							<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
						<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-debug"><?php echo $entry_debug; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_debug" id="input-debug" class="form-control">
					<?php if ($coinfide_debug) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
				  <span class="help-block"><?php echo $help_debug; ?></span>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_status" id="input-status" class="form-control">
					<?php if ($coinfide_status) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="coinfide_sort_order" value="<?php echo $coinfide_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-order-status">
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_order_status_id" id="input-order-status" class="form-control">
					<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $coinfide_order_status_id) { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_complete_status; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_complete_status_id" class="form-control">
					<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $coinfide_complete_status_id) { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_rejected_status; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_rejected_status_id" class="form-control">
					<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $coinfide_rejected_status_id) { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_cancelled_status; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_cancelled_status_id" class="form-control">
					<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $coinfide_cancelled_status_id) { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_pending_status; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_pending_status_id" class="form-control">
					<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $coinfide_pending_status_id) { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_refunded_status; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_refunded_status_id" class="form-control">
					<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $coinfide_refunded_status_id) { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $entry_partially_refunded_status; ?></label>
				<div class="col-sm-10">
				  <select name="coinfide_partially_refunded_status_id" class="form-control">
					<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $coinfide_partially_refunded_status_id) { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
					<?php } ?>
				  </select>
				</div>
			  </div>
			</div>
		  </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>