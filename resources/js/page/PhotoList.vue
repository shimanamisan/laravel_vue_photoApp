<template>
  <div class="photo-list">
    <div class="grid">
      <Photo class="grid__item" v-for="photo in photos" :key="photo.id" :item="photo" />
    </div>
    <Pagination :current-page="currentPage" :last-page="lastPage" />
  </div>
</template>

<script>
import { OK } from "../util";
import Photo from "../components/Photo";
import Pagination from "../components/Pagination";
export default {
  data() {
    return {
      photos: [],
      currentPage: 0,
      lastPage: 0
    };
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },
  components: {
    Photo,
    Pagination
  },
  methods: {
    // 表示する写真のデータを取得
    async fetchPhootos() {
      const response = await axios.get(`/api/photos/?page=${this.page}`);

      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      // console.log(JSON.stringify(response.data));

      this.photos = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
    }
  },
  watch: {
    // $routeを監視している
    // ページが切り替わった時にfetchPhotosの処理が走る
    $route: {
      async handler() {
        console.log("ページが切り替わっている");
        await this.fetchPhootos();
      },
      immediate: true
    }
  }
};
</script>

<style>
</style>