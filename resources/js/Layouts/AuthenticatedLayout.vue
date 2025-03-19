<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick, watch } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import axios from 'axios';
import introJs from 'intro.js';
import 'intro.js/introjs.css';

const startOnBoarding = async () => {
  await nextTick();

  const intro = introJs();

  intro.setOptions({
    steps: [
      {
        title: "🚀 Welcome to JobHunt!",
        intro: `
          <h2 style="text-align: center; font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;">
            Hey there! 👋
          </h2>
          <p style="text-align: center; font-size: 1rem;">
            Welcome to <strong>JobHunt</strong> – your ultimate platform for finding projects and connecting with freelancers.
          </p>
          <p style="text-align: center; font-size: 1rem;">
            Let me show you around! Click <strong>"Next →"</strong> to begin your journey. 🚀
          </p>
        `
      },
      {
        intro: `
          <div style="text-align: center;">
            <iframe src="https://giphy.com/embed/MDJ9IbxxvDUQM" width="480" height="269" style="border-radius: 10px; max-width: 100%;" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
            <p style="font-size: 1rem; margin-top: 10px;">
              Let’s explore the features of JobHunt together! 😊
            </p>
          </div>
        `
      },
      {
        element: '#menu-panel',
        intro: '📌 This is your main menu. Here, you can access different sections of JobHunt.',
      },
      {
        element: '#my-projects',
        intro: '📂 Here you can find all your projects, track progress, and manage tasks.',
      },
      {
        element: '#my-orders',
        intro: '🛒 Keep track of your purchases here.',
      },
      {
        element: '#my-applications',
        intro: '✍️ View all your submitted applications.',
      },
      {
        element: '#my-search',
        intro: '🔍 Use our powerful search feature to find projects and freelancers.',
      },
      {
        element: '#my-balance',
        intro: '💰 Check and manage your account balance.',
      },
      {
        element: '#my-profile',
        intro: '👤 Customize your profile and showcase your skills to potential clients.',
      },
    ],
    showProgress: true,
    showBullets: false,
    exitOnOverlayClick: true,
    nextLabel: 'Next →',
    prevLabel: '← Back',
    doneLabel: '✅',
  });

  intro.start();

  intro.oncomplete(() => {
  removeIsNewUserFlag();
  });
  intro.onexit(() => {
    removeIsNewUserFlag();
  });
};

onMounted(() => {
  const isNewUser = props.auth?.user?.isNewUser;
  if (isNewUser) {
    startOnBoarding();
  }

  isNewUser === false;
});


function removeIsNewUserFlag() {
  axios.post(route('forget.newuser'))
    .then(() => {
      console.log('deleted');
    })
    .catch((error) => {
      console.error(error);
    });
}

const { props } = usePage();
console.log(props);
console.log('Notifications:', props.notifications);

const notifications = props.notifications || [];
const unreadNotifications = computed(() =>
  notifications.filter((notification) => notification.read_at === null)
);

const showingNavigationDropdown = ref(false);
const searchQuery = ref('');


const isNotificationOpen = ref(false);

const showToast = ref(false);

onMounted(() => {
  if (props.flash?.success) {
    showToast.value = true;

    setTimeout(() => {
      showToast.value = false;
    }, 5000);
  }
});


function toggleNotifications() {
  isNotificationOpen.value = !isNotificationOpen.value;
}


function handleClickOutside(event) {
  if (!event.target.closest('.notification-menu')) {
    isNotificationOpen.value = false;
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});


const markAllAsRead = async () => {
  try {
    if (unreadNotifications.value.length > 0) {
      unreadNotifications.value.forEach(notification => {
        notification.read_at = new Date().toISOString();
      });
      await axios.post('/notifications/mark-all-as-read');
    }
  } catch (error) {
    console.error('Ошибка при обновлении уведомлений:', error);
  }
};


function search() {
  router.visit(route('search.index'), {
    method: 'get',
    data: {
      query: searchQuery.value,
    },
    preserveState: true,
    preserveScroll: true,
  });
}
</script>

<template>
  <div v-if="showToast" class="toast toast-end">
    <div class="alert alert-success">
      <p class="font-semibold text-white">{{ props.flash.success }} ✅</p>
    </div>
  </div>

  <div>
    <div class="min-h-screen bg-gray-100 selection:bg-red-500 selection:text-white overflow-hidden">
      <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('dashboard')">
                  <ApplicationLogo
                    class="block h-9 w-auto fill-current text-gray-800"
                  />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div id="menu-panel" class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"> 
                <template v-if="$page.props.auth.user">
                  <NavLink id="my-projects" :href="route('projects.inProfile')" :active="route().current('projects.inProfile')">
                    My Projects
                  </NavLink>

                  <NavLink id="my-orders" :href="route('orders.index')" :active="route().current('orders.index')">
                    My Orders
                  </NavLink>

                  <NavLink id="my-applications" :href="route('projects.applications.all')" :active="route().current('projects.applications.all')">
                    All My Applications
                  </NavLink>
                  
                  <template v-if="$page.props.auth.user.role.includes('freelancer')">
                    <NavLink :href="route('jobAds.index')" :active="route().current('jobAds.index')">
                      My Gigs
                    </NavLink>
                  </template>
                </template>

              </div>
            </div>

            <!-- Поиск -->
            <form id="my-search" @submit.prevent="search" class="hidden sm:flex items-center">
              <input
                v-model="searchQuery"
                type="search"
                placeholder="Search projects & ads..."
                class="border border-gray-300 rounded-l px-3 py-2 focus:outline-none"
              />
              <button
                type="submit"
                class="bg-red-600 text-white px-3 py-2 rounded-r hover:bg-gray-500"
              >
                Search
              </button>
            </form>

            <!-- Права часть меню -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
              
              <!-- Иконка уведомлений -->
              <div class="relative notification-menu inline-flex items-center me-4">
                <!-- Кнопка-иконка -->
                <svg
                  @click="toggleNotifications"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="cursor-pointer size-11 inline-flex items-center py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white focus:outline-none transition ease-in-out duration-150"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"
                  />
                </svg>
                <!-- Красная точка (счётчик непрочитанных) -->
                <span
                  v-if="unreadNotifications.length > 0"
                  class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full"
                ></span>
                
                <!-- Выпадающее меню уведомлений -->
                <div
                  v-show="isNotificationOpen"
                  class="absolute right-6 mt-96 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50
                         transition-all duration-200 ease-in-out cursor-pointer"
                  :class="{
                    'opacity-100 pointer-events-auto scale-100': isNotificationOpen,
                    'opacity-0 pointer-events-none scale-95': !isNotificationOpen
                  }"
                >
                  <div v-if="notifications.length === 0" class="p-4 text-gray-500 text-sm">
                    No new notifications
                  </div>
                  <ul v-else>
                    <li
                      v-for="notification in notifications.slice(0, 3)"
                      :key="notification.id"
                      class="px-4 py-2 hover:bg-gray-100 text-gray-700 text-sm"
                    >
                      <strong>Message:</strong> {{ notification.data.message }} <br/>
                      <strong>Sender:</strong> {{ notification.data.sender_name }} <br/>
                      <strong>Job Title:</strong> {{ notification.data.job_title || 'N/A' }}
                    </li>
                  </ul>
                  <button
                    @click="markAllAsRead"
                    class="w-full text-center bg-red-500 text-white py-2 hover:bg-red-700"
                  >
                    Mark all as read
                  </button>
                </div>
              </div>
                            

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-11 inline-flex items-center py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white  focus:outline-none transition ease-in-out duration-150 " >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                              </svg>
                            
                              <template v-if="$page.props.auth.user">
                                <NavLink id="my-balance" :href="route('balance.index')">
                                    <p class="text-gray-500 mx-4"> ${{ $page.props.auth.user.balance }}</p>
                                </NavLink>
                              </template>

                            <!-- Settings Dropdown -->
                            <div id="my-profile" class="ms-3 relative" v-if="$page.props.auth && $page.props.auth.user">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <!-- Проверка на наличие аватара -->
                                                <span v-if="$page.props.auth && $page.props.auth.user">
                                                    <img
                                                        v-if="$page.props.auth.user.avatar"
                                                      :src="$page.props.auth.user.avatar.photo_url"
                                                      alt="User Avatar"
                                                      class="w-8 h-8 rounded-full mx-2"
                                                    />
                                                  </span>
                                                <!-- Отображение первой буквы имени, если аватара нет -->
                                                <span v-else class="w-6 h-6 rounded-full mr-2 flex items-center justify-center bg-gray-400 text-white font-bold">
                                                    {{ $page.props.auth.user ? $page.props.auth.user.name.charAt(0).toUpperCase() : 'G' }}
                                                </span>
                                    
                                                {{ $page.props.auth.user ? $page.props.auth.user.name : 'Guest' }}
                                    
                                                <template v-if="$page.props.auth.user">
                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </template>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                      <template v-if="$page.props.auth.user.role === 'user'">
                                        <DropdownLink :href="route('freelancer.registration')"> Become a Freelancer </DropdownLink>
                                      </template>
                                            <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                            <DropdownLink :href="route('logout')" method="post" as="button">
                                                Log Out
                                            </DropdownLink>
                                        </template>
                                </Dropdown>
                            </div>
                            <div v-else>
        <Link
            :href="route('login')"
            class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
        >
            Sign In
        </Link>
    </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4 flex items-center">
                            <!-- Если у пользователя есть аватар, отображаем его -->
                            <img 
                                v-if="$page.props.auth.user && $page.props.auth.user.avatar" 
                                :src="$page.props.auth.user.avatar.photo_url" 
                                alt="User Avatar" 
                                class="w-10 h-10 rounded-full mr-3"
                            />
                    
                            <!-- Если аватара нет, отображаем круг с инициалом -->
                            <div 
                                v-else 
                                class="w-10 h-10 rounded-full mr-3 flex items-center justify-center bg-gray-400 text-white font-bold"
                            >
                                {{ $page.props.auth.user ? $page.props.auth.user.name.charAt(0).toUpperCase() : 'G' }}
                            </div>
                            
                            <!-- Отображаем имя и email пользователя -->
                            <div>
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.auth.user ? $page.props.auth.user.name : 'Guest' }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user ? $page.props.auth.user.email : 'No email' }}
                                </div>
                            </div>
                        </div>
                    
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                    
                </div>
            </nav>



            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>

    <hr>
    <footer class="footer bg-base-200 p-10 flex flex-col md:flex-row md:justify-around selection:bg-red-500 selection:text-white">
      <button @click="startOnBoarding" class="px-5 py-2 bg-red-700 text-white rounded-lg hover:bg-primary-focus transition-shadow shadow-md">
        Start Guide 🚀
      </button>
        <nav class="mb-6 md:mb-0">
          <h6 class="footer-title font-bold mb-3">Company</h6>
          <a class="link link-hover flex items-center"><span class="mr-2">👤</span>About us</a>          
          <a class="link link-hover flex items-center"><span class="mr-2">📞</span>Contact</a>        </nav>
          <nav>
            <h6 class="footer-title font-bold mb-2">Legal</h6>
            <a class="link link-hover flex items-center"><span class="mr-2">📄</span>Terms of Service</a>
            <a class="link link-hover flex items-center"><span class="mr-2">🔒</span>Privacy Policy</a>
          </nav>
      </footer>
      
      
      <footer class="footer bg-base-200 text-base-content border-base-300 px-10 py-4 selection:bg-red-500 selection:text-white">
        <aside class="grid-flow-col items-center">
            <ApplicationLogo
            class="block h-9 w-auto fill-current text-gray-800"
        />
          <p>
            <p>&copy; {{ new Date().getFullYear() }} JobHunt. All rights reserved.</p>
            Providing reliable service since 2024
          </p>
        </aside>
        <nav class="md:place-self-center md:justify-self-end">
          <div class="grid grid-flow-col gap-4">
          </div>
        </nav>
      </footer>
</template>
