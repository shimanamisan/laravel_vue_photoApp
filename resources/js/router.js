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
        component: PhotoList,
        // PhotoListコンポーネントのクエリパラメータにpageという値が、propsとして渡される
        props: function(route) {
            // propsに関数を指定する場合はその戻り値がpropsとしてページコンポーネントに渡される
            // そしてその関数の引数はルート情報を表すroute
            const page = route.query.page;

            return {
                // routeからクエリパラメータpageを取り出し、正規表現を使って整数と解釈されない値は1とみなして返却している
                page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
            };
        }
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
        // propsは写真のIDをpropsとして受け取ると言う意味
        // :idの値がこのコンポーネントのpropsとして渡される
        props: true
    },
    {
        path: "/500",
        component: SystemError
    }
];

// インスタンスを生成
const router = new VueRouter({
    mode: "history",
    routes,
    scrollBehavior() {
        return {
            x: 0,
            y: 0
        };
    }
});

// app.jsで読み込ませるためにインスタンスをエクスポート
export default router;
