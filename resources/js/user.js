import Api from "./Api";

export default {
    auth(link) {
        return Api().get(link ?? '/user');
    },

    get(route) {
        return Api().get(route);
    },

    post(route, data) {
        if (data) return Api().post(route, data);
        else return Api().post(route);
    },

    put(route, data) {
        if (data) return Api().put(route, data);
        else return Api().put(route);
    },

    delete(route, data) {
        if (data) return Api().delete(route, data);
        else return Api().delete(route);
    },
}