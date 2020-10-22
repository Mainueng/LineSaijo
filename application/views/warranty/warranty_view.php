<div id="form-header">
	<div class="container h-100">
		<div class="row d-flex h-100">
			<div class="col-sm-12 col-xs-12">
				<div id="logo" class="d-flex">
					<img src="<?php echo base_url(); ?>img/saijo-logo-form.png" class="img-responsive" />
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container mb-5 mb-4-sm">
	<h1 class="form-header text-center mt-5 mt-4-sm">ตรวจสอบการรับประกัน</h1>
	<hr class="form-header-hr mb-5 mb-4-sm">
<!-- 	<form class="w-100" action="<?php echo site_url('warranty/warranty_info'); ?>" method="post" enctype="multipart/form-data" id="claim-form">
 -->		<div class="row form-container" id="warranty_info">
			<div class="d-flex w-100">
				<div class="col-sm-6 col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">หมายเลขเครื่อง</label>
					<div class="d-flex" id="serial_number-input">
						<input type="text" name="serial_number" id="serial_number" class="form-control cliam-form" required>
						<button class="form-submit-btn ml-3" type="button" id="check">ตรวจสอบ</button>
					</div>
				</div>
			</div>
		</div>
<!-- 	</form> -->
</div>
<script type="text/javascript">
	$("#check").click(function(){
		if ($('#serial_number').val()) {
			$.ajax({
				url:  'warranty/warranty_info',
				type: 'post',
				data: 'serial_number=' + $('#serial_number').val(),
				success: function(data) {
					$('#warranty-card').remove();
					$('.serial_not_found').remove();
					$('#warranty_info').after(data);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		} else {
			$('.serial_not_found').remove();
			$('#serial_number-input').after('<div class="serial_not_found pt-2">หมายเลขเครื่องไม่ถูกต้อง</div>');
		}
		
	});
</script>