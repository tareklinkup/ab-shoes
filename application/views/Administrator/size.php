<div id="sizes">
    <div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom:15px;">
        <div class="col-md-4 col-md-offset-4">
            <form @submit.prevent="saveSize">
                <div class="form-group">
                    <label for="" class="col-md-2">Name</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="size.name" required>
                    </div>
                    <div class="col-md-1">
                        <input type="submit" value="Save" class="btn btn-success btn-sm">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6 col-md-offset-3">
            <div class="row">
                <div class="col-sm-12 form-inline">
                    <div class="form-group">
                        <label for="filter" class="sr-only">Filter</label>
                        <input type="text" class="form-control" v-model="filter" placeholder="Filter">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <datatable :columns="columns" :data="sizes" :filter-by="filter" style="margin-bottom: 5px;">
                            <template scope="{ row }">
                                <tr>
                                    <td width="15%">{{ row.sl }}</td>
                                    <td>{{ row.name }}</td>
                                    <td width="15%">
                                        <?php if($this->session->userdata('accountType') != 'u'){?>
                                        <button type="button" class="button edit" @click="editSize(row)">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" class="button" @click="deleteSize(row.id)">
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
        el: '#sizes',
        data: {
            size: {
                id: null,
                name: ''
            },
            sizes: [],
            columns: [
                { label: 'Serial', field: 'sl', align: 'center', filterable: false },
                { label: 'Name', field: 'name', align: 'center', filterable: false },
                { label: 'Action', align: 'center', filterable: false }
            ],
            page: 1,
            per_page: 10,
            filter: ''
        },
        created() {
            this.getSizes();
        },
        methods: {
            getSizes() {
                axios.get('/get_sizes')
                .then(res => {
                    this.sizes = res.data.map((item, sl) => {
                        item.sl = sl + 1;
                        return item;
                    });
                })
            },
            saveSize() {
                if(this.size.name == '') {
                    alert('Name is required');
                    return;
                }

                let url = '';
                if(this.size.id != null) {
                    url = '/update_size';
                } else {
                    url = "/save_size";
                    delete this.size.id
                }

                axios.post(url, this.size)
                .then(res => {
                    if(res.data.success) {
                        alert(res.data.message);
                        this.getSizes();
                        this.resetForm();
                    } else {
                        alert(res.data.message)
                    }
                })
            },
            resetForm() {
                this.size.id = null;
                this.size.name = ''
            },
            editSize(size) {
                Object.keys(this.size).forEach(key => {
                    this.size[key] = size[key]
                })
            },
            deleteSize(id) {
                if(confirm('Are you sure to delete this ?')) {
                    axios.post('/delete_size', {id: id})
                    .then(res => {
                        if(res.data.success) {
                            alert(res.data.message)
                            this.getSizes();
                        } else {
                            alert(res.data.message)
                        }
                    })
                    .catch(err => {
                        alert(err.response.data.message)
                    })
                }
            }
        }
    })
</script>