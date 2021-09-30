import axios from "axios";

axios.defaults.baseURL = "http://127.0.0.1:8000";
axios.defaults.withCredentials = false;

window.axios = axios;
