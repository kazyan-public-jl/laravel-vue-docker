require("./csrf.js");

import VueRouter from 'vue-router';

import TopPage from '../components/articles/top.vue';
import ListPage from '../components/articles/lists.vue';

// vue-router 使う宣言
Vue.use(VueRouter);

// vue-routerのインスタンス化、オプションroutesでアクセスされるパスとその時に表示するComponentを指定
const router = new VueRouter({
  mode: 'history',
  routes: [
    // TOPページ
    { 
      path: '/', component: TopPage
    },
    // Taskリスト一覧ページ
    {
      path: '/lists', component: ListPage
    }
  ]
});

var lvdVue = new Vue({
  router,
  el: "#app",
});
