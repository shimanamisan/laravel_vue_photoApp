require("./bootstrap");

import Vue from "vue";
// VueRouterインスタンスをインポート
import router from "./router";
// ストアをインポート
import store from "./store";

// ルートコンポーネント
import App from "./App.vue";

new Vue({
    router,
    store,
    // renderではコンポーネントのオブジェクトを読み込んで描画することができる
    render: h => h(App),
    // Vueインスタンスが生成される前に、認証状態をVuexへ格納
    beforeCreate() {
        console.log("beforeCreate!");
        store.dispatch("auth/currentUser");
    }
}).$mount("#app");

// const createApp = async function() {
//     await store.dispatch("auth/currentUser");

//     new Vue({
//         router,
//         store,
//         // renderではコンポーネントのオブジェクトを読み込んで描画することができる
//         render: h => h(App)
//     }).$mount("#app");
// };

// createApp();
