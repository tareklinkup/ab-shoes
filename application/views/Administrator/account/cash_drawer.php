<div id="drawers">
    <div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
        <form @submit.prevent="saveCashDrawer">
            <div class="col-md-5 col-md-offset-1">
                <div class="form-group">
                    <label class="col-md-5">Date</label>
                    <div class="col-md-7">
                        <input type="date" class="form-control" v-model="drawer.date" required @change="getCashDrawer">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Thousand (Tk. 1000)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.thousand" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Five Hundred (Tk. 500)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.five_hundred" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Two Hundred (Tk. 200)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.two_hundred" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">One Hundred (Tk. 100)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.one_hundred" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Fifty (Tk. 50)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.fifty" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Twenty (Tk. 20)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.twenty" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Ten (Tk. 10)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.ten" @input="calculateTotal" required>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label class="col-md-5">Five (Tk. 5)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.five" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Text One (1)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.text1" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Text Two (2)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.text2" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Text Three (3)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.text3" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Text Four (4)</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.text4" @input="calculateTotal" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5">Total</label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" v-model="drawer.total" required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-5"></label>
                    <div class="col-md-7">
                        <input type="submit" class="btn btn-block btn-success" value="Save">
                    </div>
                </div>
            </div>
        </form>
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
                <datatable :columns="columns" :data="drawers" :filter-by="filter" style="margin-bottom: 5px;">
                    <template scope="{ row }">
                        <tr>
                            <td width="5%">{{ row.sl }}</td>
                            <td>{{ row.thousand }}</td>
                            <td>{{ row.five_hundred }}</td>
                            <td>{{ row.two_hundred }}</td>
                            <td>{{ row.one_hundred }}</td>
                            <td>{{ row.fifty }}</td>
                            <td>{{ row.twenty }}</td>
                            <td>{{ row.ten }}</td>
                            <td>{{ row.five }}</td>
                            <td>{{ row.text1 }}</td>
                            <td>{{ row.text2 }}</td>
                            <td>{{ row.text3 }}</td>
                            <td>{{ row.text4 }}</td>
                            <td>{{ row.total }}</td>
                            <td width="10%">
                                <?php if ($this->session->userdata('accountType') != 'u') { ?>
                                    <button type="button" class="button edit" @click="editDrawer(row)">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" class="button" @click="deleteDrawer(row.id)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    </template>
                </datatable>
                <datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    const app = new Vue({
        el: '#drawers',
        data: {
            drawer: {
                id: null,
                date: moment().format('YYYY-MM-DD'),
                thousand: 0,
                five_hundred: 0,
                two_hundred: 0,
                one_hundred: 0,
                fifty: 0,
                twenty: 0,
                ten: 0,
                five: 0,
                total: 0,
                text2: '',
                text3: '',
                text4: '',
            },
            drawers: [],
            columns: [{
                    label: 'Serial',
                    field: 'sl',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Thousand',
                    field: 'thousand',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Five Hun.',
                    field: 'five_hundred',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Two Hun.',
                    field: 'two_hundred',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'One Hun.',
                    field: 'one_hundred',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Fifty',
                    field: 'fifty',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Twenty',
                    field: 'twenty',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Ten',
                    field: 'ten',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Five',
                    field: 'five',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'T. One',
                    field: 'text1',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'T. Two',
                    field: 'text2',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'T. Three',
                    field: 'text3',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'T. Four',
                    field: 'text4',
                    align: 'center',
                    filterable: false
                },
                {
                    label: 'Total',
                    field: 'total',
                    align: 'center',
                    filterable: false
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
        },
        created() {
            this.getCashDrawer();
        },
        methods: {
            getCashDrawer() {
                let filter = {
                    dateFrom: this.drawer.date,
                    dateTo: this.drawer.date
                }
                axios.post('/get_cashdrawers', filter)
                    .then(res => {
                        this.drawers = res.data.map((item, sl) => {
                            item.sl = sl + 1;
                            return item;
                        });
                    })
            },
            editDrawer(drawer) {
                Object.keys(this.drawer).forEach(key => {
                    this.drawer[key] = drawer[key]
                })
            },
            calculateTotal() {
                let thousand = (1000 * +this.drawer.thousand);
                let fiveHundred = (500 * +this.drawer.five_hundred);
                let twoHundred = (200 * +this.drawer.two_hundred);
                let oneHundred = (100 * +this.drawer.one_hundred);
                let fifty = (50 * +this.drawer.fifty);
                let twenty = (20 * +this.drawer.twenty);
                let ten = (10 * +this.drawer.ten);
                let five = (5 * +this.drawer.five);
                let text1 = this.drawer.text1 == '' || this.drawer.text1 == undefined ? 0 : this.drawer.text1;
                let text2 = this.drawer.text2 == '' || this.drawer.text2 == undefined ? 0 : this.drawer.text2;
                let text3 = this.drawer.text3 == '' || this.drawer.text2 == undefined ? 0 : this.drawer.text2;
                let text4 = this.drawer.text4 == '' || this.drawer.text2 == undefined ? 0 : this.drawer.text2;

                this.drawer.total = (+thousand + +fiveHundred + +twoHundred + +oneHundred + +fifty + +twenty + +ten + +five + +text1 + +text2 + +text3 + +text4).toFixed(2)
            },
            saveCashDrawer() {
                let url = '';
                if (this.drawer.id != null) {
                    url = '/update_cashdrawer';
                } else {
                    url = '/add_cashdrawer';
                    delete this.drawer.id;
                }

                axios.post(url, this.drawer)
                    .then(res => {
                        if (res.data.success) {
                            alert(res.data.message)
                            this.resetForm();
                            this.getCashDrawer();
                        } else {
                            alert(res.data.message)
                        }
                    })
                    .catch(err => {
                        alert(err.response.data.message)
                    })
            },
            resetForm() {
                this.drawer.id = null;
                this.drawer.thousand = 0;
                this.drawer.five_hundred = 0;
                this.drawer.two_hundred = 0;
                this.drawer.one_hundred = 0;
                this.drawer.fifty = 0;
                this.drawer.twenty = 0;
                this.drawer.ten = 0;
                this.drawer.five = 0;
                this.drawer.total = 0;
                this.drawer.date = moment().format('YYYY-MM-DD');
            },
            deleteDrawer(id) {
                axios.post('/delete_cashdrawer', {
                        id: id
                    })
                    .then(res => {
                        alert(res.data.message)
                        this.getCashDrawer();
                    })
                    .catch(err => {
                        alert(err.response.data.message)
                    })
            }
        }
    })
</script>