<!-- File: components/MultiImageUpload.vue -->
<template>
  <div class="max-w-xl mt-10 mx-auto sp-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl min-w-md font-bold mb-4 py-2 text-center bg-blue-500">
      Edit Post
    </h2>
    <input
      type="text"
      v-model="title"
      :placeholder="placeHolderText"
      class="min-w-md focus:outline-none border-2 border-sky-600 px-4 py-1 rounded-sm text-lg text-slate-600"
    />
    <input
      type="file"
      ref="fileInput"
      multiple
      @change="handleFiles"
      class="block w-full text-sm text-gray-500 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
    />
    <div class="grid grid-cols-3 gap-4 mt-6" v-if="previews.length">
      <div v-for="(img, index) in previews" :key="index" class="relative group">
        <img
          v-if="!isPreviewChanged"
          :src="`/storage/${img.file_path}`"
          class="rounded-md object-cover h-32 w-full"
        />
        <img
          v-if="isPreviewChanged"
          :src="img"
          class="rounded-md object-cover h-32 w-full"
        />
        <button
          class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 text-xs hidden group-hover:flex items-center justify-center"
          @click="removeImage(index)"
        >
          ✕
        </button>
      </div>
    </div>

    <button
      @click="submit"
      class="mt-6 w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700"
    >
      Submit
    </button>
    <div v-if="isError" class="bg-red-500 mt-2 rounded-md px-4 py-1">
      <span class="text-white text-sm" v-for="(error, index) in errors" :key="index">
        {{ error }}</span
      >
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../../plugins/axios";

const fileInput = ref(null);
const files = ref([]);
const previews = ref([]);
const errors = ref([]);
const isError = ref(false);
const title = ref("");
const placeHolderText = ref("Type Title...");
const isPreviewChanged = ref(false);
const router = useRouter();
const routes = useRoute();
onMounted(() => {
  fetchData();
});

const fetchData = async () => {
  const response = await api.get("/api/post/" + routes.params.id);
  title.value = response.data.title;
  previews.value = response.data.images;
};
const handleFiles = (e) => {
  isPreviewChanged.value = true;
  files.value = Array.from(e.target.files);
  previews.value = files.value.map((file) => URL.createObjectURL(file));
};

const removeImage = (index) => {
  files.value.splice(index, 1);
  previews.value.splice(index, 1);
};

const submit = async () => {
  const formData = new FormData();
  formData.append("id", routes.params.id);
  formData.append("title", title.value);
  files.value.forEach((file, index) => {
    formData.append(`images[${index}]`, file);
  });
  isError.value = false;
  try {
    const response = await api.post("/api/post/update", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        Accept: "application/json",
      },
    });
    title.value = "";
    files.value = [];
    previews.value = [];
    fileInput.value.value = null;
    console.log(response);
    router.push({ name: "Index" });
  } catch (error) {
    isError.value = true;
    const serverErrors = error.response.data.Error;
    if (error.status === 422) {
      errors.value = Object.values(serverErrors)[0];
    }
  }
};
</script>

<style scoped>
/* Optional fade for image preview removal */
img {
  transition: opacity 0.2s;
}
</style>
