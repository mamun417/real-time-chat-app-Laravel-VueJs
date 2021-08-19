require("./bootstrap");

window.Vue = require("vue").default;

import "./helper";

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.prototype.$authUser = JSON.parse(
    document.querySelector("meta[name='auth-user']").getAttribute("content")
);

Vue.component("main-app", require("./components/MainComponent.vue").default);

import store from "./store/index";

const app = new Vue({
    store,
    el: "#app"
});
