const state = {
    content: ""
};

const mutations = {
    // 第二引数はジョブジェクト形式で複数値を渡せる？
    setContent(state, { content, timeout }) {
        state.content = content;

        if (typeof timeout === "undefined") {
            timeout = 3000;
        }

        setTimeout(function() {
            state.content = "";
        }, timeout);
    }
};

export default {
    namespaced: true,
    state,
    mutations
};
