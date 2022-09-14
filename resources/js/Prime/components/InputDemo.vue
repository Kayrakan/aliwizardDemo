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
                           :globalFilterFields="['name','country.name', 'company', 'representative.name']"
                           responsiveLayout="scroll"
                           v-model:expandedRows="expandedRows"
                           v-model:selection="selectedCustomers" :selectAll="selectAll"
                           @select-all-change="onSelectAllChange" @row-select="onRowSelect"
                           @row-unselect="onRowUnselect">
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
                    <template #expansion="row_inner">
                        <div class="orders-subtable">
                            <h5>Bu siparisteki urunler ({{ row_inner.data.order_products.length }} urun)</h5>
                            <DataTable :values="row_inner.data.order_products" responsiveLayout="scroll">
                                <Column field="id" header="Id" sortable></Column>
                                <Column field="title" header="Urun adi"></Column>
                                <Column field="price" header="Fiyat"></Column>
                            </DataTable>
                        </div>
                    </template>

                </DataTable>
            </div>
        </div>
    </div>

</template>

<script>
import {FilterMatchMode} from 'primevue/api';
import CustomerService from '../service/CustomerService'

export default {
    data() {
        return {
            expandedRows: [],
            loading: false,
            totalRecords: 0,
            customers: null,//
            orders: null,
            selectedCustomers: null,
            selectAll: false,
            filters: {
                'order_id': {value: '', matchMode: FilterMatchMode.EQUALS},
                'name': {value: '', matchMode: FilterMatchMode.CONTAINS},
                'country': {value: '', matchMode: FilterMatchMode.EQUALS},
                'order_status': {value: '', matchMode: FilterMatchMode.EQUALS},
                'company': {value: '', matchMode: FilterMatchMode.CONTAINS},
                'representative.name': {value: '', matchMode: FilterMatchMode.CONTAINS},
            },
            lazyParams: {},
            columns: [
                {field: 'name', header: 'Name'},
                {field: 'country.name', header: 'Country'},
                {field: 'company', header: 'Company'},
                {field: 'representative.name', header: 'Representative'}
            ]
        }
    },
    customerService: null,
    created() {
        this.customerService = new CustomerService();
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

        this.loadLazyData();
    },
    methods: {
        loadLazyData() {
            this.loading = true;

            this.customerService.getCustomers(this.lazyParams)
                .then(data => {
                        this.orders = data.data
                        this.customers = data.customers;
                        this.totalRecords = data.total;
                        this.loading = false;
                    }
                );
        },
        onPage(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        onSort(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        onFilter() {
            this.lazyParams.filters = this.filters;
            this.loadLazyData();
        },
        onSelectAllChange(event) {
            const selectAll = event.checked;

            if (selectAll) {
                this.customerService.getCustomers().then(data => {
                    this.selectAll = true;
                    this.selectedCustomers = data.customers;
                });
            } else {
                this.selectAll = false;
                this.selectedCustomers = [];
            }
        },
        onRowSelect() {
            this.selectAll = this.selectedCustomers.length === this.totalRecords
        },
        onRowUnselect() {
            this.selectAll = false;
        }
    }
}
</script>

<style scoped lang="scss">
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
</style>
