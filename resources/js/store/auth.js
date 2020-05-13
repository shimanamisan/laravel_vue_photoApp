const state = {
    user: null
};

const getters = {};

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
    }
};

export default {
    namespaced: true, // 名前空間を有効化（dispatch('ストア名/アクション名')で呼び出せるようになる）
    state,
    getters,
    mutations,
    actions
};
