<style>

</style>

<div id="memberships">
    <div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
        <div class="col-md-offset-4 col-md-4">
            <form @submit.prevent="saveMembership">
                <div class="form-group">
                    <label for="name" class="col-md-3">Name <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input type="text" v-model="membership.name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-3 no-padding-right">Discount(%)<span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input type="number" min="0" step="0.01" v-model="membership.discount" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-md-3">Description</label>
                    <div class="col-md-9">
                        <textarea class="form-control" v-model="membership.description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="col-md-3"></label>
                    <div class="col-md-9">
                        <input type="submit" class="btn btn-block btn-success" value="Save">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-sm-12 form-inline">
                    <div class="form-group">
                        <label for="filter" class="sr-only">Filter</label>
                        <input type="text" class="form-control" v-model="filter" placeholder="Filter">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <datatable :columns="columns" :data="memberships" :filter-by="filter" style="margin-bottom: 5px;">
                            <template scope="{ row }">
                                <tr>
                                    <td width="10%">{{ row.sl }}</td>
                                    <td>{{ row.name }}</td>
                                    <td>{{ row.discount }}</td>
                                    <td>{{ row.description }}</td>
                                    <td width="10%">
                                        <?php if($this->session->userdata('accountType') != 'u'){?>
                                        <button type="button" class="button edit" @click="editMembership(row)">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" class="button" @click="deleteMembership(row.id)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <?php }?>
                                    </td>
                                </tr>
                            </template>
                        </datatable>
                        <datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url();?>assets/js/vue/vuejs-datatable.js"></script>
<script>
    const app = new Vue({
        el: '#memberships',
        data: {
            membership: {
                id: null,
                name: '',
                discount: 0,
                description: ''
            },
            memberships: [],
            columns: [
                { label: 'Serial', field: 'sl', align: 'center', filterable: false },
                { label: 'Name', field: 'name', align: 'center', filterable: false },
                { label: 'Discount', field: 'discount', align: 'center', filterable: false },
                { label: 'Description', field: 'description', align: 'center' },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        },
        created() {
            this.getMemberships();
        },
        methods: {
            getMemberships() {
                axios.get('/get_memberships')
                .then(res => {
                    this.memberships = res.data.map((item, sl) => {
                        item.sl = sl + 1;
                        return item;
                    });
                })
            },
            saveMembership() {
                if(this.membership.name == '') {
                    alert('Name is required');
                    return;
                }

                let url = '';
                if(this.membership.id != null) {
                    url = '/update_membership';
                } else {
                    url = '/save_membership';
                    delete this.membership.id;
                }

                axios.post(url, this.membership)
                .then(res => {
                    if(res.data.success) {
                        alert(res.data.message);
                        this.resetForm();
                        this.getMemberships();
                    } else {
                        alert(res.data.message);
                    }
                })
                .catch(err => {
                    alert(res.response.data.message);
                })
            },
            editMembership(member) {
                Object.keys(this.membership).forEach(key => {
                    this.membership[key] = member[key];
                })
            },
            deleteMembership(id) {
                if(confirm('Are you sure to delete this ?')) {
                    axios.post('/delete_membership', {id: id})
                    .then(res => {
                        if(res.data.success) {
                            alert(res.data.message);
                            this.getMemberships();
                        } else {
                            alert(res.data.message);
                        }
                    })
                    .catch(err => {
                        alert(res.response.data.message);
                    })
                }
            },
            resetForm() {
                this.membership.id = null;
                this.membership.name = '';
                this.membership.discount = 0;
                this.membership.description = '';
            }
        }
    })
</script>