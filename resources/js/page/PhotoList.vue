<template>
  <div class="photo-list">
    <div class="grid">
      <Photo
        class="grid__item"
        v-for="photo in photos"
        :key="photo.id"
        :item="photo"
        @like="onLikeClick"
      />
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
    },
    onLikeClick({ id, liked }) {
      if (!this.$store.getters["auth/check"]) {
        alert("いいね機能を使うにはログインしてください。");
        return false;
      }

      if (liked) {
        this.unlike(id);
      } else {
        this.like(id);
      }
    },
    // いいねボタン押した時の処理
    async like(id) {
      const response = await axios.put(`/api/photos/${id}/like`);

      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      this.photos = this.photos.map(photo => {
        if (photo.id === response.data.photo_id) {
          photo.likes_count += 1;
          photo.liked_by_user = true;
        }
        return photo;
      });
    },
    // いいね解除ボタン
    async unlike(id) {
      const response = await axios.delete(`/api/photos/${id}/like`);

      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      this.photos = this.photos.map(photo => {
        if (photo.id === response.data.photo_id) {
          photo.likes_count -= 1;
          photo.liked_by_user = false;
        }
        return photo;
      });
    }
  },
  watch: {
    // $routeを監視している
    // ページが切り替わった時にfetchPhotosの処理が走る
    $route: {
      async handler() {
        // console.log("ページが切り替わっている");
        await this.fetchPhootos();
      },
      immediate: true
    }
  }
};
</script>

<style></style>
