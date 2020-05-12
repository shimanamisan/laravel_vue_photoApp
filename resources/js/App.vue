<template>
  <div>
    <header>
      <Header />
    </header>
    <main>
      <div class="container">
        <!-- <RouterView/> -->
        <RouterView />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Header from "./components/Header";
import Footer from "./components/Footer";

export default {
  components: {
    Header,
    Footer
  }
};
</script>

<style>
</style>