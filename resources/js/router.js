import Vue from "vue";
import VueRouter from "vue-router";

// ページコンポーネントをインポート（今後追加された時は追加していく）
import PhotoList from "./page/PhotoList";
import Login from "./page/Login";
import PhotoDetail from "./page/PhotoDetail";
import SystemError from "./page/error/System";

// ストアのユーザー情報を参照するために追加
import store from "./store";

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
        component: Login,
        beforEnter(from, to, next) {
            console.log(from);
            console.log(to);
            console.log(next);
            if (store.getters["auth/check"]) {
                next("/");
            } else {
                next();
            }
        }
    },
    {
        path: "/photos/:id", // :idは写真のIDが入る
        component: PhotoDetail,
        props: true // propsは写真のIDをpropsとして受け取ると言う意味
    },
    {
        path: "/500",
        component: SystemError
    }
];

// インスタンスを生成
const router = new VueRouter({
    mode: "history",
    routes
});

// app.jsで読み込ませるためにインスタンスをエクスポート
export default router;
