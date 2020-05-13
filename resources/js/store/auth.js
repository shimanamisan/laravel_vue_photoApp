const state = {
    user: null
};

const getters = {
    check: state => !!state.user,
    username: state => (state.user ? state.user.name : "") // userがnullの場合でもエラーにならないよう空文字を返す
};

const mutations = {
    setUser(state, user) {
        state.user = user;
    }
};

const actions = {
    async register({ commit }, data) {
        const response = await axios.post("/api/register", data);
        commit("setUser", response.data);
    },
    async login({ commit }, data) {
        const response = await axios.post("/api/login", data);
        commit("setUser", response.data);
    },
    async logout({ commit }, data) {
        const response = await axios.post("/api/logout");
        commit("setUser", null);
    },
    async currentUser({ commit }) {
        const response = await axios.get("/api/user");
        const user = response.data || null;
        commit("setUser", user);
    }
};

export default {
    namespaced: true, // 名前空間を有効化（dispatch('ストア名/アクション名')で呼び出せるようになる）
    state,
    getters,
    mutations,
    actions
};
