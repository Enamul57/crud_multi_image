<template>
  <div class="container max-w-3xl mx-auto px-4 sm:px-8 mt-10">
    <div class="py-8">
      <div class="flex justify-between mb-4">
        <h2 class="text-2xl font-semibold leading-tight">Post Lists</h2>
        <router-link
          :to="{ name: 'Post' }"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Create Post
        </router-link>
      </div>

      <div class="mx-auto px-4 sm:px-8 py-4 overflow-x-auto">
        <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
          <table class="min-w-full leading-normal">
            <thead>
              <tr>
                <th
                  class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  SL
                </th>
                <th
                  class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Title
                </th>

                <th
                  class="px-5 py-3 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              <tr class="hover:bg-gray-50" v-for="(post, index) in posts" :key="index">
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <p class="text-gray-900 whitespace-no-wrap">{{ ++index }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                  <p class="text-gray-900 whitespace-no-wrap">{{ post["title"] }}</p>
                </td>
                <td
                  class="px-5 py-5 space-x-4 border-b border-gray-200 bg-white text-sm text-right"
                >
                  <router-link
                    :to="{ name: 'View', params: { id: post['id'] } }"
                    class="text-green-500 hover:text-green-700 font-medium"
                  >
                    View
                  </router-link>
                  <router-link
                    :to="{ name: 'Edit', params: { id: post['id'] } }"
                    class="text-blue-500 hover:text-blue-700 font-medium"
                  >
                    Edit
                  </router-link>
                  <button
                    @click="deletePost(post['id'])"
                    class="text-red-500 hover:text-red-700 font-medium"
                  >
                    Delete
                  </button>
                </td>
              </tr>
              <!-- Repeat rows as needed -->
            </tbody>
          </table>
          <div class="mx-auto max-w-xl space-x-2">
            <button
              @click="fetchData(links.url)"
              :disabled="!links.url"
              :class="[
                'px-2',
                'py-1',
                {
                  'bg-white text-black': links.active,
                  'bg-slate-500 text-white': !links.active && links.url,
                  'bg-gray-200 text-slate-600 cursor-not-allowed': !links.url,
                },
              ]"
              v-for="links in paginationLinks"
              :key="links.label"
              v-html="links.label"
            ></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import api from "../../plugins/axios";

const posts = ref([]);
const paginationLinks = ref([]);

onMounted(() => fetchData("/api/post"));

const deletePost = async (id) => {
  const response = await api.delete("/api/post/" + id);
  fetchData("api/post");
};

const fetchData = async (url) => {
  const response = await api.get(url);
  posts.value = response.data.data;
  console.log(posts.value);
  paginationLinks.value = response.data.links;
};
</script>
