<template>
    <div class="grid">
        <div class="col-12 lg:col-6  xl:col-6  ">
        </div>
        <div class="col-12 lg:col-6  xl:col-3  ">
            <div class="card mb-0">
                Mağaza:
                <MultiSelect v-model="selectedStores"   :options="stores" @change="storeSelected"
                             optionLabel="store_name"
                             optionValue="id"
                             style="max-width: 14em;"
                             placeholder="Magaza sec" display="comma"/>
            </div>
        </div>
        <div class="col-12 lg:col-6  xl:col-3 ">
            <div class="card mb-0">
                Zaman aralığı:
                <Dropdown v-model="selectedDate"  :options="dateOptions" optionLabel="label"  @change="timeIntervalSelected"
                          optionValue="code"
                          placeholder="Zaman aralığı seçin"/>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Sipariş sayısı</span>
                        <div class="text-900 font-medium text-xl">{{ storeStats.orders_count }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-blue-100 border-round"
                         style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-shopping-cart text-blue-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-500">Yoldaki siparisler:</span>
                <span class="text-green-500 font-medium">{{ storeStats.orders_waiting_buyer_accept_count }}</span>
                <br>
                <span class="text-500">Gönderim bekleyenler:</span>
                <span class="text-green-500 font-medium">{{ storeStats.orders_not_shipped_count }}</span>


            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Satış</span>
                        <div class="text-900 font-medium text-xl">${{ storeStats.orders_total_amount }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-orange-100 border-round"
                         style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-dollar text-orange-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-500">Yoldaki siparisler:</span>
                <span class="text-green-500 font-medium">${{ storeStats.orders_waiting_buyer_accept_amount }}</span>
                <br>
                <span class="text-500">Gonderim bekleyenler:</span>
                <span class="text-green-500 font-medium">${{ storeStats.orders_not_shipped_amount }}</span>

            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Müşteriler</span>
                        <div class="text-900 font-medium text-xl">{{ storeStats.customers_count }}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-cyan-100 border-round"
                         style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-user text-cyan-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-500">Müşteri başına düşen sipariş:</span>
                <span class="text-green-500 font-medium">{{
                        (storeStats.orders_count / storeStats.customers_count).toFixed(2)
                    }}</span>
            </div>
        </div>
        <div class="col-12 lg:col-6 xl:col-3">
            <div class="card mb-0">
                <div class="flex justify-content-between mb-3">
                    <div>
                        <span class="block text-500 font-medium mb-3">Satılmış olan ürünler</span>
                        <div class="text-900 font-medium text-xl">{{storeStats.number_of_products_sold}}</div>
                    </div>
                    <div class="flex align-items-center justify-content-center bg-purple-100 border-round"
                         style="width:2.5rem;height:2.5rem">
                        <i class="pi pi-tags text-purple-500 text-xl"></i>
                    </div>
                </div>
                <span class="text-green-500 font-medium">.. </span>
                <span class="text-500">..</span>
            </div>
        </div>

        <div class="col-12 xl:col-6">
            <div class="card">
                <h5>Gönderim bekleyen siparişler</h5>
                <DataTable :value="lastOrders" :rows="5" :paginator="true" class="p-datatable-customers"
                           dataKey="id"
                           v-model:filters="filters2"
                           filterDisplay="row"
                           :loading="loading2"
                           :globalFilterFields="['tag']"
                           responsiveLayout="scroll">

                    <template #header>
                        <div class="flex justify-content-end">
                        <span class="p-input-icon-left ">
                            <i class="pi pi-search" />
                            <InputText v-model="filters2['tag'].value" placeholder="Keyword Search" />
                        </span>
                        </div>
                    </template>

                    <template #empty>
                        No customers found.
                    </template>
                    <template #loading>
                        Loading customers data. Please wait.
                    </template>

                    <Column style="width:15%">
                        <template #header>
                            Ürün(ilk)
                        </template>
                        <template #body="slotProps">
                            <a :href="slotProps.data.image" target="_blank">
                                <img :src="slotProps.data.image" :alt="slotProps.data.image" width="50"
                                     class="shadow-2"/>
                            </a>
                        </template>
                    </Column>
                    <Column field="name" header="Alıcı" :sortable="true" style="width:35%">

                    </Column>
                    <Column field="amount" header="Fiyat" :sortable="true" style="width:35%">
                        <template #body="slotProps">
                            ${{ slotProps.data.amount }}
                        </template>
                    </Column>
                    <Column style="width:15%">
                        <template #header>
                            Ulke
                        </template>
                        <template #body="slotProps">
                            {{ slotProps.data.country }}
                        </template>
                    </Column>



<!--                    tags colum filter-->

                    <Column field="tag" header="Tags" :showFilterMenu="false" style="min-width:12rem">
                        <template #body="{data}">
                            <span :class="'customer-badge status-' + data.tag">{{data.tag}}</span>
                        </template>
                        <template #filter="{filterModel,filterCallback}">
                            <Dropdown v-model="filterModel.value" @change="filterCallback()" :options="statuses" placeholder="Any" class="p-column-filter" :showClear="true">
                                <template #value="slotProps">
                                    <span :class="'customer-badge status-' + slotProps.value" v-if="slotProps.value">{{slotProps.value}}</span>
                                    <span v-else>{{slotProps.placeholder}}</span>
                                </template>
                                <template #option="slotProps">
                                    <span :class="'customer-badge status-' + slotProps.option">{{slotProps.option}}</span>
                                </template>
                            </Dropdown>
                        </template>
                    </Column>

<!--                    TAGS COLUMN  ENDS-->


<!--                    <Column style="width: 10%">-->
<!--                        <template #header>#</template>-->
<!--                        <template #body>-->
<!--                            <Button icon="pi pi-search" class="p-button-raised p-button-rounded" />-->

<!--                        </template>-->
<!--                    </Column>-->

<!--                    tags column starts-->

<!--                    <Column field="tag" header="Tagss"  :sortable="true" :filterMenuStyle="{'width':'14rem'}" style="min-width:12rem;  ">-->
<!--                        <template #body="{data}" >-->
<!--                            <div style = "display:flex; flex-direction:row; ">-->
<!--                                <span class="customer-badge" style="display:flex; justify-content:center;">{{data.tag ? data.tag.name : data.tag}}</span>-->
<!--&lt;!&ndash;                                <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="activateTagDialog(data)" />&ndash;&gt;-->


<!--                            </div>-->
<!--                        </template>-->
<!--                        <template #filter="{filterModel, filterCallback}">-->

<!--                            <Dropdown v-model="tempTag" :options="Object.values(statuses)"  placeholder="Select a Tag" class="p-column-filter" :showClear="true" @change="filterCallback()">-->
<!--                                <template #value="slotProps">-->
<!--                                    <span :class="'customer-badge status-' + slotProps.value" v-if="slotProps.value">{{slotProps.value ? slotProps.value : 'Select a Tag'}}</span>-->

<!--                                    <span v-else>{{slotProps.placeholder}}</span>-->

<!--                                </template>-->
<!--                                <template #option="slotProps">-->
<!--                                    <span :class="'customer-badge status-' + slotProps.option">{{slotProps.option}}</span>-->
<!--                                </template>-->
<!--                            </Dropdown>-->
<!--                        </template>-->
<!--                    </Column>-->



<!--                    tags column ends-->




                </DataTable>
            </div>



<!--            country based stats starts-->
            <div class="card">
                <h5>Ülke bazlı siparişler</h5>
                <DataTable :value="country_totalorder_with_revenue" :rows="5" :paginator="true" responsiveLayout="scroll" class="p-datatable-customers" showGridlines>



                    <Column style="width:15%" :sortable="false"  >
                        <template #header>
                            Ulke
                        </template>
                        <template #body="slotProps">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAeAQMAAABt+G7kAAAAA1BMVEVMaXFNx9g6AAAAAXRSTlMAQObYZgAAAAtJREFUeF5jGHYAAADSAAEzF5gpAAAAAElFTkSuQmCC" :class="'flag flag-' + slotProps.data.country.toLowerCase()" width="30" >
                            <span class="image-text">{{ slotProps.data.country }}</span>

                        </template>
                    </Column>


                    <Column field="total" header="Sipariş Sayısı" :sortable="true" style="width:35%">
                        <template #body="slotProps">
                            ${{ slotProps.data.total }}
                        </template>
                    </Column>

                    <Column field="revenue" header="ciro" :sortable="true" style="width:35%">
                        <template #body="slotProps">
                            ${{ slotProps.data.revenue }}
                        </template>
                    </Column>

                    <Column field="total_perc" header="toplam şiparişe oranı" :sortable="true" style="width:35%">
                        <template #body="slotProps">
                            ${{ slotProps.data.total_perc }}
                        </template>
                    </Column>

                    <Column field="revenue_perc" header="toplam ciroya oranı" :sortable="true" style="width:35%">
                        <template #body="slotProps">
                            ${{ slotProps.data.revenue_perc }}
                        </template>
                    </Column>


                </DataTable>
            </div>

<!--            country based stats ends-->





        </div>
        <div class="col-12 xl:col-6">
            <div class="card">
                <h5>Sales Overview</h5>
<!--                <Chart type="line" :data="lineData"/>-->
                <Chart type="bar" :data="basicData" :options="basicOptions" />


            </div>


<!--            TAG BASED STATS-->

            <div class="card">
                <h5>TAG bazlı siparişler</h5>
                <DataTable :value="orders_based_on_tags" :rows="5" :paginator="true" responsiveLayout="scroll">

                    <Column style="width:15%" :sortable="false"  >
                        <template #header>
                            Tag
                        </template>
                        <template #body="slotProps">
                            {{ slotProps.data.tag }}
                        </template>
                    </Column>


                    <Column field="total" header="Sipariş Sayısı" :sortable="true" style="width:35%">
                        <template #body="slotProps">
                            ${{ slotProps.data.total }}
                        </template>
                    </Column>

                    <Column field="revenue" header="ciro" :sortable="true" style="width:35%">
                        <template #body="slotProps">
                            ${{ slotProps.data.revenue }}
                        </template>
                    </Column>

                    <Column field="total_perc" header="toplam şiparişe oranı" :sortable="true" style="width:35%">
                        <template #body="slotProps">
                            ${{ slotProps.data.total_perc }}
                        </template>
                    </Column>

                    <Column field="revenue_perc" header="toplam ciroya oranı" :sortable="true" style="width:35%">
                        <template #body="slotProps">
                            ${{ slotProps.data.revenue_perc }}
                        </template>
                    </Column>


                </DataTable>
            </div>
<!--            TAG BASED STATS END-->

<!--            BEST SELLING PRODUCTS STARTS-->
            <div class="card">
                <div class="flex justify-content-between align-items-center mb-5">
                    <h5>Best Selling Products</h5>
                    <div>
                        <Button icon="pi pi-ellipsis-v" class="p-button-text p-button-plain p-button-rounded"
                                @click="$refs.menu2.toggle($event)"></Button>
                        <Menu ref="menu2" :popup="true" :model="items"></Menu>
                    </div>
                </div>

                <ul class="list-none p-0 m-0">

                    <li v-for="value in storeStats.top_ten" class="flex flex-column md:flex-row md:align-items-center md:justify-content-between mb-4">
                        <div>
                            <span class="text-900 font-medium mr-2 mb-1 md:mb-0">{{value.title}}</span>
                            <!--                            <div class="mt-1 text-600">Clothing</div>-->
                        </div>
                        <div class="mt-2 md:mt-0 flex align-items-center">
                            <div class="surface-300 border-round overflow-hidden w-10rem lg:w-6rem" style="height:8px">
                                <!--                                <div class="bg-orange-500 h-full" style="width:50%"></div>-->
                            </div>
                            <!--                            <span class="text-orange-500 ml-3 font-medium">%50</span>-->
                        </div>
                    </li>

                    <!--                    <li class="flex flex-column md:flex-row md:align-items-center md:justify-content-between mb-4">-->
                    <!--                        <div>-->
                    <!--                            <span class="text-900 font-medium mr-2 mb-1 md:mb-0">Space T-Shirt</span>-->
                    <!--                            <div class="mt-1 text-600">Clothing</div>-->
                    <!--                        </div>-->
                    <!--                        <div class="mt-2 md:mt-0 flex align-items-center">-->
                    <!--                            <div class="surface-300 border-round overflow-hidden w-10rem lg:w-6rem" style="height:8px">-->
                    <!--                                <div class="bg-orange-500 h-full" style="width:50%"></div>-->
                    <!--                            </div>-->
                    <!--                            <span class="text-orange-500 ml-3 font-medium">%50</span>-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                    <!--                    <li class="flex flex-column md:flex-row md:align-items-center md:justify-content-between mb-4">-->
                    <!--                        <div>-->
                    <!--                            <span class="text-900 font-medium mr-2 mb-1 md:mb-0">Portal Sticker</span>-->
                    <!--                            <div class="mt-1 text-600">Accessories</div>-->
                    <!--                        </div>-->
                    <!--                        <div class="mt-2 md:mt-0 ml-0 md:ml-8 flex align-items-center">-->
                    <!--                            <div class="surface-300 border-round overflow-hidden w-10rem lg:w-6rem" style="height:8px">-->
                    <!--                                <div class="bg-cyan-500 h-full" style="width:16%"></div>-->
                    <!--                            </div>-->
                    <!--                            <span class="text-cyan-500 ml-3 font-medium">%16</span>-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                    <!--                    <li class="flex flex-column md:flex-row md:align-items-center md:justify-content-between mb-4">-->
                    <!--                        <div>-->
                    <!--                            <span class="text-900 font-medium mr-2 mb-1 md:mb-0">Supernova Sticker</span>-->
                    <!--                            <div class="mt-1 text-600">Accessories</div>-->
                    <!--                        </div>-->
                    <!--                        <div class="mt-2 md:mt-0 ml-0 md:ml-8 flex align-items-center">-->
                    <!--                            <div class="surface-300 border-round overflow-hidden w-10rem lg:w-6rem" style="height:8px">-->
                    <!--                                <div class="bg-pink-500 h-full" style="width:67%"></div>-->
                    <!--                            </div>-->
                    <!--                            <span class="text-pink-500 ml-3 font-medium">%67</span>-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                    <!--                    <li class="flex flex-column md:flex-row md:align-items-center md:justify-content-between mb-4">-->
                    <!--                        <div>-->
                    <!--                            <span class="text-900 font-medium mr-2 mb-1 md:mb-0">Wonders Notebook</span>-->
                    <!--                            <div class="mt-1 text-600">Office</div>-->
                    <!--                        </div>-->
                    <!--                        <div class="mt-2 md:mt-0 ml-0 md:ml-8 flex align-items-center">-->
                    <!--                            <div class="surface-300 border-round overflow-hidden w-10rem lg:w-6rem" style="height:8px">-->
                    <!--                                <div class="bg-green-500 h-full" style="width:35%"></div>-->
                    <!--                            </div>-->
                    <!--                            <span class="text-green-500 ml-3 font-medium">%35</span>-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                    <!--                    <li class="flex flex-column md:flex-row md:align-items-center md:justify-content-between mb-4">-->
                    <!--                        <div>-->
                    <!--                            <span class="text-900 font-medium mr-2 mb-1 md:mb-0">Mat Black Case</span>-->
                    <!--                            <div class="mt-1 text-600">Accessories</div>-->
                    <!--                        </div>-->
                    <!--                        <div class="mt-2 md:mt-0 ml-0 md:ml-8 flex align-items-center">-->
                    <!--                            <div class="surface-300 border-round overflow-hidden w-10rem lg:w-6rem" style="height:8px">-->
                    <!--                                <div class="bg-purple-500 h-full" style="width:75%"></div>-->
                    <!--                            </div>-->
                    <!--                            <span class="text-purple-500 ml-3 font-medium">%75</span>-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                    <!--                    <li class="flex flex-column md:flex-row md:align-items-center md:justify-content-between mb-4">-->
                    <!--                        <div>-->
                    <!--                            <span class="text-900 font-medium mr-2 mb-1 md:mb-0">Robots T-Shirt</span>-->
                    <!--                            <div class="mt-1 text-600">Clothing</div>-->
                    <!--                        </div>-->
                    <!--                        <div class="mt-2 md:mt-0 ml-0 md:ml-8 flex align-items-center">-->
                    <!--                            <div class="surface-300 border-round overflow-hidden w-10rem lg:w-6rem" style="height:8px">-->
                    <!--                                <div class="bg-teal-500 h-full" style="width:40%"></div>-->
                    <!--                            </div>-->
                    <!--                            <span class="text-teal-500 ml-3 font-medium">%40</span>-->
                    <!--                        </div>-->
                    <!--                    </li>-->
                </ul>
            </div>


<!--            BEST SELLING PRODUCTS ENDS-->


<!--            NOTIFICATION CARDS STARTS-->


<!--            <div class="card">-->
<!--                <div class="flex align-items-center justify-content-between mb-4">-->
<!--                    <h5>Notifications</h5>-->
<!--                    <div>-->
<!--                        <Button icon="pi pi-ellipsis-v" class="p-button-text p-button-plain p-button-rounded"-->
<!--                                @click="$refs.menu1.toggle($event)"></Button>-->
<!--                        <Menu ref="menu1" :popup="true" :model="items"></Menu>-->
<!--                    </div>-->
<!--                </div>-->

<!--                <span class="block text-600 font-medium mb-3">TODAY</span>-->
<!--                <ul class="p-0 mx-0 mt-0 mb-4 list-none">-->
<!--                    <li class="flex align-items-center py-2 border-bottom-1 surface-border">-->
<!--                        <div-->
<!--                            class="w-3rem h-3rem flex align-items-center justify-content-center bg-blue-100 border-circle mr-3 flex-shrink-0">-->
<!--                            <i class="pi pi-dollar text-xl text-blue-500"></i>-->
<!--                        </div>-->
<!--                        <span class="text-900 line-height-3">Richard Jones-->
<!--						<span class="text-700">has purchased a blue t-shirt for <span-->
<!--                            class="text-blue-500">79$</span></span>-->
<!--					</span>-->
<!--                    </li>-->
<!--                    <li class="flex align-items-center py-2">-->
<!--                        <div-->
<!--                            class="w-3rem h-3rem flex align-items-center justify-content-center bg-orange-100 border-circle mr-3 flex-shrink-0">-->
<!--                            <i class="pi pi-download text-xl text-orange-500"></i>-->
<!--                        </div>-->
<!--                        <span class="text-700 line-height-3">Your request for withdrawal of <span-->
<!--                            class="text-blue-500 font-medium">2500$</span> has been initiated.</span>-->
<!--                    </li>-->
<!--                </ul>-->

<!--                <span class="block text-600 font-medium mb-3">YESTERDAY</span>-->
<!--                <ul class="p-0 m-0 list-none">-->
<!--                    <li class="flex align-items-center py-2 border-bottom-1 surface-border">-->
<!--                        <div-->
<!--                            class="w-3rem h-3rem flex align-items-center justify-content-center bg-blue-100 border-circle mr-3 flex-shrink-0">-->
<!--                            <i class="pi pi-dollar text-xl text-blue-500"></i>-->
<!--                        </div>-->
<!--                        <span class="text-900 line-height-3">Keyser Wick-->
<!--						<span class="text-700">has purchased a black jacket for <span-->
<!--                            class="text-blue-500">59$</span></span>-->
<!--					</span>-->
<!--                    </li>-->
<!--                    <li class="flex align-items-center py-2 border-bottom-1 surface-border">-->
<!--                        <div-->
<!--                            class="w-3rem h-3rem flex align-items-center justify-content-center bg-pink-100 border-circle mr-3 flex-shrink-0">-->
<!--                            <i class="pi pi-question text-xl text-pink-500"></i>-->
<!--                        </div>-->
<!--                        <span class="text-900 line-height-3">Jane Davis-->
<!--						<span class="text-700">has posted a new questions about your product.</span>-->
<!--					</span>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </div>-->

<!--            NOTIFICATION CARD ENDS-->

<!--            <div-->
<!--                class="px-4 py-5 shadow-2 flex flex-column md:flex-row md:align-items-center justify-content-between mb-3"-->
<!--                style="border-radius: 1rem; background: linear-gradient(0deg, rgba(0, 123, 255, 0.5), rgba(0, 123, 255, 0.5)), linear-gradient(92.54deg, #1C80CF 47.88%, #FFFFFF 100.01%)">-->
<!--                <div>-->
<!--                    <div class="text-blue-100 font-medium text-xl mt-2 mb-3">TAKE THE NEXT STEP</div>-->
<!--                    <div class="text-white font-medium text-5xl">Try PrimeBlocks</div>-->
<!--                </div>-->
<!--                <div class="mt-4 mr-auto md:mt-0 md:mr-0">-->
<!--                    <a href="https://www.primefaces.org/primeblocks-vue"-->
<!--                       class="p-button font-bold px-5 py-3 p-button-warning p-button-rounded p-button-raised">-->
<!--                        Get Started-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</template>

<script>

import * as PusherPushNotifications from "@pusher/push-notifications-web";


import ProductService from '../service/ProductService';
import DashboardService from '../service/DashboardService';

import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';

//for tags
import CustomerService from '../service/CustomerService'
import {FilterMatchMode,FilterOperator} from 'primevue/api';


export default {

    data() {
        return {

            //related to tag filter
            statuses: [],
            filters2: {

                'tag': {value: null, matchMode: FilterMatchMode.EQUALS},
            },
            loading2: true,
            //tag filter end

            //country based stats:
            country_totalorder_with_revenue:[],

            //Tag based stats:
            orders_based_on_tags: [],

            selectedDate: "all",
            convertedSelectedDate: '1998-08-03 11:12:40',  // send backend to data beginning with this default date
            dateOptions: [
                {"label": "Son 30 gün", "code": 'last_30'},
                {"label": "Son 90 gün", "code": 'last_90'},
                {"label": "Son 1 yıl", "code": 'last_365'},
                {"label": "Tüm zamanlar", "code": 'all'},
            ],
            lastOrders: null,
            lineData: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Agust', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Revenue',
                        data: [65, 59, 80, 81, 56, 55, 40, 50, 40,60, 70, 30],
                        fill: false,
                        backgroundColor: '#2f4860',
                        borderColor: '#2f4860',
                        tension: 0.4
                    },
                    {
                        label: 'Sales',
                        data: [28, 48, 40, 19, 86, 27, 90, 50, 20, 60, 30, 70],
                        fill: false,
                        backgroundColor: '#00bb7e',
                        borderColor: '#00bb7e',
                        tension: 0.4
                    }
                ]
            },
            items: [
                {label: 'Add New', icon: 'pi pi-fw pi-plus'},
                {label: 'Remove', icon: 'pi pi-fw pi-minus'}
            ],
            stores: [],
            selectedStores: [],
            storeStats: {},

            //from now on related to chart
            year_months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            basicData: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Agust', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Revenue',
                        backgroundColor: '#42A5F5',
                        data: this.revenues,//[65, 59, 80, 81, 56, 55, 40, 50, 40,60, 70, 30]
                    },
                    {
                        label: 'Total',
                        backgroundColor: '#FFA726',
                        data: this.total_monthly_sell //[28, 48, 40, 19, 86, 27, 90, 50, 40,60, 70, 30]
                    }
                ]
            },
            basicOptions: {
                plugins: {
                    legend: {
                        labels: {
                            color: '#495057'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#495057'
                        },
                        grid: {
                            color: '#ebedef'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#495057'
                        },
                        grid: {
                            color: '#ebedef'
                        }
                    }
                }
            },


        }
    },
    productService: null,
    created() {
        this.productService = new ProductService();
        this.dashboardService = new DashboardService();
        this.customerService = new CustomerService();



    },
    mounted() {

        this.dashboardService.get_stores().then(data => {

            data.stores.forEach(function (item){
                this.selectedStores.push(item.id)
            }.bind(this))
            this.stores=data.stores;

            this.loadData();
            this.loadStatuses(); // loading all tags.

        })


    },
    methods: {


        loadStatuses() {    //load tags from database
            this.customerService.loadStatuses().then( data => {
                this.loading2 = false;

                data.forEach( key => {
                    // this.statuses[key.id] = key.name
                    this.statuses.push(key.name)

                } )

            });
        },

        // onFilter(event) {
        //     // console.log(this.filterModel.value)
        //     this.filters2.tag.value = Object.values(this.statuses).find(value => value === this.tempTag) // filters order table with selected Tag
        //     console.log('filters')
        //     console.log(this.tempTag);
        //     console.log(event.filters)
        //     console.log(this.filters1)
        //     console.log(this.filters2)
        //     console.log(this.filters2.tag.value)
        //     console.log(this.statuses)
        //
        //
        // },


        getP() {
            axios.get(route("test")).then(data => {
                console.log(data.data)
            }).catch(error => {
                console.log(error)
            })
        },
        formatCurrency(value) {
            return value.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
        },

        loadData() {

            // this.dashboardService.getDashboardInfo(this.selectedStores,this.convertedSelectedDate).then(data => {
            //
            //     console.log('data');
            //     console.log(data);
            //
            //     this.storeStats = data
            //
            //     console.log(data.stores)
            //     this.lastOrders = data.last_orders;
            //     console.log(data.last_orders)
            // });

            this.dashboardService.orders_count(this.selectedStores,this.convertedSelectedDate).then(data => {
                this.storeStats.orders_count = data.orders_count;
            });

            this.dashboardService.orders_not_shipped_count(this.selectedStores,this.convertedSelectedDate).then(data => {
                this.storeStats.orders_not_shipped_count = data.orders_not_shipped_count;
            });

            this.dashboardService.orders_not_shipped_amount(this.selectedStores,this.convertedSelectedDate).then(data => {
                this.storeStats.orders_not_shipped_amount = data.orders_not_shipped_amount;

            });

            this.dashboardService.orders_total_amount(this.selectedStores,this.convertedSelectedDate).then(data => {
                this.storeStats.orders_total_amount = data.orders_total_amount;

            });

            this.dashboardService.orders_waiting_buyer_accept_count(this.selectedStores,this.convertedSelectedDate).then(data => {
                this.storeStats.orders_waiting_buyer_accept_count = data.orders_waiting_buyer_accept_count;
            });

            this.dashboardService.orders_waiting_buyer_accept_amount(this.selectedStores,this.convertedSelectedDate).then(data => {
                this.storeStats.orders_waiting_buyer_accept_amount = data.orders_waiting_buyer_accept_amount;

            });

            this.dashboardService.customers_count(this.selectedStores,this.convertedSelectedDate).then(data => {
                this.storeStats.customers_count = data.customers_count;

            });

            this.dashboardService.last_orders(this.selectedStores,this.convertedSelectedDate).then(data => {

                this.loading2 = false;

                data.forEach(order => {

                    // if(order.tag === null ) {
                    //     order.tag =  '';//{id: null, user_id: null, name: ''}
                    // } else {
                    //     order.tag = order.tag.name;
                    // }
                    if(order.tag !== null ) {
                        order.tag = order.tag.name; // do, otherwise it is throwing error
                    }
                })

                this.lastOrders = data;

            });

            this.dashboardService.country_totalorder_with_revenue(this.selectedStores,this.convertedSelectedDate).then(data => {

                // if selected store has no data, resets the data table
                if(Object.keys(data.country_totalorder_with_revenue).length === 0) {
                    this.country_totalorder_with_revenue =[]
                }

                //map data
                Object.entries(data.country_totalorder_with_revenue).forEach(($item) => {
                    this.country_totalorder_with_revenue.push({
                        'country':$item[0],
                        'total': $item[1].total,
                        'revenue': $item[1].revenue,
                        'total_perc': $item[1].total_perc,
                        'revenue_perc': $item[1].revenue_perc
                    })

                });

            });

            this.dashboardService.orders_based_on_tags(this.selectedStores,this.convertedSelectedDate).then(data => {

                // if selected store has no data, resets the related data table
                if(Object.keys(data.orders_based_on_tags).length === 0) {
                    this.orders_based_on_tags =[]
                }

                //map data
                Object.entries(data.orders_based_on_tags).forEach(($item) => {

                    if($item[0] !== '') { // first one comes  without tag. So pass it

                        this.orders_based_on_tags.push({
                            'tag': $item[1].tag,
                            'total': $item[1].total,
                            'revenue': $item[1].revenue,
                            'total_perc': $item[1].total_perc,
                            'revenue_perc': $item[1].revenue_perc
                        })

                    }


                });


            });

            this.dashboardService.top_ten(this.selectedStores,this.convertedSelectedDate).then(data => {

                this.storeStats.top_ten = data.top_ten;

            });
            this.dashboardService.monthly_sell(this.selectedStores).then(data => {



                data.monthly_sell.forEach(($item) => {

                    // calculate the percentage in relation to general
                    this.basicData.datasets[0].data[this.year_months.indexOf($item.months)] = Math.floor(((100 * $item.sums) / this.storeStats.orders_total_amount) ) ;
                    this.basicData.datasets[1].data[this.year_months.indexOf($item.months)] = Math.floor((( 100 * $item.total) / this.storeStats.orders_count));
                } );


            });


            this.dashboardService.number_of_products_sold(this.selectedStores,this.convertedSelectedDate).then(data => {
                this.storeStats.number_of_products_sold = data.number_of_products_sold;

            });


        },

        storeSelected(event) {


            // if no store is selected reset all values.
            if(this.selectedStores == '') {

                this.storeStats={}
                this.lastOrders=null
                this.basicData.datasets[0].data=[]
                this.basicData.datasets[1].data=[]
                this.orders_based_on_tags =[]
                this.country_totalorder_with_revenue = []


            } else {

                this.loadData(); // load data of the selected store..

            }

        },


        timeIntervalSelected() {

            let today = new Date();

            if(this.selectedDate == 'last_30') {
                today.setMonth(today.getMonth() - 1);
                this.convertedSelectedDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+ today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            }
            else if ( this.selectedDate == 'last_90') {
                today.setMonth(today.getMonth() - 3);
                this.convertedSelectedDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+ today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            }
            else if ( this.selectedDate == 'last_365') {
                today.setMonth(today.getMonth() - 12);
                this.convertedSelectedDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+ today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            }
            else if ( this.selectedDate == 'all') {
                today.setFullYear(today.getFullYear() - 20);
                this.convertedSelectedDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+ today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            }

            this.loadData();


        }
    },
    components: {
        Dropdown, MultiSelect
    }
}
</script>


<style lang="scss" scoped>

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

::v-deep(.p-paginator) {
.p-paginator-current {
    margin-left: auto;
}
}

::v-deep(.p-progressbar) {
    height: .5rem;
    background-color: #D8DADC;

.p-progressbar-value {
    background-color: #607D8B;
}
}

::v-deep(.p-datepicker) {
    min-width: 25rem;

td {
    font-weight: 400;
}
}

::v-deep(.p-datatable.p-datatable-customers) {
    .p-datatable-header {
        padding: 1rem;
        text-align: left;
        font-size: 1.5rem;
    }

    .p-paginator {
        padding: 1rem;
    }

    .p-datatable-thead > tr > th {
        text-align: left;
    }

    .p-datatable-tbody > tr > td {
        cursor: auto;
    }

    .p-dropdown-label:not(.p-placeholder) {
        text-transform: uppercase;
    }
}
</style>
