import moment from "moment";
import lodash from "lodash";
import Vue from "vue";
import VueChatScroll from "vue-chat-scroll";

Vue.use(VueChatScroll);

Vue.prototype.$moment = moment;
Vue.prototype.$_ = lodash;

Vue.prototype.$dateFormat = function(date, format = "MMM DD, Y h:mm A") {
    return moment(date).format(format);
};
