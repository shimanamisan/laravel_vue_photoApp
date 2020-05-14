<template>
  <footer class="footer">
    <button class="button button--link" @click="logout" v-if="isLogin">Logout</button>
    <RouterLink class="button button--link" to="/login">Login / Register</RouterLink>
  </footer>
</template>


<script>
import { mapState, mapGetters } from "vuex";
export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      isLogin: "auth/check"
    })
  },
  methods: {
    async logout() {
      await this.$store.dispatch("auth/logout");

      // 通信成功時の処理
      if (this.apiStatus) {
        // ログインへ遷移
        this.$router.push("/login");
      }
    }
  },
  computed: {
    isLogin() {
      return this.$store.getters["auth/check"];
    }
  }
};
</script>

<style>
</style>