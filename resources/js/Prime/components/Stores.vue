<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <h5>Mağazalar</h5>
                <DataTable :value="stores" responsiveLayout="scroll">
                    <template #header>
                        <Button label="Magaza ekle"
                                @click="openLink('stores.create', '_self')"
                                icon="pi pi-plus"/>
                    </template>
                    <Column field="name" header="Mağaza adı"></Column>
                    <Column field="store_id" header="AE ID"></Column>
                    <Column field="total_orders" header="Toplam ciro">
                        <template #body="slot">
                            ${{ (slot.data.total_orders).toFixed(2) }}
                        </template>
                    </Column>
                    <Column field="url" header="URL">
                        <template #body="slot">
                            <a :href="slot.data.url" target="_blank">Magazayi gor</a>
                        </template>
                    </Column>
                    <Column header="Islemler">
                        <template #body="slot">
                            <SplitButton label="İşlemler" icon="pi pi-plus" :model="operations"></SplitButton>

                        </template>
                    </Column>
                    <template #footer>
                        Toplam cironuz ${{stores ? stores.reduce((a, b) => a + (b['total_orders'] || 0), 0).toFixed(2) : 0 }}.
                    </template>
                </DataTable>
            </div>
        </div>

    </div>
</template>

<script>
import {FilterMatchMode, FilterOperator} from 'primevue/api';
import CustomerService from "../service/CustomerService";
import ProductService from '../service/ProductService';
import Button from "primevue/button";
import SplitButton from 'primevue/splitbutton';

export default {
    data() {
        return {
            stores: [],
            operations: [
                {
                    label: 'Sil',
                    icon: 'pi pi-times',
                    command: () => {
                        this.$toast.add({severity: 'warn', summary: 'Delete', detail: 'Data Deleted', life: 3000});
                    }
                }, {
                    label: 'Yenile',
                    icon: 'pi pi-refresh',
                    command: () => {
                        this.$toast.add({severity: 'warn', summary: 'Delete', detail: 'Data Deleted', life: 3000});
                    }
                }
            ]
        }
    },

    created() {
        axios.get(route('api.stores')).then(data => {
            this.stores = data.data
        })
    },
    mounted() {

    },
    methods: {
        openLink: function (url, target) {
            window.open(route(url), target)
        }
    },
    components: {
        Button, SplitButton
    }
}
</script>

<style scoped lang="scss"></style>
