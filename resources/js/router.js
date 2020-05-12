import Vue from "vue";
import VueRouter from "vue-router";

// ページコンポーネントをインポート（今後追加された時は追加していく）
import PhotoList from "./page/PhotoList";
import Login from "./page/Login";

// VueRouterプラグインを読み込む
// そうすることで、RouterViewコンポーネントが使用できる
Vue.use(VueRouter);

// コンポーネントとURLパスを紐付ける
const routes = [
    {
        path: "/",
        component: PhotoList
    },
    {
        path: "/login",
        component: Login
    }
];

// インスタンスを生成
const router = new VueRouter({
    mode: "history",
    routes
});

// app.jsで読み込ませるためにインスタンスをエクスポート
export default router;
