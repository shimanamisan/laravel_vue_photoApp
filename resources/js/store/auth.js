// ステータスコード
import { OK, CREATED, UNPROCESSABLE_ENTITY } from "../util";
import { compileToFunctions } from "vue-template-compiler";

const state = {
    user: null,
    apiStatus: null,
    loginErrorMessage: null,
    registerErrorMessage: null
};

const getters = {
    check: state => !!state.user,
    username: state => (state.user ? state.user.name : "") // userがnullの場合でもエラーにならないよう空文字を返す
};

const mutations = {
    setUser(state, user) {
        state.user = user;
    },
    setApiStatus(state, status) {
        state.apiStatus = status;
    },
    setLoginErrorMessage(state, message) {
        state.loginErrorMessage = message;
    },
    setRegisterErrorMessage(state, message) {
        state.registerErrorMessage = message;
    }
};

const actions = {
    // 会員登録処理
    async register({ commit }, data) {
        // apiStatusを初期化
        commit("setApiStatus", null);
        // 非同期通信を行いDBへ登録処理
        const response = await axios.post("/api/register", data);

        // 登録成功時の処理
        if (response.status === CREATED) {
            commit("setApiStatus", true);
            commit("setUser", response.data);
            return false;
        }
        // ステータスコード201以外の時の処理
        commit("setApiStatus", false);

        // バリデーションエラーに引っかかった時の処理
        if (response.status === UNPROCESSABLE_ENTITY) {
            // レスポンスのエラーメッセージを格納
            commit("setRegisterErrorMessage", response.data.errors);
        } else {
            commit("error/setCode", response.status, { root: true });
        }
    },

    // ログイン処理
    async login({ commit }, data) {
        // apiStatusを初期化
        commit("setApiStatus", null);
        // 非同期でログイン処理を行う
        const response = await axios.post("/api/login", data);

        // 通信が成功ならture
        if (response.status === OK) {
            commit("setApiStatus", true);
            commit("setUser", response.data);
            return false;
        }
        // ステータスコード200以外の時の処理
        commit("setApiStatus", false);

        // バリデーションエラーに引っかかった時の処理
        if (response.status === UNPROCESSABLE_ENTITY) {
            // レスポンスのエラーメッセージを格納
            commit("setLoginErrorMessage", response.data.errors);
        } else {
            commit("error/setCode", response.status, { root: true });
        }
    },

    // ログアウト処理
    async logout({ commit }, data) {
        // apiStatusを初期化
        commit("setApiStatus", null);
        const response = await axios.post("/api/logout");
        commit("setUser", null);

        // 通信が成功した時の処理
        if (response.status === OK) {
            commit("setApiStatus", true);
            commit("setUser", null);
            return;
        }

        // ステータスコード200以外の時の処理
        commit("setApiStatus", false);
        commit("error/setCode", response.status, { root: true });
    },

    // ログインユーザーチェック
    async currentUser({ commit }) {
        // apiStatusを初期化
        commit("setApiStatus", null);
        const response = await axios.get("/api/user");

        // 通信が成功した時の処理
        if (response.status === OK) {
            commit("setApiStatus", true);
            commit("setUser", null);
            return false;
        }
        // const user = response.data || null;
        // commit("setUser", user);

        commit("setApiStatus", false);
        commit("error/setCode", response.status, { root: true });
    }
};

export default {
    namespaced: true, // 名前空間を有効化（dispatch('ストア名/アクション名')で呼び出せるようになる）
    state,
    getters,
    mutations,
    actions
};
