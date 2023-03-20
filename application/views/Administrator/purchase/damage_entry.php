<style>
    .v-select {
        margin-bottom: 5px;
    }

    .v-select.open .dropdown-toggle {
        border-bottom: 1px solid #ccc;
    }

    .v-select .dropdown-toggle {
        padding: 0px;
        height: 25px;
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
</style>
<div id="damages">
    <div class="row" style="margin-top: 15px;">
        <form class="form-horizontal" @submit.prevent="addDamage">
            <div class="col-sm-5 col-sm-offset-1">
                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Code </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <input type="text" placeholder="Code" class="form-control" v-model="damage.Damage_InvoiceNo" required readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Date </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <input type="date" placeholder="Date" class="form-control" v-model="damage.Damage_Date" required />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Supplier </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <v-select v-bind:options="suppliers" label="display_name" v-model="selectedSupplier" placeholder="Select Supplier"></v-select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Product </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-6">
                        <v-select v-if="!isProductFilterByBarcode" v-bind:options="products" v-model="selectedProduct" label="display_text" v-on:input="productOnChange"></v-select>
                        <input v-else type="text" v-model="barcodeValue" placeholder="Barcode" ref="barcode" class="form-control" id="barcode">
                    </div>
                    <div class="col-sm-1" style="padding: 0;">
                        <a href="#" @click.prevent="changeProductFilter" class="btn btn-xs btn-danger" style="height: 26px; border: 0; width: 28px;" target="_blank" title="Add New Product"><i class="fa fa-barcode" aria-hidden="true" style="margin-top: 5px;"></i></a>
                    </div>
                </div>

                <!-- <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Product </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <v-select v-bind:options="filterProducts" label="display_text" v-model="selectedProduct" placeholder="Select Product" v-on:input="productOnChange"></v-select>
                    </div>
                </div> -->

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Description </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <textarea class="form-control" placeholder="Description" v-model="damage.Damage_Description"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-sm-5">
                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Damage Quantity </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <input type="number" placeholder="Quantity" class="form-control" v-model="damage.DamageDetails_DamageQuantity" required v-on:input="calculateTotal" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Damage Rate </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <input type="number" step="0.01" placeholder="Rate" class="form-control" v-model="damage.damage_rate" required v-on:input="calculateTotal" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Brand </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <input type="text" placeholder="Brand" class="form-control" v-model="damage.brand" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Color </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <input type="text" placeholder="Brand" class="form-control" v-model="damage.color" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Size </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <input type="text" placeholder="Brand" class="form-control" v-model="damage.size" readonly />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"> Damage Amount </label>
                    <label class="col-sm-1 control-label no-padding-right">:</label>
                    <div class="col-sm-7">
                        <input type="number" placeholder="Amount" class="form-control" v-model="damage.damage_amount" required disabled />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label no-padding-right"></label>
                    <label class="col-sm-1 control-label no-padding-right"></label>
                    <div class="col-sm-7 text-right">
                        <button type="submit" class="btn btn-sm btn-success">
                            Submit
                            <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-md-4">
            <h1 style="display: none;" v-bind:style="{display: productStock !== '' ? '' : 'none'}">Stock : {{productStock}}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-inline">
            <div class="form-group">
                <label for="filter" class="sr-only">Filter</label>
                <input type="text" class="form-control" v-model="filter" placeholder="Filter">
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <datatable :columns="columns" :data="damages" :filter-by="filter">
                    <template scope="{ row }">
                        <tr>
                            <td>{{ row.Damage_InvoiceNo }}</td>
                            <td>{{ row.Damage_Date }}</td>
                            <td>{{ row.Product_Code }}</td>
                            <td>{{ row.Product_Name }}</td>
                            <td>{{ row.DamageDetails_DamageQuantity }}</td>
                            <td>{{ row.damage_rate }}</td>
                            <td>{{ row.damage_amount }}</td>
                            <td>{{ row.Damage_Description }}</td>
                            <td>
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <button type="button" class="button edit" @click="editDamage(row)">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" class="button" @click="deleteDamage(row.Damage_SlNo)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    </template>
                </datatable>
                <datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: '#damages',
        data() {
            return {
                damage: {
                    Damage_SlNo: 0,
                    supplier_id: null,
                    Damage_InvoiceNo: '<?php echo $damageCode; ?>',
                    Damage_Date: moment().format('YYYY-MM-DD'),
                    Damage_Description: '',
                    Product_SlNo: '',
                    DamageDetails_DamageQuantity: '',
                    damage_rate: '',
                    damage_amount: 0,
                    brand: '',
                    color: '',
                    size: ''
                },
                barcodeValue: '',
                isProductFilterByBarcode: true,
                products: [],
                filterProducts: [],
                selectedProduct: null,
                suppliers: [],
                selectedSupplier: null,
                productStock: '',
                damages: [],
                columns: [{
                        label: 'Code',
                        field: 'Damage_InvoiceNo',
                        align: 'center',
                        filterable: false
                    },
                    {
                        label: 'Date',
                        field: 'Damage_Date',
                        align: 'center'
                    },
                    {
                        label: 'Product Code',
                        field: 'Product_Code',
                        align: 'center'
                    },
                    {
                        label: 'Product Name',
                        field: 'Product_Name',
                        align: 'center'
                    },
                    {
                        label: 'Quantity',
                        field: 'DamageDetails_DamageQuantity',
                        align: 'center'
                    },
                    {
                        label: 'Damage Rate',
                        field: 'damage_rate',
                        align: 'center'
                    },
                    {
                        label: 'Damage Amount',
                        field: 'damage_amount',
                        align: 'center'
                    },
                    {
                        label: 'Description',
                        field: 'Damage_Description',
                        align: 'center'
                    },
                    {
                        label: 'Action',
                        align: 'center',
                        filterable: false
                    }
                ],
                page: 1,
                per_page: 10,
                filter: ''
            }
        },
        watch: {
            selectedSupplier(supplier) {
                if (supplier == undefined) return;
                this.damage.supplier_id = supplier.Supplier_SlNo
                this.filterProducts = this.products.filter(item => item.supplier_id == supplier.Supplier_SlNo)
            }
        },
        created() {
            this.getProducts();
            this.getDamages();
            this.getSuppliers();
        },
        methods: {
            async productOnChange() {
                if ((this.selectedProduct.Product_SlNo != '' || this.selectedProduct.Product_SlNo != 0)) {
                    this.damage.damage_rate = this.selectedProduct.Product_Purchase_Rate;

                    let damage_amount = parseFloat(this.damage.damage_rate) * parseFloat(this.damage.DamageDetails_DamageQuantity);
                    this.damage.damage_amount = isNaN(damage_amount) ? 0 : damage_amount;



                    this.productStock = await axios.post('/get_product_stock', {
                        productId: this.selectedProduct.Product_SlNo
                    }).then(res => {
                        return res.data;
                    })
                }
                this.damage.brand = this.selectedProduct.brand_name;
                this.damage.color = this.selectedProduct.color_name;
                this.damage.size = this.selectedProduct.size_name;
            },
            changeProductFilter() {
                this.isProductFilterByBarcode = !this.isProductFilterByBarcode;
            },
            getSuppliers() {
                axios.get('/get_suppliers')
                    .then(res => {
                        this.suppliers = res.data;
                    })
            },
            getProducts() {
                axios.post('/get_products', {
                    isService: 'false'
                }).then(res => {
                    this.products = res.data;
                })
            },
            addDamage() {
                if (this.selectedSupplier == null) {
                    alert('Select supplier');
                    return;
                }
                if (this.selectedProduct == null) {
                    alert('Select product');
                    return;
                }

                if (this.damage.DamageDetails_DamageQuantity > this.productStock) {
                    alert('Stock unavailable');
                    return;
                }

                this.damage.Product_SlNo = this.selectedProduct.Product_SlNo;

                let url = '/add_damage';
                if (this.damage.Damage_SlNo != 0) {
                    url = '/update_damage'
                }
                axios.post(url, this.damage).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.resetForm();
                        this.damage.Damage_InvoiceNo = r.newCode;
                        this.getDamages();
                    }
                })
            },

            editDamage(damage) {
                let keys = Object.keys(this.damage);
                keys.forEach(key => this.damage[key] = damage[key]);

                this.selectedProduct = this.products.find(item => item.Product_SlNo == damage.Product_SlNo)
                this.selectedProduct.display_text = `${damage.Product_Name} - ${damage.Product_Code}`
                this.selectedSupplier = this.suppliers.find(item => item.Supplier_SlNo == damage.supplier_id)
                this.selectedSupplier.display_name = `${damage.Supplier_Code} - ${damage.Supplier_Name}`;
            },

            calculateTotal() {
                let damage_amount = parseFloat(this.damage.damage_rate) * parseFloat(this.damage.DamageDetails_DamageQuantity);
                this.damage.damage_amount = isNaN(damage_amount) ? 0 : damage_amount;
            },

            deleteDamage(damageId) {
                let deleteConfirm = confirm('Are you sure?');
                if (deleteConfirm == false) {
                    return;
                }
                axios.post('/delete_damage', {
                    damageId: damageId
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getDamages();
                    }
                })
            },

            getDamages() {
                axios.get('/get_damages').then(res => {
                    this.damages = res.data;
                })
            },

            resetForm() {
                this.damage.Damage_SlNo = '';
                this.damage.Damage_Description = '';
                this.damage.Product_SlNo = '';
                this.damage.DamageDetails_DamageQuantity = '';
                this.damage.damage_rate = '';
                this.damage.damage_amount = 0;
                this.selectedProduct = null;
                this.productStock = '';
            }
        }
    })
</script>