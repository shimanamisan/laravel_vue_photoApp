require("./bootstrap");

import Vue from "vue";
// VueRouterインスタンスをインポート
import router from "./router";
// ルートコンポーネント
import App from "./App.vue";

// new Vue({
//     el: "#app",
//     template: "<h1>Hello Vue!</h1>"
// });

new Vue({
    el: "#app",
    router,

    // renderではコンポーネントのオブジェクトを読み込んで描画することができる
    render: h => h(App)
}).$mount("#app");
