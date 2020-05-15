<template>
  <div v-show="value" class="photo-form">
    <h2 class="titl">Submit a Photo</h2>

    <div class="panel" v-show="loading">
      <Loader>Sending your Photo...</Loader>
    </div>
    <form class="form" @submit.prevent="submit" v-show="!loading">
      <div class="errors" v-if="errors">
        <ul v-if="erroes.photo">
          <li v-for="msg in erroes.photo" :key="msg">{{ msg }}</li>
        </ul>
      </div>
      <input type="file" class="form__item" @change="onFileChange" />
      <output class="form__output" v-if="preview">
        <img :src="preview" alt="投稿画像" />
      </output>
      <div class="form__button">
        <button class="button button--inverse">submit</button>
      </div>
    </form>
  </div>
</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from "../util";
import Loader from "./Loader";
export default {
  components: {
    Loader
  },
  props: {
    value: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      preview: null,
      photo: null,
      errors: null
    };
  },
  methods: {
    // ファイルが選択時に実行される
    onFileChange(event) {
      // 未選択だったら中断
      if (event.target.files.length === 0) {
        this.reset();
        return false;
      }

      // ファイルが画像でなかったら処理を中断
      if (!event.target.files[0].type.match("image.*")) {
        this.reset();
        return false;
      }

      // FileReaderクラスをインスタンス化
      const reader = new FileReader();

      // こちらではなぜか、処理が走り値も取得できているがthis.previewに値が入らない
      // ファイルを読み込む
      //   reader.onload = function(event) {
      //     console.log("読み込まれているか確認");
      //     // previewに読み込み結果（データURL）を代入する
      //     // previewに値が入るとoutputタグにつけたv-ifがtrue判定となる
      //     // また、outputタグ内のimgタグのsrc属性はpreviewを参照しているので、結果として画像が表示される
      //     this.preview = event.target.result;
      //   };

      reader.onload = e => {
        this.preview = e.target.result;
      };

      // ファイルを読み込む
      // 上記onloadを参照より、読み込まれたファイルはデータURL形式で受け取れる
      reader.readAsDataURL(event.target.files[0]);
      this.photo = event.target.files[0];
    },
    // キャンセルしたときに入力値は消えるが、this.previe内にデータが残るのでそれを消去する処理
    reset() {
      this.preview = "";
      this.photo = null;
      // this.$elはコンポーネントそのもののDOM要素を表す
      this.$el.querySelector('input[type="file"]').value = null;
    },
    async submit() {
      // 写真投稿時にローディング画面を表示
      this.loading = true;

      const formData = new FormData();
      formData.append("photo", this.photo);
      const response = await axios.post("/api/photos", formData);

      // ローディング画面を表示
      this.loading = false;

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors;
        return false;
      }

      this.reset();
      this.$emit("input", false);

      if (response.status !== CREATED) {
        this.$store.commit("error/setCode", resopnse.status);
        return false;
      }

      // 投稿が成功したらメッセージを表示
      this.$store.commit("message/setContent", {
        content: "写真が投稿されました",
        timeout: 6000
      });

      // 投稿が成功したら写真の詳細画面に遷移
      this.$router.push(`/photos/${response.data.id}`);
    }
  }
};
</script>

<style>
</style>