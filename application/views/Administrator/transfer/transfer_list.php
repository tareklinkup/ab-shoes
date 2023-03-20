<style>
    .v-select {
        margin: 0 10px 5px 5px;
        float: right;
        min-width: 180px;
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

    .form-group select {
        padding: 2px 4px;
        height: 25px;
        border-radius: 4px;
    }
</style>

<div id="transferList">
    <div class="row" style="border-bottom: 1px solid #ccc;">
        <div class="col-md-12">
            <form class="form-inline" @submit.prevent="getTransfers">
                <div class="form-group">
                    <label>Transfer to</label>
                    <v-select v-bind:options="branches" v-model="selectedBranch" label="Brunch_name" placeholder="Select Branch"></v-select>
                </div>
                <div class="form-group">
                    <label>Search By</label>
                    <select class="form-control" v-model="filter.search_type" @change="onChangeSearchType">
                        <option value="without_details">Without Details</option>
                        <option value="with_details">With Details</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date from</label>
                    <input type="date" class="form-control" v-model="filter.dateFrom">
                </div>

                <div class="form-group">
                    <label>to</label>
                    <input type="date" class="form-control" v-model="filter.dateTo">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-info btn-xs" value="Search" style="padding-top:0px;padding-bottom:0px;margin-top:-4px;">
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered" v-if="filter.search_type == 'without_details'">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Transfer Date</th>
                            <th>Transfer by</th>
                            <th>Transfer To</th>
                            <th>Amount</th>
                            <th>Qty</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody style="display:none;" v-bind:style="{display: transfers.length > 0 ? '' : 'none'}">
                        <tr v-for="(transfer, sl) in transfers">
                            <td>{{ sl + 1 }}</td>
                            <td>{{ transfer.transfer_date }}</td>
                            <td>{{ transfer.transfer_by_name }}</td>
                            <td>{{ transfer.transfer_to_name }}</td>
                            <td>{{ transfer.total_amount }}</td>
                            <td>{{ transfer.qty }}</td>
                            <td>{{ transfer.note }}</td>
                            <td>
                                <a href="" v-bind:href="`/transfer_invoice/${transfer.transfer_id}`" target="_blank" title="View invoice"><i class="fa fa-file"></i></a>
                                <a href="" v-bind:href="`/product_transfer/${transfer.transfer_id}`" target="_blank" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="" @click.prevent="deleteTransfer(transfer.transfer_id)" title="Delete"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table v-else class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Transfer Date</th>
                            <th>Transfer by</th>
                            <th>Transfer To</th>
                            <th>Product Name</th>
                            <th>Model</th>
                            <th>Category</th>
                            <th>Color</th>
                            <th>Sell Rate</th>
                            <th>Purchase Rate</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody style="display:none;" v-bind:style="{display: transfers.length > 0 ? '' : 'none'}">
                        <template v-for="(transfer, sl) in transfers">
                            <tr>
                                <td>{{ sl + 1 }}</td>
                                <td>{{ transfer.transfer_date }}</td>
                                <td>{{ transfer.transfer_by_name }}</td>
                                <td>{{ transfer.transfer_to_name }}</td>
                                <td>{{ transfer.Details[0].Product_Name }}</td>
                                <td>{{ transfer.Details[0].model }}</td>
                                <td>{{ transfer.Details[0].ProductCategory_Name }}</td>
                                <td>{{ transfer.Details[0].color_name }}</td>
                                <td>{{ transfer.Details[0].sell_rate }}</td>
                                <td>{{ transfer.Details[0].purchase_rate }}</td>
                                <td>{{ transfer.Details[0].quantity }}</td>
                                <td>{{ transfer.Details[0].total }}</td>
                                <td>
                                    <a href="" v-bind:href="`/transfer_invoice/${transfer.transfer_id}`" target="_blank" title="View invoice"><i class="fa fa-file"></i></a>
                                    <a href="" v-bind:href="`/product_transfer/${transfer.transfer_id}`" target="_blank" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a href="" @click.prevent="deleteTransfer(transfer.transfer_id)" title="Delete"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <tr v-for="(product, sl) in transfer.Details.slice(1)">
                                <td colspan="4" v-bind:rowspan="transfer.Details.length - 1" v-if="sl == 0"></td>
                                <td>{{ product.Product_Name }}</td>
                                <td>{{ product.model }}</td>
                                <td>{{ product.ProductCategory_Name }}</td>
                                <td>{{ product.color_name }}</td>
                                <td>{{ product.sell_rate }}</td>
                                <td>{{ product.purchase_rate }}</td>
                                <td>{{ product.quantity }}</td>
                                <td>{{ product.total }}</td>
                            </tr>
                            <tr style="font-weight:bold;">
                                <td colspan="10" style="text-align:right">Total</td>
                                <td style="text-align:center;">{{ transfer.Details.reduce((prev, curr) => {return prev + parseFloat(curr.quantity)}, 0) }}</td>
                                <td style="text-align:center;">{{ transfer.Details.reduce((prev, curr) => {return prev + parseFloat(curr.total)}, 0) }}</td>
                                <td></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
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
        el: '#transferList',
        data() {
            return {
                filter: {
                    branch: null,
                    search_type: 'without_details',
                    dateFrom: moment().format('YYYY-MM-DD'),
                    dateTo: moment().format('YYYY-MM-DD')
                },
                branches: [],
                selectedBranch: null,
                transfers: []
            }
        },
        created() {
            this.getBranches();
        },
        methods: {
            onChangeSearchType() {
                this.transfers = [];

            },
            getBranches() {
                axios.get('/get_branches').then(res => {
                    let thisBranchId = parseInt("<?php echo $this->session->userdata('BRANCHid'); ?>");
                    let ind = res.data.findIndex(branch => branch.brunch_id == thisBranchId);
                    res.data.splice(ind, 1);
                    this.branches = res.data;
                })
            },

            getTransfers() {
                if (this.selectedBranch != null) {
                    this.filter.branch = this.selectedBranch.brunch_id;
                } else {
                    this.filter.branch = null;
                }

                axios.post('/get_transfers', this.filter).then(res => {
                    this.transfers = res.data;
                })
            },

            deleteTransfer(transferId) {
                let confirmation = confirm('Are you sure?');
                if (confirmation == false) {
                    return;
                }
                axios.post('/delete_transfer', {
                    transferId: transferId
                }).then(res => {
                    let r = res.data;
                    alert(r.message);
                    if (r.success) {
                        this.getTransfers();
                    }
                })
            }
        }
    })
</script>