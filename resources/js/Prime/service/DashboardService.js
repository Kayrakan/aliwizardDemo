import axios from 'axios';

export default class DashboardService {

    getDashboardInfo(store, time_interval ) {

        let url = new URL(route(`dashboard`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();

        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    orders_count(store, time_interval ) {

        let url = new URL(route(`orders_count`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();

        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    orders_not_shipped_count(store, time_interval ) {

        let url = new URL(route(`orders_not_shipped_count`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    orders_not_shipped_amount(store, time_interval ) {

        let url = new URL(route(`orders_not_shipped_amount`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    orders_total_amount(store, time_interval ) {

        let url = new URL(route(`orders_total_amount`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    orders_waiting_buyer_accept_count(store, time_interval ) {

        let url = new URL(route(`orders_waiting_buyer_accept_count`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    orders_waiting_buyer_accept_amount(store, time_interval ) {

        let url = new URL(route(`orders_waiting_buyer_accept_amount`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    customers_count(store, time_interval ) {

        let url = new URL(route(`customers_count`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    last_orders(store, time_interval ) {

        let url = new URL(route(`last_orders`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }


    get_stores() {

        return axios.get(route(`get_stores`)).then( res => res.data).
        catch((err) => err.response);
    }


    country_totalorder_with_revenue(store, time_interval ) {

        let url = new URL(route(`country_totalorder_with_revenue`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    orders_based_on_tags(store, time_interval ) {

        let url = new URL(route(`orders_based_on_tags`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    top_ten(store, time_interval ) {

        let url = new URL(route(`top_ten`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }

    monthly_sell(store) {

        let url = new URL(route(`monthly_sell`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }


    number_of_products_sold(store, time_interval ) {

        let url = new URL(route(`number_of_products_sold`));
        let search_params = url.searchParams;

        search_params.set('store', store);
        search_params.set('time_interval', time_interval)
        url.search = search_params.toString();


        return axios.get(url.toString()).then( res => res.data).
        catch((err) => err.response);
    }




}
