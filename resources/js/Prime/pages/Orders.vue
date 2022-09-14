<template>
    <div class="grid crud-demo">
        <div class="col-12">
            <div class="card">
                <Toast/>
                <DataTable :value="orders" :lazy="true" :paginator="true" :rows="10" v-model:filters="filters"
                           paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                           :rowsPerPageOptions="[10,20,50,200]"
                           ref="dt" dataKey="id"
                           :totalRecords="totalRecords" :loading="loading" @page="onPage($event)" @sort="onSort($event)"
                           @filter="onFilter($event)" filterDisplay="row"
                           :globalFilterFields="['name','country.name', 'company', 'representative.name', 'tag']"
                           responsiveLayout="scroll"
                           v-model:expandedRows="expandedRows"
                           v-model:selection="selectedCustomers" :selectAll="selectAll"
                           @select-all-change="onSelectAllChange" @row-select="onRowSelect"
                           @rowExpand="onRowExpand" @rowCollapse="onRowCollapse"
                           @row-unselect="onRowUnselect" @row-select-all = "onRowSelectAll" @row-unselect-all = "onRowUnselectAll">
                    <Column :expander="true" headerStyle="width: 3rem" />

                    <Column selectionMode="multiple" headerStyle="width: 3em"></Column>
                    <Column>
                        <template #body="row">
                            <img :src="row.data.image" alt="" class="product-image">
                        </template>
                    </Column>
                    <Column field="order_id" header="Sipariş no" filterField="order_id" filterMatchMode="contains"
                            ref="country.name" :sortable="true">
                        <template #filter="{filterModel,filterCallback}">
                            <InputText type="text" v-model="filterModel.value" @keydown.enter="filterCallback()"
                                       class="p-column-filter" placeholder="Filtrele"/>
                        </template>
                    </Column>
                    <Column field="name" header="Müşteri Adı" filterMatchMode="startsWith" ref="name" :sortable="true">
                        <template #filter="{filterModel,filterCallback}">
                            <InputText type="text" v-model="filterModel.value" @keydown.enter="filterCallback()"
                                       class="p-column-filter" placeholder="Filtrele"/>
                        </template>
                    </Column>

                    <Column field="country" header="Ülke" filterMatchMode="contains" ref="company" :sortable="true">
                        <template #filter="{filterModel,filterCallback}">
                            <InputText type="text" v-model="filterModel.value" @keydown.enter="filterCallback()"
                                       class="p-column-filter" placeholder="Filtrele"/>
                        </template>
                    </Column>
                    <Column field="order_status" header="Siparis Durumu" filterField="order_status" :sortable="true">
                        <template #filter="{filterModel,filterCallback}">
                            <InputText type="text" v-model="filterModel.value" @keydown.enter="filterCallback()"
                                       class="p-column-filter" placeholder="Filtrele"/>
                        </template>
                    </Column>
                    <!--                    <Column field="order_status" header="Siparis Durumu" filterField="order_status" :sortable="true">-->
                    <!--                        <template #filter="{filterModel,filterCallback}">-->
                    <!--                            <InputText type="text" v-model="filterModel.value" @keydown.enter="filterCallback()"-->
                    <!--                                       class="p-column-filter" placeholder="Filtrele"/>-->
                    <!--                        </template>-->
                    <!--                    </Column>-->
                    <!--                    {{slotProps.value}}-->


                    <!--                    store list starts-->
                    <Column field="store" header="Stores" :filterMenuStyle="{'width':'14rem'}" style="min-width:12rem; ">
                        <template #body="{data}" >
                            <div style = "display:flex; flex-direction:row; ">
                                <span class="" style="display:flex; justify-content:center;">{{data.store ? data.store.store_name : data.store}}</span>
                                <!--                                <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="activateTagDialog(data)" />-->


                            </div>
                        </template>
                        <template #filter="{filterModel, filterCallback}">

                            <Dropdown v-model="tempStore" :options="Object.values(stores)"  placeholder="Select a Store" class="p-column-filter" :showClear="true" @change="filterCallback()">
                                <template #value="slotProps">
                                    <span :class="'' + slotProps.value" v-if="slotProps.value">{{slotProps.value ? slotProps.value : 'Select a Store'}}</span>

                                    <span v-else>{{slotProps.placeholder}}</span>

                                </template>
                                <template #option="slotProps">
                                    <span :class="'' + slotProps.option">{{slotProps.option}}</span>
                                </template>
                            </Dropdown>
                        </template>
                    </Column>
                    <!--                    store list ends-->




                    <Column field="tag" header="Tagss" :filterMenuStyle="{'width':'14rem'}" style="min-width:12rem; ">
                        <template #body="{data}" >
                            <div style = "display:flex; flex-direction:row; ">
                                <span class="customer-badge" style="display:flex; justify-content:center;">{{data.tag ? data.tag.name : data.tag}}</span>
                                <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="activateTagDialog(data)" />


                            </div>
                        </template>
                        <template #filter="{filterModel, filterCallback}">

                            <Dropdown v-model="tempTag" :options="Object.values(statuses)"  placeholder="Select a Tag" class="p-column-filter" :showClear="true" @change="filterCallback()">
                                <template #value="slotProps">
                                    <span :class="'customer-badge status-' + slotProps.value" v-if="slotProps.value">{{slotProps.value ? slotProps.value : 'Select a Tag'}}</span>

                                    <span v-else>{{slotProps.placeholder}}</span>

                                </template>
                                <template #option="slotProps">
                                    <span :class="'customer-badge status-' + slotProps.option">{{slotProps.option}}</span>
                                </template>
                            </Dropdown>
                        </template>
                    </Column>



                    <template #expansion="slotProps">
                        <div class="orders-subtable">
                            <h5>Bu siparisteki urunler ({{ slotProps.data.order_products.length }} urun)</h5>
                            <DataTable :value="slotProps.data.order_products"
                                       responsiveLayout="scroll">
                                <!--                                <template #empty>-->
                                <!--                                    No records found-->
                                <!--                                </template>-->
                                <Column header="Id" :sortable="true">
                                    <template #body="slotProps">
                                        {{slotProps.data.id}}
                                    </template>
                                </Column>
                                <Column field="product_name" header="Urun adi"></Column>
                                <Column field="pivot.price" header="Fiyat"></Column>
                            </DataTable>
                        </div>
                    </template>

                </DataTable>



                <!--                    change Tag of an order STARTS            -->
                <Dialog v-model:visible="tagDialog" :style="{width: '450px'}" header="Product Details" :modal="true" class="p-fluid">


                    <div class="p-field">
                        <label for="tag" class="p-mb-3">Select Tag</label>
                        <Dropdown  v-model="currentOrder.tag.name" :options="Object.values(statuses)" optionLabel="label" placeholder="Select a Status" class="p-column-filter" :showClear="true" @change="changeTag()">
                            <template #value="slotProps">
                                <span :class="'customer-badge status-' + slotProps.value" v-if="slotProps.value">{{slotProps.value}}</span>

                                <span v-else>{{slotProps.placeholder}}</span>

                            </template>
                            <template #option="slotProps">
                                <span :class="'customer-badge status-' + slotProps.option">{{slotProps.option}}</span>
                            </template>
                        </Dropdown>
                    </div>

                    <template #footer>
                        <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
                        <Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveTag()" />
                    </template>
                </Dialog>
                <!--                    change Tag of an order ENDS            -->



            </div>
        </div>
    </div>

</template>

<script>
import {FilterMatchMode, FilterOperator} from 'primevue/api';
import CustomerService from '../service/CustomerService'

export default {
    data() {
        return {
            tempTag: '',  //to hold a temporary tag value
            statuses: {}, //all tags retrieved from database
            tempStore: '', //to hold a temporary store value
            stores: {}, // all stores user has.
            tagDialog: false, // activate and deactivate dialog for tag changing
            currentOrder: {}, // temporarily holding the row order values while changing the tag
            submitted : false, // sumbit for tag changing.


            filters1: {},
            expandedRows: [],
            loading: false,
            totalRecords: 0,
            customers: null,//
            orders: [],
            selectedCustomers: [],
            selectAll: false,
            filters: {
                'order_id': {value: '', matchMode: FilterMatchMode.EQUALS},
                'name': {value: '', matchMode: FilterMatchMode.CONTAINS},
                'country': {value: '', matchMode: FilterMatchMode.EQUALS},
                'order_status': {value: '', matchMode: FilterMatchMode.EQUALS},
                'tag': {value: '', matchMode: FilterMatchMode.EQUALS},

                'store': {value: '', matchMode: FilterMatchMode.CONTAINS}, //for store filter

                'company': {value: '', matchMode: FilterMatchMode.CONTAINS},
                'representative.name': {value: '', matchMode: FilterMatchMode.CONTAINS},
            },
            lazyParams: {},
            columns: [
                {field: 'name', header: 'Name'},
                {field: 'country.name', header: 'Country'},
                {field: 'company', header: 'Company'},
                {field: 'store', header: 'Store'},
                {field: 'representative.name', header: 'Representative'}
            ]
        }
    },
    customerService: null,
    created() {
        this.customerService = new CustomerService();

        this.initFilters1();
        this.initFilters2();
    },
    mounted() {

        this.loading = true;

        this.lazyParams = {
            first: 0,
            rows: this.$refs.dt.rows,
            sortField: null,
            sortOrder: null,
            filters: this.filters
        };
        this.loadStatuses(); // getting tags from database
        this.loadStores();
        this.loadLazyData();
    },
    methods: {
        loadLazyData() {

            this.loading = true;

            this.customerService.getCustomers(this.lazyParams)
                .then(data => {
                        console.log('new data')
                        console.log(data)
                        data.data.forEach(order => {

                            if(order.tag === null ) {
                                order.tag = {id: null, user_id: null, name: ''}
                            }
                        })
                        this.totalRecords = data.total;
                        this.orders = data.data


                        // this.customers = data.customers;



                        // List must be filled with a value or null otherwise header checkbox will not work since it requires
                        // every member of totalRecords to have a value
                        //
                        for (let i=(this.orders.length); i<this.totalRecords; ++i) {

                            this.orders[i] = ''  //setting this value as null , breaking the last page of the pagination ( on the last page, if lets say there are 8 orders instead of ten, then it gives a null error.)
                        }                         //setting this as '' instead of null is a more healthly way.


                        // this.selectAll=true;


                        console.log()
                        this.loading = false;

                    }
                );
        },
        loadStatuses() {    //load tags from database
            this.loading = true;
            this.customerService.loadStatuses().then( data => {
                data.forEach( key => {
                    this.statuses[key.id] = key.name
                } )
            });
        },

        //load all stores user has
        loadStores() {
            this.loading = true;
            this.customerService.loadStores().then( data => {

                data.forEach(key => {
                    this.stores[key.id] = key.name
                })
            });
        },

        onPage(event) {
            this.selectedCustomers = [];  // when new page is loaded, remove previosly selected orders.

            this.lazyParams = event;
            this.loadLazyData();
        },
        onSort(event) {

            this.lazyParams = event;
            this.loadLazyData();
        },
        onFilter() {

            this.filters.tag.value = Object.keys(this.statuses).find(key => this.statuses[key] === this.tempTag) // filters order table with selected Tag
            this.lazyParams.filters = this.filters;
            this.loadLazyData();
        },


        onSelectAllChange(event) {


            const selectAll = event.checked;


            if (selectAll) {
                this.customerService.getCustomers().then(data => {
                    this.selectAll = true;
                    this.selectedCustomers = data.data;
                });
            } else {
                this.selectAll = false;
                this.selectedCustomers = [];
            }
        },
        onRowSelect(event) {


            this.selectAll = this.selectedCustomers.length === this.totalRecords
        },
        onRowUnselect() {
            this.selectAll = false;
        },
        onRowSelectAll(event) {
            this.selectAll = true;

            console.log(event)
        },
        onRowUnselectAll(event) {
            this.selectAll = false;

            console.log('unselected header')
            console.log(event)
        },

        onRowExpand(event) {

            this.$toast.add({severity: 'info', summary: 'Product Expanded', detail: event.data.name, life: 3000});
        },
        onRowCollapse(event) {

            this.$toast.add({severity: 'success', summary: 'Product Collapsed', detail: event.data.name, life: 3000});
        },

        initFilters1() {

            this.filters1 = {
                'global': {value: null, matchMode: FilterMatchMode.CONTAINS}
            }
        },
        initFilters2() {

            this.filters2 = {
                'global': {value: null, matchMode: FilterMatchMode.CONTAINS}
            }
        },
        changeTag() {

            // Since the current row's order is changed. Id of this tag also be changed with the new tag's id.
            this.currentOrder.tag.id = Object.keys(this.statuses).find(key => this.statuses[key] === this.currentOrder.tag.name)

        },
        activateTagDialog(data) {

            this.currentOrder = {...data}   // hold the current row's  order data temporarily. (v-bind to tag changer dialog)
            this.tagDialog = true; //activate dialog

        },
        hideDialog() {  //deactivate dialog
            this.tagDialog = false;
            this.submitted = false;
        },
        saveTag() { // Save the order with its new tag to database
            this.submitted = true;

            if (this.currentOrder.tag.name.trim()) {
                console.log("currently")
                console.log(this.currentOrder.tag)

                this.customerService.saveTag(this.currentOrder).then( data => {

                    console.log(data);

                } );
                this.$toast.add({severity:'success', summary: 'Successful', detail: 'Product Created', life: 3000});


                this.tagDialog = false;
                this.currentOrder = {};
            }
        },

    }
}
</script>

<style scoped lang="scss">
.orders-subtable {
    padding: 1rem;
}
.product-badge {
    border-radius: 2px;
    padding: .25em .5rem;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: .3px;

    &.status-instock {
        background: #C8E6C9;
        color: #256029;
    }

    &.status-outofstock {
        background: #FFCDD2;
        color: #C63737;
    }

    &.status-lowstock {
        background: #FEEDAF;
        color: #8A5340;
    }

}

.product-image {
    width: 50px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23)
}
.product-image:hover{
    box-shadow: 0 6px 3px rgba(0, 0, 0, 0.16), 0 6px 3px rgba(0, 0, 0, 0.23)
}



.customer-badge {
    margin:5px;
    border-radius: 2px;
    padding: .25em .5rem;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: .3px;
    background-color: #C8E6C9;
    color: #256029;
}
</style>
