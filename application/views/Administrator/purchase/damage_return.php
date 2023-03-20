<style>
    .v-select{
		margin-bottom: 5px;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
</style>
<div id="damageReturn">
    <div class="row" style="border-bottom: 1px solid #ccc;padding: 3px 0;">
        <form>
            <div class="col-md-3 col-md-offset-3">
                <div class="form-group">
                    <label class="col-md-3">Invoice</label>
                    <div class="col-md-9">
                        <v-select v-bind:options="invoices" label="Damage_InvoiceNo" v-model="selectedInvoice"></v-select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-md-3">Date</label>
                    <div class="col-md-9">
                        <input type="date" class="form-control" v-model="damageReturn.date">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row" style="margin-top: 10px;display:none;" v-bind:style="{display: cart.length > 0 ? '' : 'none'}">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Already returned quantity</th>
                            <th>Already returned amount</th>
                            <th>Return Quantity</th>
                            <th>Return Rate</th>
                            <th>Return Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, sl) in cart">
                            <td>{{ sl + 1 }}</td>
                            <td>{{ product.Product_Name }}</td>
                            <td>{{ product.DamageDetails_DamageQuantity }}</td>
                            <td>{{ product.damage_amount }}</td>
                            <td>{{ product.returned_quantity }}</td>
                            <td>{{ product.returned_amount }}</td>
                            <td><input type="text" v-model="product.return_quantity" v-on:input="productReturnTotal(sl)"></td>
                            <td><input type="text" v-model="product.return_rate" v-on:input="productReturnTotal(sl)"></td>
                            <td>{{ product.return_amount }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align:right;padding-top:15px;">Note</td>
                            <td colspan="2">
                                <textarea style="width: 100%" v-model="damageReturn.note"></textarea>
                            </td>
                            <td>
                                <button class="btn btn-success pull-left" v-on:click="saveDamageReturn">Save</button>
                            </td>
                            <td>Total: {{ damageReturn.total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    const app = new Vue({
        el: '#damageReturn',
        data: {
            damageReturn: {
                id: null,
                invoice: '',
                date: moment().format('YYYY-MM-DD'),
                note: '',
                total: 0,
            },
            invoices: [],
            selectedInvoice: null,
            cart: [],
        },
        watch: {
            selectedInvoice(invoice){
                if(invoice == undefined) return;
                this.damageReturn.invoice  = invoice.Damage_InvoiceNo
                this.getDamageForReturn();
            }
        },
        created() {
            this.getInvoices();
        },
        methods: {
            getInvoices() {
                axios.get('/get_damages')
                .then(res => {
                    this.invoices = res.data;
                })
            },
            async getDamageForReturn() {
                await axios.post('/get_damage_for_return', {damageId: this.selectedInvoice.Damage_SlNo})
                .then(res => {
                    this.cart = res.data
                })
            },
            productReturnTotal(ind){
				if(this.cart[ind].return_quantity > (this.cart[ind].DamageDetails_DamageQuantity - this.cart[ind].returned_quantity)){
					alert('Return quantity is not valid');
					this.cart[ind].return_quantity = 0;
                    this.cart[ind].return_amount = 0;
				}

				if(parseFloat(this.cart[ind].return_rate) > parseFloat(this.cart[ind].damage_rate)){
					alert('Rate is not valid');
					this.cart[ind].return_rate = '';
				}
				this.cart[ind].return_amount = parseFloat(this.cart[ind].return_quantity) * parseFloat(this.cart[ind].return_rate);
				this.calculateTotal();
			},
			calculateTotal(){
				this.damageReturn.total = this.cart.reduce((prev, cur) => {return prev + (cur.return_amount ? parseFloat(cur.return_amount) : 0.00)}, 0);
			},
            saveDamageReturn() {
                let filteredCart = this.cart.filter(product => product.return_quantity > 0 && product.return_rate > 0);

				if(filteredCart.length == 0){
					alert('No products to return');
					return;
				}

				if(this.damageReturn.date == null || this.damageReturn.date == ''){
					alert('Enter date');
					return;
				}

				let damage = {
					damageReturn: this.damageReturn,
					cart: filteredCart
				}

				let url = '';
				if(this.damageReturn.id != null) {
					url = '/update_damage_return';
				} else {
                    url = '/add_damage_return';
                    delete this.damageReturn.id ;
                }

				axios.post(url, damage).then(res => {
					let r = res.data;
					alert(r.message);
					window.location = '/damageReturn';
				})
                .catch(err => {
                    alert(err.response.data.message)
                })
            }
        }
    })
</script>