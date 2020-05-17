<template>
  <div>
    <header>
      <Header />
    </header>
    <main>
      <div class="container">
        <Message />
        <!-- <RouterView/> -->
        <RouterView />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Message from "./components/Message";
import Header from "./components/Header";
import Footer from "./components/Footer";
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from "./util";

export default {
  components: {
    Message,
    Header,
    Footer
  },
  computed: {
    errorCode() {
      return this.$store.state.error.code;
    }
  },
  watch: {
    // errorストアのエラーコードを常に監視している
    // 500エラーがストアに代入されたらSystemエラーページへリダイレクトされる
    errorCode: {
      async handler(val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
        } else if (val === UNAUTHORIZED) {
          // トークンをリフレッシュ
          await axios.get("/api/refresh-token");
          // ストアのuserをクリア
          this.$store.commit("auth/setUser", null);
          // ログイン画面へ
          this.$router.push("/login");
        } else if (val === NOT_FOUND) {
          this.$router.push("/not-found");
        }
      },
      // コンポーネントが生成されたタイミングでも実行される
      immediate: true
    },
    $route() {
      this.$store.commit("error/setCode", null);
    }
  }
};
</script>

<style>
</style>