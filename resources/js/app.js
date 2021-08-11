require("./bootstrap");

window.Vue = require("vue").default;

import lodash from "lodash";
Vue.prototype.$_ = lodash;

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component("chat", require("./components/ChatComponent.vue").default);
Vue.component("main-app", require("./components/MainComponent.vue").default);

import VueChatScroll from "vue-chat-scroll";
Vue.use(VueChatScroll);

import store from "./store/index";

const app = new Vue({
    store,
    el: "#app"
});
