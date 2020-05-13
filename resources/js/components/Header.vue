<template>
  <nav class="navbar">
    <RouterLink class="navbar__brand" to="/">Laravel Vue PhotoApp</RouterLink>
    <div class="navbar__menu">
      <div class="navbar__item" v-if="isLogin">
        <button class="button">
          <i class="icon ion-md-add"></i>
          Submit a photo
        </button>
      </div>
      <span class="navbar__item" v-if="isLogin">{{ username }}</span>
      <div class="navbar__item">
        <RouterLink class="button button--link" to="/login">Login / Register</RouterLink>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  computed: {
    // isLogin() {
    //   return this.$store.getters["auth/check"];
    // },
    // username() {
    //   return this.$store.getters["auth/username"];
    // }

    // mapGettersを使ったまとめた書き方
    ...mapGetters({
      isLogin: "auth/check",
      username: "auth/username"
    })
  }
};
</script>

<style>
</style>