<div id="drawers">
    <div class="row" style="border-bottom: 1px solid #ccc;padding-bottom: 5px">
        <form @submit.prevent="getCashDrawers">
            <div class="form-group">
                <label for="dateFrom" class="col-md-1">DateFrom</label>
                <div class="col-md-2">
                    <input type="date" class="form-control" v-model="filter.dateFrom" />
                </div>
            </div>
            <div class="form-group">
                <label for="dateFrom" class="col-md-1">DateTo</label>
                <div class="col-md-2">
                    <input type="date" class="form-control" v-model="filter.dateTo" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-1">
                    <input type="submit" class="btn btn-xs btn-primary" value="Search"/>
                </div>
            </div>
        </form>
    </div>
    
    <div class="row" style="margin-top: 15px; display: none" :style="{display: records.length > 0 ? '' : 'none'}">
        <div class="col-md-12">
            <a href="" @click.prevent="print"><i class="fa fa-print"></i> Print</a>
        </div>    
        <div class="col-md-12">
            <div class="table-responsive" id="printContent">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <th>Sl</th>
                        <th>Date</th>
                        <th>Thousand</th>
                        <th>Five Hundred</th>
                        <th>Two Hundred</th>
                        <th>One Hundred</th>
                        <th>Fifty</th>
                        <th>Twenty</th>
                        <th>Ten</th>
                        <th>Five</th>
                        <th>Text One</th>
                        <th>Text Two</th>
                        <th>Text Three</th>
                        <th>Text Four</th>
                        <th>Total</th>
                    </thead>
                    <tbody>
                        <tr v-for="(row, sl) in records">
                            <td width="5%">{{ sl + 1}}</td>
                            <td>{{ row.date }}</td>
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
                        </tr>
                        <tr>
                            <td colspan="10" style="text-align: right;"><strong>Total Amount</strong></td>
                            <td><strong>{{ records.reduce((p, c) => {return +p + +c.total}, 0).toFixed(2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/moment.min.js"></script>
<script>
    const app = new Vue({
        el: '#drawers',
        data: {
            filter: {
                dateFrom: moment().format('YYYY-MM-DD'),
                dateTo: moment().format('YYYY-MM-DD')
            },
            records: []
        },
        created(){
            this.getCashDrawers();
        },
        methods: {
            getCashDrawers() {
                axios.post('/get_cashdrawers', this.filter)
                .then(res => {
                    this.records = res.data;
                })
            },
            async print() {
                let printContent = `
                    <div class="container">
                        <h4 style="text-align:center">Cash Drawers List</h4 style="text-align:center">
						<div class="row">
							<div class="col-xs-12">
								${document.querySelector('#printContent').innerHTML}
							</div>
						</div>
                    </div>
                `;

                let printWindow = window.open('', '', `width=${screen.width}, height=${screen.height}`);
                printWindow.document.write(`
                    <?php $this->load->view('Administrator/reports/reportHeader.php'); ?>
                `);

                printWindow.document.body.innerHTML += printContent;
                printWindow.focus();
                await new Promise(r => setTimeout(r, 1000));
                printWindow.print();
                printWindow.close();
            }
        }
    })
</script>