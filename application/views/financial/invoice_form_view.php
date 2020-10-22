<div class="container">
	<div class="table-responsive">
		<h1>Invoice #<?php echo $this->data['invoice_id']; ?></h1>
		<table id="invoice-info" class="table table-bordered font-14">
			<thead>
				<tr>
					<th scope="col">Service Infomation</th>
					<th scope="col">Payment Address</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if ($this->data['invoice_form']) {

					foreach ($this->data['invoice_form'] as $result) { ?>
						<tr>
							<td class="w-50 align-middle">
								<p class="mb-0"><b>Date Added </b><?php echo $result['date_added']; ?></p>
								<p class="mb-0"><b>Job ID: </b><?php echo $result['job_id']; ?></p>
								<p><b>Service Type </b><?php echo ucfirst($result['service_type']); ?></p>
							</td>
							<td class="w-50 align-middle">
								<p class="mb-0"><?php echo $result['name']." ".$result['lastname']; ?></p>
								<p class="mb-0"><?php echo $result['address']; ?></p>
								<p><?php echo $result['district']." ".$result['province']." ".$result['postal_code']; ?></p>
								<p><b>Telephone </b><?php echo $result['telephone']; ?></p>
							</td>
						</tr>
					<?php } 
				} ?>
			</tbody>
		</table>
		<table id="invoice-service-list" class="table table-bordered font-14">
			<thead>
				<tr>
					<th scope="col">Serial No.</th>
					<th scope="col">Service Type</th>
					<th scope="col">Service Name</th>
					<th scope="col" class="text-right">Total</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if ($this->data['invoice_service']) {

					foreach ($this->data['invoice_service'] as $result) { ?>
						<tr>
							<td><?php echo $result['serial']; ?></td>
							<td><?php echo $result['service_type']; ?></td>
							<td><?php echo $result['service_name']; ?></td>
							<td class="text-right"><?php echo $result['cost'].' '.$result['unit']; ?></td>
						</tr>

						<?php if (!$this->data['service_fee']) { ?>
							<tr>
								<td><?php echo $result['serial']; ?></td>
								<td>ส่วนลดค่า<?php echo $result['service_type']; ?></td>
								<td>ส่วนลดค่า<?php echo $result['service_name']; ?></td>
								<td class="text-right"> -<?php echo $result['cost'].' '.$result['unit']; ?></td>
							</tr>
						<?php } ?>

					<?php } 
				} ?>
				<tr>
					<td colspan="3" class="text-right"><b>Sub-Total</b></td>
					<td class="text-right"><?php echo $this->data['sub-total'].' '.$this->data['unit']; ?></td>
				</tr>
				<tr>
					<td colspan="3" class="text-right"><b>Vat (7%)</b></td>
					<td class="text-right"><?php echo $this->data['vat'].' '.$this->data['unit']; ?></td>
				</tr>
				<tr>
					<td colspan="3" class="text-right"><b>Total</b></td>
					<td class="text-right"><?php echo $this->data['total'].' '.$this->data['unit']; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>