<div class="container">
	<div class="table-responsive">
		<h1>หมายเลขงาน #<?php echo $this->data['claim_id']; ?></h1>
		<table id="invoice-info" class="table table-bordered font-14">
			<thead>
				<tr>
					<th scope="col">ข้อมูลลูกค้า</th>
					<th scope="col">ที่อยู่</th>
				</tr>
			</thead>
			<tbody>

				<tr>
					<td class="w-50 align-middle">
						<p class="mb-2"><b>ชื่อ-นามสกุล </b><?php echo $this->data['customer']; ?></p>
						<p class="mb-2"><b>เบอร์โทร: </b><?php echo $this->data['phone_number']; ?></p>
						<p><b>วันที่แจ้ง: </b><?php echo $this->data['claim_date']; ?></p>
					</td>
					<td class="w-50 align-top">
						<?php echo $this->data['address']; ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<table id="invoice-service-list" class="table table-bordered font-14">
			<thead>
				<tr>
					<th scope="col" colspan="2">ข้อมูลงาน</th>
				</tr>
				<tr>
					<td class="w-50 align-middle">
						<p class="mb-2"><b>หมายเลขเครื่องตัวเย็น </b><?php echo $this->data['serial_number_indoor']; ?></p>
						<p class="mb-2"><b>หมายเลขเครื่องตัวร้อน </b><?php echo $this->data['serial_number_outdoor']; ?></p>
						<p class="mb-2"><b>หมายเลขอะไหล่: </b><?php echo $this->data['part_number']; ?></p>
						<p class="mb-2"><b>ประเภท: </b><?php echo $this->data['job_type']; ?></p>
						<p class="mb-2"><b>การรับประกัน: </b><?php echo $this->data['warranty']; ?></p>
					</td>
					<td class="w-50 align-top">
						<p class="mb-2"><b>ปัญหา: </b><?php echo $this->data['problem']; ?></p>
					</td>
				</tr>
				<tr>
					<td class="w-50 align-middle">
						<p class="mb-2"><b>เจ้าหน้าที่รับเรื่อง: </b><?php echo $this->data['officer']; ?></p>
						<p class="mb-2"><b>ช่างผู้รับผิดชอบ: </b><?php echo $this->data['technician']; ?></p>
						<p class="mb-2"><b>สถานะงาน: </b><?php echo $this->data['status']; ?></p>
						<p class="mb-2"><b>วันที่อัพเดทสถานะงาน: </b><?php echo $this->data['update_date']; ?></p>
					</td>
					<td class="w-50 align-top">
						<p class="mb-2"><b>หมายเหตุ: </b><?php echo $this->data['note']; ?></p>
					</td>
				</tr>
			</thead>
		</table>
	</div>
</div>