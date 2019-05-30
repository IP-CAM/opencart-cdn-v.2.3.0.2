<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Medianova CDN</h3>
      </div>
      <div class="panel-body">

  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured">
      <table class="form" width="50%">
      	<tr>
      		<td><?php echo $entry_cdn_status; ?></td>
      		<td>
      			<select name="cdn_status">
	                <?php if ($cdn_status) { ?>
	                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
	                <option value="0"><?php echo $text_disabled; ?></option>
	                <?php } else { ?>
	                <option value="1"><?php echo $text_enabled; ?></option>
	                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
	                <?php } ?>
           		</select>
           	</td>
        </tr>
        <tr>
        	<td><?php echo $entry_cdn_domain; ?></td>
        	<td><input type="text" name="cdn_domain" size="50" value="<?php echo $cdn_domain; ?>"/></td>
        </tr>
        <tr>
        	<td><?php echo $entry_cdn_images; ?></td>
        	<td><input type="checkbox" name="cdn_images" value="1" <?php echo ($cdn_images) ? 'checked="checked"' : ''; ?>></td>
        </tr>
        <tr>
        	<td><?php echo $entry_cdn_js; ?></td>
        	<td><input type="checkbox" name="cdn_js" value="1" <?php echo ($cdn_js) ? 'checked="checked"' : ''; ?>></td>
        </tr>
        <tr>
        	<td><?php echo $entry_cdn_css; ?></td>
        	<td><input type="checkbox" name="cdn_css" value="1" <?php echo ($cdn_css) ? 'checked="checked"' : ''; ?>></td>
        </tr>
      </table>
    </form>
  </div>

          <a href="https://www.medianova.com/free-trial/">Medianova Signup</a>
</div></div></div>
<?php echo $footer; ?>