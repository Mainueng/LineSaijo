<div id="warranty-card">
	<div class="d-flex">
		<div class="card mt-4 col-sm-6 col-12 px-0">
			<div class="card-body px-0">
				<h5 class="card-title text-center">Product Modal</h5>
				<p class="card-text text-center warranty-detail"><?php echo $this->data['product_model']; ?></p>
				<div class="warranty-container row m-0">
					<?php if ($this->data['type'] == 'air_con'){ ?>
						<div class="warranty-sub-container col-sm-6 col-12">
							<p class="text-center mb-2 mt-4 mb-0">Indoor Unit Serial No.</p>
							<p class="text-center warranty-detail mb-0"><?php echo $this->data['indoor']; ?></p>
						</div>
						<div class="warranty-sub-container col-sm-6 col-12">
							<p class="text-center mb-2 mt-4 mb-0">Outdoor Unit Serial No.</p>
							<p class="text-center warranty-detail mb-0"><?php echo $this->data['outdoor']; ?></p>
						</div>
					<?php } else { ?>
						<div class="warranty-sub-container col-12">
							<p class="text-center mb-2 mt-4 mb-0">Unit Serial No.</p>
							<p class="text-center warranty-detail mb-0"><?php echo $this->data['serial']; ?></p>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="d-flex">
		<div class="card mt-4 col-sm-6 col-12 px-0">
			<div class="card-body p-0 row">
				<?php if ($this->data['type'] == 'air_con'){ ?>
					<div class="w-100 col-sm-6 col-12 py-4 py-3-sm warranty-br">
						<h5 class="card-title text-center">รับประกันอะไหล่</h5>
						<p class="card-text text-center mb-0">วันที่ลงทะเบียนการรับประกัน</p>
						<p class="card-text text-center warranty-detail"><?php echo $this->data['active_date']; ?></p>
						<p class="card-text text-center mb-0">วันหมดอายุการประกัน</p>
						<p class="card-text text-center warranty-detail"><?php echo $this->data['warranty_compressor']; ?></p>
					</div>
					<div class="w-100 col-sm-6 col-12 py-4 py-3-sm warranty-hr">
						<h5 class="card-title text-center">รับประกันคอมแพรสเซอร์</h5>
						<p class="card-text text-center mb-0">วันที่ลงทะเบียนการรับประกัน</p>
						<p class="card-text text-center warranty-detail"><?php echo $this->data['active_date']; ?></p>
						<p class="card-text text-center mb-0">วันหมดอายุการประกัน</p>
						<p class="card-text text-center warranty-detail"><?php echo $this->data['warranty_part']; ?></p>
					</div>
				<?php } else { ?>
					<div class="w-100 col-12 py-4 py-3-sm">
						<h5 class="card-title text-center">รับประกันสินค้า</h5>
						<p class="card-text text-center mb-0">วันที่ลงทะเบียนการรับประกัน</p>
						<p class="card-text text-center warranty-detail"><?php echo $this->data['active_date']; ?></p>
						<p class="card-text text-center mb-0">วันหมดอายุการประกัน</p>
						<p class="card-text text-center warranty-detail"><?php echo $this->data['warranty_compressor']; ?></p>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>