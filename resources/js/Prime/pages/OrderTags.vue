<template>
    <div class="grid crud-demo">
        <div class="col-12">
            <div class="card">
                <Toast/>


                <DataTable ref="dt" :value="tags" v-model:selection="selectedProducts" dataKey="id"
                           :paginator="true" :rows="10" :filters="filters"
                           paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                           :rowsPerPageOptions="[5,10,25]"
                           currentPageReportTemplate=" {first}-{last} of {totalRecords}"
                           responsiveLayout="scroll">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Sipariş durumları</h5>
                            <div class="my-2">
                                <Button label="Ekle" icon="pi pi-plus" class="p-button-success mr-2" @click="openNew"/>

                            </div>
                        </div>
                    </template>

                    <Column field="id" header="#"></Column>
                    <Column field="name" header="İsim" :sortable="true">
                        <template #body="slotProps">
                            <span class="p-column-title">Name</span>
                            {{ slotProps.data.name }}
                        </template>
                    </Column>
                    <Column field="order_count" header="Sipariş sayısı"></Column>


                    <Column>
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2"
                                    @click="editProduct(slotProps.data)"/>
                            <Button icon="pi pi-trash" class="p-button-rounded p-button-warning"
                                    @click="confirmDeleteProduct(slotProps.data)"/>
                        </template>
                    </Column>
                </DataTable>

                <Dialog v-model:visible="tagDialog" :style="{width: '450px'}" header="Durum ekle" :modal="true"
                        class="p-fluid">

                    <div class="field">
                        <label for="name">İsim</label>
                        <InputText id="name" v-model.trim="tag.name" required="true" autofocus
                                   :class="{'p-invalid': submitted && !tag.name}"/>
                        <small class="p-invalid" v-if="submitted && !tag.name">Bu alan gerekli.</small>
                    </div>

                    <template #footer>
                        <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
                        <Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveTag"/>
                    </template>
                </Dialog>

                <Dialog v-model:visible="deleteTagDialog" :style="{width: '450px'}" header="Emin misiniz?"
                        :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem"/>
                        <span v-if="tag">Bu durumu silmek istediğinize emin misiniz: <b>{{ tag.name }}</b>?
                        </span>
                    </div>
                    <template #footer>
                        <Button label="Vazgeç" icon="pi pi-times" class="p-button-text"
                                @click="deleteTagDialog = false"/>
                        <Button label="Sil" icon="pi pi-check" class="p-button-text" @click="deleteProduct"/>
                    </template>
                </Dialog>


            </div>
        </div>
    </div>

</template>

<script>
import {FilterMatchMode} from 'primevue/api';

export default {
    data() {
        return {
            tags: null,
            tagDialog: false,
            deleteTagDialog: false,
            deleteProductsDialog: false,
            tag: {},
            selectedProducts: null,
            filters: {},
            submitted: false,

        }
    },//ff
    created() {
        this.initFilters();
    },
    mounted() {
        this.getTags()
    },
    methods: {
        getTags() {
            axios.get(route('order_tags.index'))
                .then(res => {
                    this.tags = res.data
                })
        },
        openNew() {
            this.tag = {};
            this.submitted = false;
            this.tagDialog = true;
        },
        hideDialog() {
            this.tagDialog = false;
            this.submitted = false;
        },
        saveTag() {
            this.submitted = true;
            if (typeof (this.tag.id) != "undefined") {
                //update
                console.log("update")
                axios.patch(route('order_tags.update', {order_tag: this.tag.id}), {
                    name: this.tag.name
                }).then(r => {
                    console.log(r.data)
                }).catch(error => {
                    this.$toast.add({
                        severity: 'error',
                        summary: 'Hata',
                        detail: error.response.data.message,
                        life: 3000
                    });

                })
            } else {
                //save
                if (this.tag.name.trim()) {
                    axios.post(route("order_tags.store"), {name: this.tag.name})
                }
            }

            this.hideDialog()
            this.getTags()
        },
        editProduct(product) {
            this.tag = {...product};
            this.tagDialog = true;
        },
        confirmDeleteProduct(product) {
            this.tag = product;
            this.deleteTagDialog = true;
        },
        deleteProduct() {

            axios.delete(route('order_tags.destroy', {order_tag: this.tag.id})).then(result=>{
                console.log(result.data)
            }).catch(error => {
                this.$toast.add({severity: 'error', summary: 'Hata', detail: error.response.data.message, life: 3000});

            })
            this.deleteTagDialog = false;
            this.tag = {};
            this.$toast.add({severity: 'success', summary: 'Basarili', detail: 'Silindi', life: 3000});
            this.getTags()
        },

        initFilters() {
            this.filters = {
                'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
            }
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
</style>
