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
import { INTERNAL_SERVER_ERROR } from "./util";

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
    errorCode: {
      handler(val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500").catch(error => {});
        }
      },
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