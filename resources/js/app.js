require("./bootstrap");

window.Vue = require("vue").default;

import "./helper";

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.prototype.$authUser = JSON.parse(
    document.querySelector("meta[name='auth-user']").getAttribute("content")
);

Vue.component("app", require("./App.vue").default);

import store from "./store/index";
import router from "./router/index";
import "./axios";

const app = new Vue({
    store,
    router,
    el: "#app"
});
