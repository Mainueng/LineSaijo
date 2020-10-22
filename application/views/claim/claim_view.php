<div id="loader"></div>
<div id="myDiv">
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
		<h1 class="form-header text-center mt-5 mt-4-sm">แบบฟอร์มการแจ้งซ่อมและแจ้งเคลม</h1>
		<hr class="form-header-hr mb-5 mb-4-sm">
		<form class="w-100" action="<?php echo site_url('claim/add_form'); ?>" method="post" enctype="multipart/form-data" id="claim-form">
			<div class="row form-container">
				<h3 class="col-sm-10 col-12 form-sub-header mb-3 mb-2-sm">รายละเอียดการแจ้งซ่อมและแจ้งเคลม</h3>
				<div class="col-sm-6 col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">ชื่อ<span class="require">*</span></label>
					<input type="text" name="firstname" class="form-control cliam-form" required>
				</div>
				<div class="col-sm-6 col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">นามสกุล<span class="require">*</span></label>
					<input type="text" name="lastname" class="form-control cliam-form" required>
				</div>
				<div class="col-sm-6 col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">เบอร์ติดต่อ<span class="require">*</span></label>
					<input type="text" name="phone_number" class="form-control cliam-form" required>
				</div>
				<div class="col-sm-6 col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">วัน-เวลาที่แจ้ง<span class="require">*</span></label>
					<input type="text" name="date" class="form-control cliam-form" disabled value="<?php echo date("d/m/Y H:i:s"); ?>">
				</div>
				<div class="col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">ที่อยู่<span class="require">*</span></label>
					<textarea class="form-control cliam-form" rows="4" style="resize: none" name="address" id="address-textarea" required></textarea>
				</div>
				<div class="col-sm-6 col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">ประเภท<span class="require">*</span></label>
					<div class="row">
						<div class="p-relative col-sm-auto col-12">
							<label class="cliam-form-label-radio">
								<input type="radio" name="type" value="air_con" checked>
								<span class="ml-2 mr-4">เครื่องปรับอากาศ</span>
								<span class="checkmark-type"></span>
							</label>
						</div>
						<div class="p-relative col-sm-auto col-12">
							<label class="cliam-form-label-radio">
								<input type="radio" name="type" value="air_puri">
								<span class="ml-2 mr-4">เครื่องฟอกอากาศ</span>
								<span class="checkmark-type"></span>
							</label>
						</div>
						<div class="p-relative col-sm-auto col-12">
							<label class="cliam-form-label-radio">
								<input type="radio" name="type" value="part">
								<span class="ml-2 mr-4">อะไหล่</span>
								<span class="checkmark-type"></span>
							</label>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">ซ่อม / เคลม<span class="require">*</span></label>
					<div class="row">
						<div class="p-relative col-sm-auto col-12">
							<label class="cliam-form-label-radio">
								<input type="radio" name="job_type" value="เคลม" checked>
								<span class="ml-2 mr-4">เคลม</span>
								<span class="checkmark-type"></span>
							</label>
						</div>
						<div class="p-relative col-sm-auto col-12">
							<label class="cliam-form-label-radio">
								<input type="radio" name="job_type" value="ซ่อม">
								<span class="ml-2 mr-4">ซ่อม</span>
								<span class="checkmark-type"></span>
							</label>
						</div>
					</div>
				</div>
				<div class="col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">หมายเลขเครื่องตัวเย็น<span class="require">*</span></label>
					<div class="d-flex" id="serial_number_indoor-input">
						<input type="text" name="serial_number_indoor" class="form-control cliam-form" id="serial_number_indoor" required>
					</div>
				</div>
				<div class="col-12 mt-4 mt-3-sm">
					<label class="cliam-form-label">หมายเลขเครื่องตัวร้อน<span class="require">*</span></label>
					<div class="d-flex" id="serial_number_outdoor-input">
						<input type="text" name="serial_number_outdoor" class="form-control cliam-form" id="serial_number_outdoor" required>
					</div>
				</div>
				<div class="col-12 mt-4 mt-3-sm" id="part-input">
					<label class="cliam-form-label">หมายเลขอะไหล่<span class="require">*</span></label>
					<div class="d-flex" id="part_number-input">
						<input type="text" name="part_number" class="form-control cliam-form" id="part_number" required>
					</div>
				</div>
				<section class="w-100 mb-4">
					<div class="col-12 mt-4 mt-3-sm">
						<label class="cliam-form-label">อาการเสีย<span class="require">*</span></label>
						<textarea class="form-control cliam-form" rows="4" style="resize: none" name="problem" required id="problem-textarea"></textarea>
					</div>
					<div class="d-flex col-sm-12 mt-3 mt-0-sm">
						<div class="form-group required w-100">
							<label class="cliam-form-label" for="input-file">รูปถ่ายสินค้าที่เสีย</label>
							<p style="color: red;" class="cliam-form-label mt-2">*ถ่ายรูปรหัสสินค้าให้ชัดเจนคู่กับอุปกรณ์หรือสินค้าที่ต้องการแจ้งซ่อมและแจ้งเคลม</p>
							<div class="row">
								<label class="col-sm-12 cliam-form-label mt-3 mt-2-sm">ตัวอย่าง</label>
								<div class=" col-sm-6 col-6">
									<img src="<?php echo base_url(); ?>img/example_2.jpg" class="img-thumbnail">
									<p class="cliam-form-label text-center pt-2">หมายเลขเครื่อง</p>
								</div>
								<div class=" col-sm-6 col-6">
									<img src="<?php echo base_url(); ?>img/example_1.jpg" class="img-thumbnail">
									<p class="cliam-form-label text-center pt-2">หมายเลขอะไหล่</p>
								</div>
							</div>
							<div class="d-flex-left mt-4 mt-2-sm">
								<div id="upload-1">
									<div class="upload-wrapper d-flex p-relative mr-3 mr-2-sm">
										<img src="<?php echo base_url(); ?>img/plus.png" class="img-responsive upload-img" id="upload-img-1">
										<input type="file" name="file-1" id="input-file-1" class="p-absolute h-100 w-100 pointer" accept="image/*" onchange="readURL_1(this);">
									</div>
									<button class="clear-img" type="button" id="clear_1">ลบ</button>
								</div>
								<div id="upload-2">
									<div class="upload-wrapper d-flex p-relative mr-3 mr-2-sm" id="upload-2">
										<img src="<?php echo base_url(); ?>img/plus.png" class="img-responsive upload-img" id="upload-img-2">
										<input type="file" name="file-2" id="input-file-2" class="p-absolute h-100 w-100 pointer" accept="image/*" onchange="readURL_2(this);">
									</div>
									<button class="clear-img" type="button" id="clear_2">ลบ</button>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="warranty" type="button" id="warranty">
					<div class="col-12 my-4 ">
						<button type="submit" class="form-submit-btn float-right" id="confirm">ยืนยัน</button>
					</div>
				</section>
			</div>
			<input type="hidden" name="hidden-img-1" id="hidden-img-1">
			<input type="hidden" name="hidden-img-2" id="hidden-img-2">
		</form>
	</div>
</div>

<script type="text/javascript">
	function readURL_1(input) {
		if (input.files && input.files[0]) {

			var img = document.createElement("img");
			var dataurl = null;
			var reader = new FileReader();

			reader.onload = function (e) {

				img.src = e.target.result;

				img.onload = function () {

					var canvas = document.createElement("canvas");
					var ctx = canvas.getContext("2d");
					ctx.drawImage(img, 0, 0);

					var MAX_WIDTH = 720;
					var MAX_HEIGHT = 720;
					var width = img.width;
					var height = img.height;

					if (width > height) {
						if (width > MAX_WIDTH) {
							height = MAX_WIDTH * height / width;
							width = MAX_WIDTH;
						}
					} else {
						if (height > MAX_HEIGHT) {
							width = MAX_HEIGHT * width / height;
							height = MAX_HEIGHT;
						}


					}
					canvas.width = width;
					canvas.height = height;
					var ctx = canvas.getContext("2d");
					ctx.drawImage(img, 0, 0, width, height);

					dataurl = canvas.toDataURL("image/png");

					console.log(dataurl);

					$('#hidden-img-1').val(dataurl);

					$('#upload-img-1').attr('src', e.target.result);
				}
			}

			reader.readAsDataURL(input.files[0]);

			$('#upload-2').show();

		}
	}

	function readURL_2(input) {
		if (input.files && input.files[0]) {

			var img = document.createElement("img");
			var dataurl = null;
			var reader = new FileReader();

			reader.onload = function (e) {

				img.src = e.target.result;

				img.onload = function () {

					var canvas = document.createElement("canvas");
					var ctx = canvas.getContext("2d");
					ctx.drawImage(img, 0, 0);

					var MAX_WIDTH = 720;
					var MAX_HEIGHT = 720;
					var width = img.width;
					var height = img.height;

					if (width > height) {
						if (width > MAX_WIDTH) {
							height = MAX_WIDTH * height / width;
							width = MAX_WIDTH;
						}
					} else {
						if (height > MAX_HEIGHT) {
							width = MAX_HEIGHT * width / height;
							height = MAX_HEIGHT;
						}


					}
					canvas.width = width;
					canvas.height = height;
					var ctx = canvas.getContext("2d");
					ctx.drawImage(img, 0, 0, width, height);

					dataurl = canvas.toDataURL("image/png");

					console.log(dataurl);

					$('#hidden-img-2').val(dataurl);

					$('#upload-img-2').attr('src', e.target.result);
				}
			}

			reader.readAsDataURL(input.files[0]);
		}
	}

	function myFunction() {
		setInterval(showPage);
	}

	function showPage() {
		document.getElementById("loader").style.display = "none";
	}
</script>

<script>
	$(document).ready(function(){  

		$("form").submit(function(){
			$("#myDiv").hide();
			$("#loader").show();
		});

		$("input[name='type']").click(function(){
			if ($("input[name='job_type']:checked").val() == 'เคลม') {
				$('#part-input').show();
				$("input[name='part_number']").prop("required", true);
			} else if ($("input[name='type']:checked").val() == 'part') {
				$('#part-input').show();
				$("input[name='part_number']").prop("required", true);
			} else {
				$('#part-input').hide();
				$("input[name='part_number']").prop("required", false);
			}
			
			if ($("input[name='type']:checked").val() == 'air_puri') {
				$('#serial_number_outdoor').prop("required", false);
			} else {
				$('#serial_number_outdoor').prop("required", true);
			}
		});

		$("input[name='job_type']").click(function(){
			if ($("input[name='job_type']:checked").val() == 'เคลม') {
				$('#part-input').show();
				$("input[name='part_number']").prop("required", true);
			} else if ($("input[name='type']:checked").val() == 'part') {
				$('#part-input').show();
				$("input[name='part_number']").prop("required", true);
			} else {
				$('#part-input').hide();
				$("input[name='part_number']").prop("required", false);
			}
		});
	});

	/*$("#serial_number").change(function(){
		$.ajax({
			url:  'claim/check',
			type: 'post',
			data: 'serial_number=' + $('#serial_number').val(),
			success: function(data) {
				if (data != 'not_found') {

					date_warranty = new Date(data); 
					date_now = new Date();

					var diff_time = date_warranty.getTime() - date_now.getTime(); 
					var diff_day = diff_time / (1000 * 3600 * 24);

					if (Math.round(diff_day) >= 0) {
						$('.e-warranty').remove();
						$('#serial_number-input').after('<div class="e-warranty pt-2">สินค้าของคุณลูกค้า SN:'+$('#serial_number').val()+' อยู่ในประกัน รับประกันถึงวันที่ '+ date_warranty.getDate() +'/'+ (date_warranty.getMonth()+1) +'/'+ date_warranty.getFullYear()+'</div>');
						$('.e-warranty-expire').remove();
						$('.serial_not_found').remove();

						$('#warranty').val('yes');

						$('#confirm').prop('disabled', false);

					} else {
						$('.e-warranty-expire').remove();
						$('#serial_number-input').after('<div class="e-warranty-expire pt-2">สินค้าของคุณลูกค้า SN:'+$('#serial_number').val()+' หมดประกัน (หมดประกันในวันที่ '+ date_warranty.getDate() +'/'+ (date_warranty.getMonth()+1) +'/'+ date_warranty.getFullYear()+') มีค่าใช้จ่ายในการเคลม</div>');
						$('.e-warranty').remove();
						$('.serial_not_found').remove();

						$('#warranty').val('no');

						$('#confirm').prop('disabled', false);

					}
				} else {
					$('.serial_not_found').remove();
					$('#serial_number-input').after('<div class="serial_not_found pt-2">หมายเลขเครื่องไม่ถูกต้อง</div>');
					$('.e-warranty-expire').remove();
					$('.e-warranty').remove();

					$('#warranty').val('');

					$('#confirm').prop('disabled', true);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});*/

	$("#input-file-1").change(function(){
		$('#clear_1').show();
	});

	$("#input-file-2").change(function(){
		$('#clear_2').show();
	});

	$('#clear_1').click(function(){
		$('#upload-img-1').attr('src', '<?php echo base_url(); ?>img/plus.png');
		$('#input-file-1').val('');
		$('#clear_1').hide();
	});

	$('#clear_2').click(function(){
		$('#upload-img-2').attr('src', '<?php echo base_url(); ?>img/plus.png');
		$('#input-file-2').val('');
		$('#clear_2').hide();
	});

</script>