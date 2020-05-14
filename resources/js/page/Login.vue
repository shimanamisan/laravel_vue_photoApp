<template>
  <div class="container--small">
    <ul class="tab">
      <li class="tab__item" :class="{'tab__item--active': tab === 1}" @click="tab = 1">Login</li>
      <li class="tab__item" :class="{'tab__item--active': tab === 2}" @click="tab = 2">Register</li>
    </ul>
    <!-- LoginForm -->
    <div class="panel" v-show="tab === 1">
      <form class="form" @submit.prevent="login">
        <div class="errors" v-if="loginError">
          <ul v-if="loginError.email">
            <li v-for="msg in loginError.email" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="loginError.password">
            <li v-for="msg in loginError.password" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <label for="login-email">Email</label>
        <input type="text" class="form__item" id="login-email" v-model="loginForm.email" />
        <label for="login-password">Password</label>
        <input type="password" class="form__item" id="login-password" v-model="loginForm.password" />
        <div class="form__button">
          <button type="submit" class="button button--inverse">login</button>
        </div>
      </form>
    </div>
    <!-- End LoginForm -->

    <!-- RegisterForm -->
    <div class="panel" v-show="tab === 2">
      <form class="form" @submit.prevent="register">
        <div class="errors" v-if="registerError">
          <ul v-if="registerError.name">
            <li v-for="msg in registerError.name" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="registerError.email">
            <li v-for="msg in registerError.email" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="registerError.password">
            <li v-for="msg in registerError.password" :key="msg">{{ msg }}</li>
          </ul>
        </div>
        <label for="username">Name</label>
        <input type="text" class="form__item" id="username" v-model="registerForm.name" />
        <label for="email">Email</label>
        <input type="text" class="form__item" id="email" v-model="registerForm.email" />
        <label for="password">Password</label>
        <input type="password" class="form__item" id="password" v-model="registerForm.password" />
        <label for="password-confirmation">Password (confirm)</label>
        <input
          type="password"
          class="form__item"
          id="password-confirmation"
          v-model="registerForm.password_confirmation"
        />
        <div class="form__button">
          <button type="submit" class="button button--inverse">register</button>
        </div>
      </form>
    </div>
    <!-- End RegisterForm -->
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      tab: 1,
      loginForm: {
        email: "",
        password: ""
      },
      registerForm: {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
      }
    };
  },
  computed: {
    // apiStatus() {
    //   return this.$store.state.auth.apiStatus;
    // }
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
      loginError: state => state.auth.loginErrorMessage,
      registerError: state => state.auth.registerErrorMessage
    })
  },
  methods: {
    async register() {
      // authストアのアクションを呼び出す
      // Vue.use(Vuex)という記述をしているので、this.$storeでアクセスできる
      await this.$store.dispatch("auth/register", this.registerForm);
      if (this.apiStatus) {
        // トップページへ遷移
        this.$router.push("/");
      }
    },
    async login() {
      await this.$store.dispatch("auth/login", this.loginForm);
      if (this.apiStatus) {
        // トップページへ遷移
        this.$router.push("/");
      }
    },
    clearError() {
      this.$store.commit("auth/setLoginErrorMessage", null);
      this.$store.commit("auth/setRegisterErrorMessage", null);
    }
  },
  created() {
    this.clearError();
  }
};
</script>

<style>
</style>