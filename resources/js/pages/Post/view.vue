<!-- File: components/MultiImageUpload.vue -->
<template>
  <div class="max-w-xl mt-10 mx-auto sp-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl min-w-md font-bold mb-4 py-2 text-center bg-lime-500">
      View Post
    </h2>
    <h2 class="text-center text-2xl font-semibold font-mono text-slate-600">
      {{ title }}
    </h2>

    <div class="grid grid-cols-2 gap-4 mt-6" v-if="previews.length">
      <div v-for="(img, index) in previews" :key="index" class="relative py-2">
        <img
          :src="`/storage/${img.file_path}`"
          class="rounded-md object-contain h-32 w-full"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "../../plugins/axios";

const files = ref([]);
const previews = ref([]);
const errors = ref([]);
const title = ref("");
const routes = useRoute();
onMounted(() => fetchData());
const fetchData = async () => {
  const response = await api.get("/api/post/" + routes.params.id);
  console.log(response.data);
  title.value = response.data.title;
  previews.value = response.data.images;
};
</script>
