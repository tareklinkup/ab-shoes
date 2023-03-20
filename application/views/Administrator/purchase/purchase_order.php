<style>
	span.selected-tag {
		font-weight: 600;
	}

	.v-select {
		margin-bottom: 5px;
	}

	.v-select .dropdown-toggle {
		padding: 0px;
	}

	.v-select input[type=search],
	.v-select input[type=search]:focus {
		margin: 0px;
	}

	.v-select .vs__selected-options {
		overflow: hidden;
		flex-wrap: nowrap;
	}

	.v-select .selected-tag {
		margin: 2px 0px;
		white-space: nowrap;
		position: absolute;
		left: 0px;
	}

	.v-select .vs__actions {
		margin-top: -5px;
	}

	.v-select .dropdown-menu {
		width: auto;
		overflow-y: auto;
	}

	#branchDropdown .vs__actions button {
		display: none;
	}

	#branchDropdown .vs__actions .open-indicator {
		height: 15px;
		margin-top: 7px;
	}

	.add-button {
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display: block;
		text-align: center;
		color: white;
	}

	.add-button:hover {
		background-color: #41add6;
		color: white;
	}
</style>

<div class="row" id="purchase">
	<div class="col-xs-12 col-md-12 col-lg-12" style="border-bottom:1px #ccc solid;margin-bottom:5px;">
		<div class="row">
			<div class="form-group">
				<label class="col-sm-1 control-label no-padding-right"> Invoice no </label>
				<div class="col-sm-2">
					<input type="text" id="invoice" name="invoice" v-model="purchase.invoice" readonly style="height:26px;" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label no-padding-right"> Memo No </label>
				<div class="col-sm-2">
					<input type="text" id="invoice" name="invoice" v-model="purchase.memo_no" required style="height:26px;" required />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label no-padding-right"> Pur. For </label>
				<div class="col-sm-2">
					<v-select id="branchDropdown" v-bind:options="branches" v-model="selectedBranch" label="Brunch_name" disabled></v-select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-1 control-label"> Date </label>
				<div class="col-sm-2">
					<input class="form-control" id="purchaseDate" name="purchaseDate" type="date" v-model="purchase.purchaseDate" v-bind:disabled="userType == 'u' ? true : false" style="border-radius: 5px 0px 0px 5px !important;padding: 4px 6px 4px" />
				</div>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-9 col-lg-9">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Supplier & Product Information</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>

					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Supplier </label>
								<div class="col-xs-7">
									<v-select v-bind:options="suppliers" v-model="selectedSupplier" v-on:input="onChangeSupplier" label="display_name"></v-select>
								</div>
								<div class="col-xs-1" style="padding: 0;">
									<a href="<?= base_url('supplier') ?>" title="Add New Supplier" class="btn btn-xs btn-danger" style="height: 25px; border: 0; width: 27px; margin-left: -10px;" target="_blank"><i class="fa fa-plus" aria-hidden="true" style="margin-top: 5px;"></i></a>
								</div>
							</div>

							<div class="form-group" style="display:none;" v-bind:style="{display: selectedSupplier.Supplier_Type == 'G' ? '' : 'none'}">
								<label class="col-xs-4 control-label no-padding-right"> Name </label>
								<div class="col-xs-8">
									<input type="text" placeholder="Supplier Name" class="form-control" v-model="selectedSupplier.Supplier_Name" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Mobile No </label>
								<div class="col-xs-8">
									<input type="text" placeholder="Mobile No" class="form-control" v-model="selectedSupplier.Supplier_Mobile" v-bind:disabled="selectedSupplier.Supplier_Type == 'G' ? false : true" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-xs-4 control-label no-padding-right"> Address </label>
								<div class="col-xs-8">
									<textarea class="form-control" v-model="selectedSupplier.Supplier_Address" v-bind:disabled="selectedSupplier.Supplier_Type == 'G' ? false : true"></textarea>
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<form v-on:submit.prevent="addToCart">
								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Model </label>
									<div class="col-xs-9">
										<input type="text" class="form-control" v-model="selectedProduct.model" required @change="getProducts">
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Product </label>
									<div class="col-xs-9">
										<input style="font-weight: 600;" type="text" class="form-control" @change="changeProductName" v-model="selectedProduct.Product_Name" required :disabled="isExists ? true : false">
									</div>
								</div>

								<div class="form-group clearfix">
									<label class="control-label col-md-3">Category:</label>
									<div class="col-md-4">
										<v-select v-bind:options="categories" v-model="selectedCategory" label="ProductCategory_Name" v-if="categories.length > 0" @input="onChangeProductDetails" :disabled="isExists ? true : false"></v-select>
									</div>

									<label for="brand" class="col-md-1" style="padding: 0px;">Brand</label>
									<div class="col-md-4">
										<v-select v-bind:options="brands" v-model="selectedBrand" label="brand_name" v-if="brands.length > 0" @input="onChangeProductDetails" :disabled="isExists ? true : false"></v-select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Color </label>
									<div class="col-xs-4">
										<v-select v-bind:options="colors" v-model="selectedColor" label="color_name" v-if="colors.length > 0" @input="onChangeProductDetails" :disabled="isExists ? true : false"></v-select>
									</div>

									<label class="col-xs-1" style="padding: 0px;"> Size </label>
									<div class="col-xs-4">
										<v-select v-bind:options="sizes" v-model="selectedSize" label="name" v-if="sizes.length > 0" @input="onChangeProductDetails" :disabled="isExists ? true : false"></v-select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Pur. Rate </label>
									<div class="col-xs-4">
										<input type="text" id="purchaseRate" name="purchaseRate" class="form-control" placeholder="Pur. Rate" v-model="selectedProduct.Product_Purchase_Rate" v-on:input="productTotal" required />
									</div>

									<label class="col-xs-2 control-label" style="padding: 0px;">Quantity </label>
									<div class="col-xs-3" style="padding: 0;padding-right: 10px;">
										<input type="text" step="0.01" min="0" id="quantity" name="quantity" class="form-control" placeholder="Quantity" ref="quantity" v-model="selectedProduct.quantity" v-on:input="productTotal" required />

									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Percentage(%) </label>
									<div class="col-xs-4">
										<input type="number" step="0.01" class="form-control" v-model="selectedProduct.percentage" @input="calculateSalePrice" required>
									</div>

									<label class="col-xs-2 control-label" style="padding: 0px;">Sale Rate </label>
									<div class="col-xs-3" style="padding: 0;padding-right: 10px;">
										<input type="text" id="salesRate" class="form-control" v-model="selectedProduct.Product_SellingPrice" @input="calculateSalePrice" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> Total </label>
									<div class="col-xs-4">
										<input type="text" id="productTotal" name="productTotal" class="form-control" readonly v-model="selectedProduct.total" />
									</div>
									<label class="col-xs-2">Unit</label>
									<div class="col-xs-3" style="padding: 0;padding-right: 10px;">
										<v-select v-bind:options="units" v-model="selectedUnit" label="Unit_Name" v-if="units.length > 0" :disabled="isExists ? true : false"></v-select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-xs-3 control-label no-padding-right"> </label>
									<div class="col-xs-4">
									</div>
									<div class="col-xs-5">
										<button type="submit" class="btn btn-default pull-right">Add Cart</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xs-12 col-md-12 col-lg-12" style="padding-left: 0px;padding-right: 0px;">
			<div class="table-responsive">
				<table class="table table-bordered" style="color:#000;margin-bottom: 5px;">
					<thead>
						<tr>
							<th style="width:4%;color:#000;">SL</th>
							<th style="width:20%;color:#000;">Product Name</th>
							<th style="color:#000;">Category</th>
							<th style="color:#000;">Brand</th>
							<th style="color:#000;">Color</th>
							<th style="color:#000;">Size</th>
							<th style="color:#000;">Pur. Rate</th>
							<th style="color:#000;">Sale Rate</th>
							<th style="color:#000;">Model</th>
							<th style="color:#000;">Quantity</th>
							<th style="color:#000;">Total Amount</th>
							<th style="color:#000;">Action</th>
						</tr>
					</thead>
					<tbody style="display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
						<tr v-for="(product, sl) in cart">
							<td>{{ sl + 1}}</td>
							<td>{{ product.name }}</td>
							<td>{{ product.categoryName }}</td>
							<td>{{ product.brandName }}</td>
							<td>{{ product.colorName }}</td>
							<td>{{ product.sizeName }}</td>
							<td>{{ product.purchaseRate }}</td>
							<td>{{ product.salesRate }}</td>
							<td>{{ product.model }}</td>
							<td>{{ product.quantity }}</td>
							<td>{{ product.total }}</td>
							<td><a href="" v-on:click.prevent="removeFromCart(sl)"><i class="fa fa-trash"></i></a></td>
						</tr>

						<tr>
							<td colspan="12"></td>
						</tr>

						<tr style="font-weight: bold;">
							<td colspan="7">Note</td>
							<td colspan="5">Total</td>
						</tr>

						<tr>
							<td colspan="7"><textarea style="width: 100%;font-size:13px;" placeholder="Note" v-model="purchase.note"></textarea></td>
							<td colspan="5" style="padding-top: 15px;font-size:18px;">{{ purchase.total }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div class="col-xs-12 col-md-3 col-lg-3">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Amount Details</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>

					<a href="#" data-action="close">
						<i class="ace-icon fa fa-times"></i>
					</a>
				</div>
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row">
						<div class="col-xs-12">
							<div class="table-responsive">
								<table style="color:#000;margin-bottom: 0px;">
									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Sub Total</label>
												<div class="col-xs-12">
													<input type="number" id="subTotal" name="subTotal" class="form-control" v-model="purchase.subTotal" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right"> Vat </label>
												<div class="col-xs-12">
													<input type="number" id="vatPercent" name="vatPercent" v-model="vatPercent" v-on:input="calculateTotal" style="width:50px;height:25px;" />
													<span style="width:20px;"> % </span>
													<input type="number" id="vat" name="vat" v-model="purchase.vat" readonly style="width:140px;height:25px;" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Discount</label>
												<div class="col-xs-12">
													<input type="number" id="discount" name="discount" class="form-control" v-model="purchase.discount" v-on:input="calculateTotal" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Transport Cost</label>
												<div class="col-xs-12">
													<input type="number" id="freight" name="freight" class="form-control" v-model="purchase.freight" v-on:input="calculateTotal" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Labour Cost</label>
												<div class="col-xs-12">
													<input type="number" id="freight" name="freight" class="form-control" v-model="purchase.labour_cost" v-on:input="calculateTotal" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Total</label>
												<div class="col-xs-12">
													<input type="number" id="total" class="form-control" v-model="purchase.total" readonly />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Paid</label>
												<div class="col-xs-12">
													<input type="number" id="paid" class="form-control" v-model="purchase.paid" v-on:input="calculateTotal" v-bind:disabled="selectedSupplier.Supplier_Type == 'G' ? true : false" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<label class="col-xs-12 control-label no-padding-right">Due</label>
												<div class="col-xs-6">
													<input type="number" id="due" name="due" class="form-control" v-model="purchase.due" readonly />
												</div>
												<div class="col-xs-6">
													<input type="number" id="previousDue" name="previousDue" class="form-control" v-model="purchase.previousDue" readonly style="color:red;" />
												</div>
											</div>
										</td>
									</tr>

									<tr>
										<td>
											<div class="form-group">
												<div class="col-xs-6">
													<input type="button" class="btn btn-success" value="Purchase" v-on:click="savePurchase" v-bind:disabled="purchaseOnProgress == true ? true : false" style="background:#000;color:#fff;padding:3px;width:100%;">
												</div>
												<div class="col-xs-6">
													<input type="button" class="btn btn-info" onclick="window.location = '<?php echo base_url(); ?>purchase'" value="New Purch.." style="background:#000;color:#fff;padding:3px;width:100%;">
												</div>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
	Vue.component('v-select', VueSelect.VueSelect);
	new Vue({
		el: '#purchase',
		data() {
			return {
				purchase: {
					purchaseId: parseInt('<?php echo $purchaseId; ?>'),
					invoice: '<?php echo $invoice; ?>',
					memo_no: '',
					purchaseFor: '',
					purchaseDate: moment().format('YYYY-MM-DD'),
					supplierId: '',
					subTotal: 0.00,
					vat: 0.00,
					discount: 0.00,
					freight: 0.00,
					labour_cost: 0.00,
					total: 0.00,
					paid: 0.00,
					due: 0.00,
					previousDue: 0.00,
					note: ''
				},
				vatPercent: 0.00,
				branches: [],
				selectedBranch: {
					brunch_id: "<?php echo $this->session->userdata('BRANCHid'); ?>",
					Brunch_name: "<?php echo $this->session->userdata('Brunch_name'); ?>"
				},
				suppliers: [],
				selectedSupplier: {
					Supplier_SlNo: null,
					Supplier_Code: '',
					Supplier_Name: '',
					display_name: 'Select Supplier',
					Supplier_Mobile: '',
					Supplier_Address: '',
					Supplier_Type: ''
				},
				oldSupplierId: null,
				oldPreviousDue: 0,
				products: [],
				existProduct: null,
				selectedProduct: {
					Product_SlNo: '',
					Product_Code: '',
					display_text: 'Select Product',
					Product_Name: '',
					Unit_Name: '',
					quantity: '',
					Product_Purchase_Rate: '',
					Product_SellingPrice: 0.00,
					total: '',
					model: "<?php echo $productModel; ?>",
					percentage: 0
				},
				cart: [],
				purchaseOnProgress: false,
				userType: '<?php echo $this->session->userdata("accountType") ?>',
				categories: [],
				selectedCategory: null,
				colors: [],
				selectedColor: null,
				sizes: [],
				selectedSize: null,
				brands: [],
				selectedBrand: null,
				units: [],
				selectedUnit: null,
				duplicateModel: '',
				isExists: false,
				model: "<?php echo $productModel; ?>"
			}
		},
		async created() {
			await this.getSuppliers();
			this.getBranches();
			this.getCategories();
			this.getColors();
			this.getSizes();
			this.getBrands();
			this.getUnits();

			if (this.purchase.purchaseId != 0) {
				await this.getPurchase();
			}
		},
		methods: {
			async modelNumber() {

				await axios.get("/get_last_model").then(res => {
					if (this.cart.length == 0) {
						this.selectedProduct.model = res.data;
					} else {
						let model = res.data;

						this.cart.forEach(result => {
							let new_model = Number(model.slice(2, model.length)) + +1

							if (new_model <= 9) {
								model = 'AB0000' + new_model;
							} else if (10 <= new_model && new_model <= 99) {
								model = 'AB000' + new_model;
							} else if (100 <= new_model && new_model <= 999) {
								model = 'AB00' + new_model;
							} else if (1000 <= new_model && new_model <= 9999) {
								model = 'AB0' + new_model;
							} else {
								model = 'AB' + new_model;
							}
						})
						this.selectedProduct.model = model;
					}
				})



				// let model = this.model;
				// let commas = [...model].filter(l => l == '0').length;
				// if (commas == 4) {
				// 	if (Number(model.slice(2, model.length)) + 1 == 10) {
				// 		return "AB00010"
				// 	} else if (Number(model.slice(2, model.length)) + 1 == 11) {
				// 		return "AB00011"
				// 	} else {
				// 		return "AB0000" + (Number(model.slice(2, model.length)) + 1)
				// 	}
				// } else if (commas == 3) {
				// 	if (Number(model.slice(2, model.length)) + 1 == 100) {
				// 		return "AB000100"
				// 	} else {
				// 		return "AB000" + (Number(model.slice(2, model.length)) + 1)
				// 	}
				// } else if (commas == 2) {
				// 	if (Number(model.slice(2, model.length)) + 1 == 1000) {
				// 		return "AB001000"
				// 	} else {
				// 		return "AB00" + (Number(model.slice(2, model.length)) + 1)
				// 	}
				// } else if (commas == 1) {
				// 	if (Number(model.slice(2, model.length)) + 1 == 10000) {
				// 		return "AB010000"
				// 	} else {
				// 		return "AB0" + (Number(model.slice(2, model.length)) + 1)
				// 	}
				// } else {
				// 	if (Number(model.slice(2, model.length)) + 1 == 100000) {
				// 		return "AB100000"
				// 	} else {
				// 		return "AB" + (Number(model.slice(2, model.length)) + 1)
				// 	}
				// }

				console.log(this.selectedProduct);
			},

			onChangeProductDetails() {
				if (this.purchase.purchaseId != 0) {
					return
				}
				let data = {
					Product_Name: this.selectedProduct.Product_Name,
					category_id: this.selectedCategory == null ? "" : this.selectedCategory.ProductCategory_SlNo,
					brand_id: this.selectedBrand == null ? "" : this.selectedBrand.brand_SiNo,
					color_id: this.selectedColor == null ? "" : this.selectedColor.color_SiNo,
					size_id: this.selectedSize == null ? "" : this.selectedSize.id
				}
				if (this.selectedProduct != null && this.selectedCategory != null && this.selectedBrand != null && this.selectedColor != null && this.selectedSize != null) {
					axios.post("check_variantwiseproduct", data)
						.then(res => {
							this.existProduct = res.data;
							if (this.existProduct != undefined) {
								// this.isExists = true;
								this.selectedProduct.Product_Name = this.existProduct.Product_Name;
								this.selectedProduct.Product_Purchase_Rate = this.existProduct.Product_Purchase_Rate;
								this.selectedProduct.percentage = this.existProduct.percentage;
								this.selectedProduct.Product_SellingPrice = this.existProduct.Product_SellingPrice;
								this.selectedProduct.model = this.existProduct.model;

								this.selectedCategory = this.categories.find(item => item.ProductCategory_SlNo == this.existProduct.ProductCategory_ID)
								this.selectedBrand = this.brands.find(b => b.brand_SiNo == this.existProduct.brand);
								this.selectedColor = this.colors.find(c => c.color_SiNo == this.existProduct.color);
								this.selectedSize = this.sizes.find(s => s.id == this.existProduct.size);
								this.selectedUnit = this.units.find(u => u.Unit_SlNo == this.existProduct.Unit_ID)
							} else {
								// this.isExists = false;
								if (this.cart.length > 0) {
									this.selectedProduct.model = this.modelNumber();
								}
							}
						})
				}
			},

			changeProductName() {
				if (this.purchase.purchaseId != 0) {
					return
				}
				let data = {
					Product_Name: this.selectedProduct.Product_Name
				}

				axios.post("check_product", data)
					.then(res => {
						this.existProduct = res.data;
						if (this.existProduct != undefined) {
							this.isExists = true;
							this.selectedProduct.Product_Name = this.existProduct.Product_Name;
							this.selectedProduct.Product_Purchase_Rate = this.existProduct.Product_Purchase_Rate;
							this.selectedProduct.percentage = this.existProduct.percentage;
							this.selectedProduct.Product_SellingPrice = this.existProduct.Product_SellingPrice;
							this.selectedProduct.model = this.existProduct.model;

							this.selectedCategory = this.categories.find(item => item.ProductCategory_SlNo == this.existProduct.ProductCategory_ID)
							this.selectedBrand = this.brands.find(b => b.brand_SiNo == this.existProduct.brand);
							this.selectedColor = this.colors.find(c => c.color_SiNo == this.existProduct.color);
							this.selectedSize = this.sizes.find(s => s.id == this.existProduct.size);
							this.selectedUnit = this.units.find(u => u.Unit_SlNo == this.existProduct.Unit_ID)
						} else {
							this.isExists = false;
							if (this.cart.length > 0) {
								this.selectedProduct.model = this.modelNumber();
							}
						}
					})
			},
			getCategories() {
				axios.get('/get_categories').then(res => {
					this.categories = res.data;
				})
			},
			getBranches() {
				axios.get('/get_branches').then(res => {
					this.branches = res.data;
				})
			},
			getColors() {
				axios.get('/get_colors')
					.then(res => {
						this.colors = res.data
					})
			},
			getSizes() {
				axios.get('/get_sizes')
					.then(res => {
						this.sizes = res.data;
					})
			},
			getBrands() {
				axios.get('/get_brands')
					.then(res => {
						this.brands = res.data
					})
			},
			getUnits() {
				axios.get('/get_units').then(res => {
					this.units = res.data;
				})
			},
			async getSuppliers() {
				await axios.get('/get_suppliers').then(res => {
					this.suppliers = res.data;
					this.suppliers.unshift({
						Supplier_SlNo: 'S01',
						Supplier_Code: '',
						Supplier_Name: '',
						display_name: 'General Supplier',
						Supplier_Mobile: '',
						Supplier_Address: '',
						Supplier_Type: 'G'
					})
				})
			},
			getProducts() {
				axios.post('/get_products', {
					isService: 'false',
					model: this.selectedProduct.model
				}).then(res => {
					this.existProduct = res.data[0];
					if (this.existProduct != undefined) {
						this.isExists = true;
						this.selectedProduct.Product_Name = this.existProduct.Product_Name;
						this.selectedProduct.Product_Purchase_Rate = this.existProduct.Product_Purchase_Rate;
						this.selectedProduct.percentage = this.existProduct.percentage;
						this.selectedProduct.Product_SellingPrice = this.existProduct.Product_SellingPrice;
						this.selectedProduct.model = this.existProduct.model;

						this.selectedCategory = this.categories.find(item => item.ProductCategory_SlNo == this.existProduct.ProductCategory_ID)
						this.selectedBrand = this.brands.find(b => b.brand_SiNo == this.existProduct.brand);
						this.selectedColor = this.colors.find(c => c.color_SiNo == this.existProduct.color);
						this.selectedSize = this.sizes.find(s => s.id == this.existProduct.size);
						this.selectedUnit = this.units.find(u => u.Unit_SlNo == this.existProduct.Unit_ID)
					} else {
						this.isExists = false;
					}
				})
			},
			onChangeSupplier() {
				if (this.selectedSupplier.Supplier_SlNo == null) {
					return;
				}

				if (event.type == 'readystatechange') {
					return;
				}

				if (this.purchase.purchaseId != 0 && this.oldSupplierId != parseInt(this.selectedSupplier.Supplier_SlNo)) {
					let changeConfirm = confirm('Changing supplier will set previous due to current due amount. Do you really want to change supplier?');
					if (changeConfirm == false) {
						return;
					}
				} else if (this.purchase.purchaseId != 0 && this.oldSupplierId == parseInt(this.selectedSupplier.Supplier_SlNo)) {
					this.purchase.previousDue = this.oldPreviousDue;
					return;
				}

				axios.post('/get_supplier_due', {
					supplierId: this.selectedSupplier.Supplier_SlNo
				}).then(res => {
					if (res.data.length > 0) {
						this.purchase.previousDue = res.data[0].due;
					} else {
						this.purchase.previousDue = 0;
					}
				})

				this.calculateTotal();
			},
			onChangeProduct() {
				this.$refs.quantity.focus();
			},
			calculateSalePrice() {
				if (this.selectedProduct.Product_Purchase_Rate == '' || this.selectedProduct.Product_Purchase_Rate == 0) {
					alert('Purchase rete is required');
					this.selectedProduct.percentage = 0
					return;
				}

				if (event.target.id == 'salesRate') {
					this.selectedProduct.percentage = ((+this.selectedProduct.Product_SellingPrice - +this.selectedProduct.Product_Purchase_Rate) / +this.selectedProduct.Product_Purchase_Rate * 100).toFixed(2);
				} else {
					let percentage = (+this.selectedProduct.Product_Purchase_Rate * +this.selectedProduct.percentage / 100).toFixed(2)
					this.selectedProduct.Product_SellingPrice = (+this.selectedProduct.Product_Purchase_Rate + +percentage).toFixed(2)
				}


			},
			productTotal() {
				this.selectedProduct.total = this.selectedProduct.quantity * this.selectedProduct.Product_Purchase_Rate;
			},
			async addToCart() {
				if (this.selectedProduct.name == '') {
					alert('Product name is required');
					return;
				}
				if (this.selectedProduct.model == '') {
					alert('Product model is required');
					return;
				}
				if (this.selectedCategory == null) {
					alert('Select product category');
					return;
				}
				if (this.selectedBrand == null) {
					alert('Select brand');
					return;
				}
				if (this.selectedColor == null) {
					alert('Select color');
					return;
				}
				if (this.selectedSize == null) {
					alert('Select size');
					return;
				}
				if (this.selectedProduct.Product_Purchase_Rate == '') {
					alert('Purchase rate is required');
					return;
				}
				if (this.selectedProduct.quantity == '' || this.selectedProduct.quantity == 0) {
					alert('Quantity is required');
					return
				}
				if (this.selectedProduct.percentage == '' || this.selectedProduct.percentage == 0) {
					alert('Sale rate is required');
					return;
				}
				if (this.selectedUnit == null) {
					alert('Select unit');
					return;
				}

				await axios.post('/check_model', {
					model: this.selectedProduct.model
				}).then(res => {
					this.duplicateModel = res.data
				})

				// axios.post("/")
				// if (this.duplicateModel > 0 && this.isExists == false) {
				// 	alert("This model already exists");
				// 	return;
				// }

				let cartInd = this.cart.findIndex(p => p.model != this.selectedProduct.model && p.name == this.selectedProduct.Product_Name && p.categoryId == this.selectedCategory.ProductCategory_SlNo && p.brand == this.selectedBrand.brand_SiNo && p.color == this.selectedColor.color_SiNo && p.size == this.selectedSize.id);

				// console.log(this.cart, this.selectedProduct.model, this.selectedProduct.Product_Name, this.selectedCategory.ProductCategory_SlNo, this.selectedBrand.brand_SiNo, this.selectedColor.color_SiNo, this.selectedSize.id);
				// console.log(cartInd);
				if (cartInd > -1) {
					alert('Product exists in the cart with another Model');
					return;
				}

				let product = {
					name: this.selectedProduct.Product_Name,
					model: this.selectedProduct.model,
					categoryId: this.selectedCategory.ProductCategory_SlNo,
					categoryName: this.selectedCategory.ProductCategory_Name,
					purchaseRate: this.selectedProduct.Product_Purchase_Rate,
					salesRate: this.selectedProduct.Product_SellingPrice,
					quantity: this.selectedProduct.quantity,
					total: this.selectedProduct.total,
					brand: this.selectedBrand.brand_SiNo,
					brandName: this.selectedBrand.brand_name,
					color: this.selectedColor.color_SiNo,
					colorName: this.selectedColor.color_name,
					size: this.selectedSize.id,
					sizeName: this.selectedSize.name,
					unit: this.selectedUnit.Unit_SlNo,
					percentage: this.selectedProduct.percentage
				}

				let cartInd2 = this.cart.findIndex(p => p.model == this.selectedProduct.model);
				if (cartInd2 > -1) {
					this.cart.splice(cartInd, 1, product);
				} else {
					this.cart.push(product);
				}
				this.clearSelectedProduct();
				this.calculateTotal();
				this.existProduct = null;
				this.isExists = false;
			},
			async removeFromCart(ind) {
				if (this.cart[ind].id) {
					let stock = await axios.post('/get_product_stock', {
						productId: this.cart[ind].productId
					}).then(res => res.data);
					if (this.cart[ind].quantity > stock) {
						alert('Stock unavailable');
						return;
					}
				}
				this.cart.splice(ind, 1);
				this.calculateTotal();
			},
			clearSelectedProduct() {
				this.selectedProduct.quantity = '';
				this.selectedProduct.total = 0;
				this.selectedProduct.model = this.selectedProduct.model;
				this.model = this.selectedProduct.model;
			},
			calculateTotal() {
				this.purchase.subTotal = this.cart.reduce((prev, curr) => {
					return prev + parseFloat(curr.total);
				}, 0).toFixed(2);
				this.purchase.vat = ((this.purchase.subTotal * parseFloat(this.vatPercent)) / 100).toFixed(2);
				this.purchase.total = ((parseFloat(this.purchase.subTotal) + parseFloat(this.purchase.vat) + parseFloat(this.purchase.freight) + parseFloat(this.purchase.labour_cost)) - parseFloat(this.purchase.discount)).toFixed(2);
				if (this.selectedSupplier.Supplier_Type == 'G') {
					this.purchase.paid = this.purchase.total;
					this.purchase.due = 0;
				} else {
					if (event.target.id != 'paid') {
						this.purchase.paid = 0;
					}

					this.purchase.due = (parseFloat(this.purchase.total) - parseFloat(this.purchase.paid)).toFixed(2);
				}
			},
			savePurchase() {
				if (this.purchase.memo_no == '') {
					alert('Purchase memo number is required');
					return;
				}

				if (this.selectedSupplier.Supplier_SlNo == null) {
					alert('Select supplier');
					return;
				}

				if (this.purchase.purchaseDate == '') {
					alert('Enter purchase date');
					return;
				}

				if (this.cart.length == 0) {
					alert('Cart is empty');
					return;
				}

				this.purchase.supplierId = this.selectedSupplier.Supplier_SlNo;
				this.purchase.purchaseFor = this.selectedBranch.brunch_id;

				this.purchaseOnProgress = true;

				let data = {
					purchase: this.purchase,
					cartProducts: this.cart
				}

				if (this.selectedSupplier.Supplier_Type == 'G') {
					data.supplier = this.selectedSupplier;
				}

				let url = '/add_purchase';
				if (this.purchase.purchaseId != 0) {
					url = '/update_purchase';
				}

				// console.log(data);
				// return

				axios.post(url, data).then(async res => {
					let r = res.data;
					alert(r.message);
					if (r.success) {
						let conf = confirm('Do you want to view invoice?');
						if (conf) {
							window.open(`/purchase_invoice_print/${r.purchaseId}`, '_blank');
							await new Promise(r => setTimeout(r, 1000));
							window.location = '/purchase';
						} else {
							window.location = '/purchase';
						}
					} else {
						this.purchaseOnProgress = false;
					}
				})
			},
			async getPurchase() {
				await axios.post('/get_purchases', {
					purchaseId: this.purchase.purchaseId
				}).then(res => {
					let r = res.data;
					let purchase = r.purchases[0];

					this.selectedSupplier.Supplier_SlNo = purchase.Supplier_SlNo;
					this.selectedSupplier.Supplier_Code = purchase.Supplier_Code;
					this.selectedSupplier.Supplier_Name = purchase.Supplier_Name;
					this.selectedSupplier.Supplier_Mobile = purchase.Supplier_Mobile;
					this.selectedSupplier.Supplier_Address = purchase.Supplier_Address;
					this.selectedSupplier.Supplier_Type = purchase.Supplier_Type;
					this.selectedSupplier.display_name = purchase.Supplier_Type == 'G' ? 'General Supplier' : `${purchase.Supplier_Code} - ${purchase.Supplier_Name}`;

					this.purchase.invoice = purchase.PurchaseMaster_InvoiceNo;
					this.purchase.memo_no = purchase.memo_no;
					this.purchase.purchaseFor = purchase.PurchaseMaster_PurchaseFor;
					this.purchase.purchaseDate = purchase.PurchaseMaster_OrderDate;
					this.purchase.supplierId = purchase.Supplier_SlNo;
					this.purchase.subTotal = purchase.PurchaseMaster_SubTotalAmount;
					this.purchase.vat = purchase.PurchaseMaster_Tax;
					this.purchase.discount = purchase.PurchaseMaster_DiscountAmount;
					this.purchase.freight = purchase.PurchaseMaster_Freight;
					this.purchase.labour_cost = purchase.PurchaseMaster_labour_cost;
					this.purchase.total = purchase.PurchaseMaster_TotalAmount;
					this.purchase.paid = purchase.PurchaseMaster_PaidAmount;
					this.purchase.due = purchase.PurchaseMaster_DueAmount;
					this.purchase.previousDue = purchase.previous_due;
					this.purchase.note = purchase.PurchaseMaster_Description;

					this.oldSupplierId = purchase.Supplier_SlNo;
					this.oldPreviousDue = purchase.previous_due;

					this.vatPercent = (this.purchase.vat * 100) / this.purchase.subTotal;

					r.purchaseDetails.forEach(product => {
						let cartProduct = {
							id: product.PurchaseDetails_SlNo,
							productId: product.Product_IDNo,
							name: product.Product_Name,
							categoryId: product.ProductCategory_ID,
							categoryName: product.ProductCategory_Name,
							brand: product.brand,
							brandName: product.brand_name,
							color: product.color,
							colorName: product.color_name,
							model: product.model,
							size: product.size,
							sizeName: product.size_name,
							purchaseRate: product.PurchaseDetails_Rate,
							salesRate: product.Product_SellingPrice,
							quantity: product.PurchaseDetails_TotalQuantity,
							total: product.PurchaseDetails_TotalAmount
						}

						this.cart.push(cartProduct);
					})

					let gSupplierInd = this.suppliers.findIndex(s => s.Supplier_Type == 'G');
					this.suppliers.splice(gSupplierInd, 1);
				})
			}
		}
	})
</script>