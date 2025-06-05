import { createApp } from 'vue'
import store from '@/js/stores';
import router from '@/js/router';
import App from '@/js/layouts/App';
import '@/js/plugins'
import 'vue-toast-notification/dist/theme-sugar.css';
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import { IconsPlugin,SkeletonPlugin,FormCheckboxPlugin, VBTooltipPlugin,TablePlugin, SpinnerPlugin, PaginationPlugin, FormTimepickerPlugin, FormDatepickerPlugin, DropdownPlugin, CollapsePlugin, NavbarPlugin, CardPlugin, BFormCheckbox, TooltipPlugin, FormTagsPlugin } from 'bootstrap-vue'
import excel from 'vue-excel-export';
import "bootstrap";
import vue3GoogleLogin from 'vue3-google-login'
import VueSortable from "vue3-sortablejs";
import Echo from "laravel-echo"

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.withCredentials = true;

window.Pusher = require('pusher-js');
window.Echo = new Echo({
     broadcaster: 'pusher',
     key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});
const $toast = useToast();
const app = createApp({
  methods:{
      toast(message,type) {
          $toast.open({
              message:message,
              type:type,
              position:'top-right'
          })
      }
    }
  })
    .use(VueSortable)
    .use(excel)
    .use(IconsPlugin)
    .use(VBTooltipPlugin)
    .use(CardPlugin)
    .use(TablePlugin)
    .use(SpinnerPlugin)
    .use(PaginationPlugin)
    .use(FormTimepickerPlugin)
    .use(FormDatepickerPlugin)
    .use(DropdownPlugin)
    .use(CollapsePlugin)
    .use(NavbarPlugin)
    .use(SkeletonPlugin)
    .use(FormTagsPlugin)
    .use(FormCheckboxPlugin)
    .use(store)
    .use(router)
    .use(vue3GoogleLogin,{
        clientId: process.env.MIX_GOOGLE_CLIENT_ID
    });

app.config.globalProperties.$axios = axios;

app.directive('select', {
    twoWay: true,
    bind: function (el, binding, vnode) {
            $(el).select2().on("select2:select", (e) => {
            el.dispatchEvent(new Event('change', { target: e.target }));
        });
    },
});

app.mount('#app');
