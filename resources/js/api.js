import axios from "axios";

let BaseApi = axios.create({
    baseURL: `${window.location.origin}/api`
});

let Api = () => {
    let token = localStorage.getItem('token');
    BaseApi.defaults.headers.common["Authorization"] = token ? `Bearer ${token}` : '';
    return BaseApi;
}

export default Api;