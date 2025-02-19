<template>
    <AuthenticatedLayout>
      <Head title="Запросы на модерацию" />
  
      <div class="max-w-7xl mx-auto p-6 bg-white shadow sm:rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Запросы на модерацию</h1>
        
        <!-- Запросы проектов -->
        <h2 class="text-xl font-semibold mb-2">Проекты</h2>
        <table class="min-w-full mb-6">
          <thead>
            <tr>
              <th class="px-4 py-2">Название</th>
              <th class="px-4 py-2">Автор</th>
              <th class="px-4 py-2">Действия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="project in pendingProjects" :key="project.id">
              <td class="border px-4 py-2">{{ project.title }}</td>
              <td class="border px-4 py-2">{{ project.creator }}</td>
              <td class="border px-4 py-2">
                <!-- Кнопка для одобрения -->
                <form :action="route('admin.projects.approve', project.id)" method="POST" class="inline-block mr-2">
                    <input type="hidden" name="_token" :value="csrf" />
                    <button type="submit" class="btn bg-green-500 text-white">Одобрить</button>
                </form>
                  
                <!-- Кнопка для отклонения -->
                <form :action="route('admin.projects.reject', project.id)" method="POST" class="inline-block">
                    <input type="hidden" name="_token" :value="csrf" />
                    <button type="submit" class="btn bg-red-500 text-white">Отклонить</button>
                  </form>
                  
              </td>
            </tr>
          </tbody>
        </table>
  
        <!-- Запросы объявлений о работе -->
        <h2 class="text-xl font-semibold mb-2">Объявления о работе</h2>
        <table class="min-w-full">
          <thead>
            <tr>
              <th class="px-4 py-2">Название</th>
              <th class="px-4 py-2">Автор</th>
              <th class="px-4 py-2">Действия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="jobAd in pendingJobAds" :key="jobAd.id">
              <td class="border px-4 py-2">{{ jobAd.Title }}</td>
              <td class="border px-4 py-2">{{ jobAd.creator }}</td>
              <td class="border px-4 py-2">
                <!-- Кнопка для одобрения -->
                <form :action="route('admin.jobAds.approve', jobAd.id)" method="POST" class="inline-block mr-2">
                  <input type="hidden" name="_token" :value="csrf">
                  <button type="submit" class="btn bg-green-500 text-white">Одобрить</button>
                </form>
                <!-- Кнопка для отклонения -->
                <form :action="route('admin.jobAds.reject', jobAd.id)" method="POST" class="inline-block">
                  <input type="hidden" name="_token" :value="csrf">
                  <button type="submit" class="btn bg-red-500 text-white">Отклонить</button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { computed } from 'vue'
  import { Head, usePage } from '@inertiajs/vue3'
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  
  const pageProps = usePage().props

  const pendingProjects = computed(() => pageProps.pendingProjects  || [])
 const pendingJobAds = computed(() => pageProps.pendingJobAds || [])
  const csrf = computed(() => pageProps.csrf || '')
  console.log(csrf)
  </script>
  
  
  