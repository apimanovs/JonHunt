<template>
    <AuthenticatedLayout>
      <Head title="Users Management" />
  
      <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Users Managment</h1>
  
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr>
              <th class="px-6 py-3 text-left">ID</th>
              <th class="px-6 py-3 text-left">Avatar</th>
              <th class="px-6 py-3 text-left">Name</th>
              <th class="px-6 py-3 text-left">Email</th>
              <th class="px-6 py-3 text-left">Role</th>
              <th class="px-6 py-3 text-left">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users.data" :key="user.id">
              <td class="px-6 py-4">{{ user.id }}</td>
              <td class="px-6 py-4 flex items-center">
                <a :href="`/user/${user.username}`" class="flex items-center">
                  <img
                    v-if="user.avatar"
                    :src="user.avatar.photo_url"
                    alt="User Avatar"
                    class="w-10 h-10 rounded-full mr-3"
                  />
                  <div
                    v-else
                    class="w-10 h-10 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center mr-3"
                  >
                    {{ user.name.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <div class="text-gray-900 font-semibold">{{ user.name }}</div>
                    <div class="text-gray-500 text-sm">@{{ user.username }}</div>
                  </div>
                </a>
              </td>
                       
            <td class="px-6 py-4">{{ user.name }}</td>
              <td class="px-6 py-4">{{ user.email }}</td>
              <td class="px-6 py-4">{{ user.role }}</td>
              <td class="px-6 py-4">
                <button
                  @click="deleteUser(user.id)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
  
        <div class="mt-4 flex space-x-2">
          <button
            :disabled="!users.links.prev"
            @click="goTo(users.links.prev)"
            class="btn bg-gray-300 px-3 py-1 rounded"
          >
            Previous
          </button>
          <button
            :disabled="!users.links.next"
            @click="goTo(users.links.next)"
            class="btn bg-gray-300 px-3 py-1 rounded"
          >
            Next
          </button>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { Head, Link, router } from '@inertiajs/vue3'
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  
  // Принимаем пропсы
  const props = defineProps({
    users: Object, // результаты пагинации
  })
  
  // Функция удаления пользователя
  function deleteUser(userId) {
    if (!confirm('Вы уверены, что хотите удалить этого пользователя?')) {
      return
    }
    router.delete(route('admin.users.destroy', userId))
  }
  
  // Функция перехода по пагинации
  function goTo(url) {
    if (url) router.get(url)
  }
  </script>
  