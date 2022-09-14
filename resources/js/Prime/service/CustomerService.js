import axios from 'axios'
import {filter} from "lodash/collection";

export default class CustomerService {

    getCustomers(params) {
        console.log('params')
        console.log(JSON.stringify(params))
        const queryParams = Object.keys(params['filters']).map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params['filters'][k])).join('&');
        console.log('query params')
        console.log(queryParams)
        let page = ((params.first / params.rows) || 0) + 1

        var url = new URL(route("orders.index") );

        var search_params = url.searchParams;

        search_params.set('page', page);
        search_params.set('page_size', params.rows)

        Object.keys(params.filters).forEach(key => {
            if(params.filters[key].value != '' && params.filters[key].value != null) {
                console.log('adding')
                search_params.set(`filter[${key}]`, params.filters[key].value)
            }
        });
        if (params.sortField != null) {
            search_params.set('sort', params.sortField)
        }
        url.search = search_params.toString();
        var new_url = url.toString();
        console.log(new_url);

        return axios.get(new_url).then(res => res.data)

    }

    loadStatuses() {
        return axios.get(route('order_tags.index')).then(res =>  res.data )

    }

    loadStores() {
        return axios.get(route('user_stores')).then(res => res.data).catch(err => {
            console.log(err.response);

        });

    }

    saveTag(currentOrder) {
        console.log(currentOrder)
        // currentOrder.tag = currentOrder.tag.id

        return axios.post(route('updateTag',{
            order: currentOrder.id
        }), {
            tag: currentOrder.tag.id,
        }).then(res => res.data )

    }

}

