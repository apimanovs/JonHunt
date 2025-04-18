<script setup>
import { computed, ref } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import introJs from 'intro.js';
import 'intro.js/introjs.css';



const { props: pageProps } = usePage();

const Layout = computed(() => {
    return pageProps.auth ? AuthenticatedLayout : GuestLayout;
});

const getTimeRemaining = (deadline) => {
  if (!deadline) return '';
  const now = new Date();
  const end = new Date(deadline);

  const nowDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  const endDate = new Date(end.getFullYear(), end.getMonth(), end.getDate());
  const diffDays = Math.round((endDate - nowDate) / (1000 * 60 * 60 * 24));

  if (diffDays === 0) {
    return "Today is deadline!";
  }
  if (diffDays < 0) {
    return "Deadline passed";
  }
  return `${diffDays} days left`;
};

const showReportModal = ref(false);
const reportReason = ref("");
const reportTarget = ref({ project_id: null, job_ad_id: null });

const openReportModal = (projectId = null, jobAdId = null) => {
  reportTarget.value = {
    project_id: projectId,
    job_ad_id: jobAdId,
  };
  reportReason.value = "";
  showReportModal.value = true;
};

const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const submitReport = async () => {
  if (!reportReason.value.trim()) {
    alert("Please enter a reason.");
    return;
  }

  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  try {
    const response = await fetch("/reports", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": token,
      },
      credentials: "include",
      body: JSON.stringify({
        reason: reportReason.value,
        project_id: reportTarget.value.project_id,
        job_advertisement_id: reportTarget.value.job_ad_id,
      }),
    });

    if (!response.ok) {
      const data = await response.json();
      alert(data.message || "Failed to submit report.");
      return;
    }

    showReportModal.value = false;
    alert("Report submitted!");
  } catch (err) {
    console.error(err);
    alert("Error occurred.");
  }
};


const user = computed(() => pageProps.auth || null);

const activeTab = ref('projects');

const selectedNiche = ref('');
const selectedBudgetRange = ref('');
const selectedCompletionDate = ref('');

const budgetRanges = [
    { label: 'Any', value: '' },
    { label: 'Up to $500', value: '0-500' },
    { label: '$500 - $1000', value: '500-1000' },
    { label: 'Over $1000', value: '1000+' },
];

const sortedProjects = computed(() => {
    return pageProps.projects
        .filter(project => project.Status === 'approved')
        .slice()
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

const filteredProjects = computed(() => {
    return sortedProjects.value.filter(project => {
        if (selectedNiche.value && project.niche !== selectedNiche.value) {
            return false;
        }
        if (selectedBudgetRange.value) {
            const [min, max] = selectedBudgetRange.value.split('-').map(Number);
            const budget = project.budget || 0;
            if ((min && budget < min) || (max && budget > max)) {
                return false;
            }
        }
        if (selectedCompletionDate.value && project.completion_date !== selectedCompletionDate.value) {
            return false;
        }
        return true;
    });
});

const filteredJobAds = computed(() => {
    return pageProps.jobAds.filter(ad => {
        if (ad.Status !== 'approved') {
            return false;
        }
        if (selectedBudgetRange.value) {
            const [min, max] = selectedBudgetRange.value.split('-').map(Number);
            const price = ad.Price || 0;
            if ((min !== undefined && price < min) || (max !== undefined && price > max)) {
                return false;
            }
        }
        return true;
    });
});

// ----- Pagination Setup -----
const itemsPerPage = 18;
const currentPageProjects = ref(1);
const currentPageJobAds = ref(1);

const totalPagesProjects = computed(() => {
    return Math.ceil(filteredProjects.value.length / itemsPerPage);
});
const totalPagesJobAds = computed(() => {
    return Math.ceil(filteredJobAds.value.length / itemsPerPage);
});

const paginatedProjects = computed(() => {
    const start = (currentPageProjects.value - 1) * itemsPerPage;
    return filteredProjects.value.slice(start, start + itemsPerPage);
});

const paginatedJobAds = computed(() => {
    const start = (currentPageJobAds.value - 1) * itemsPerPage;
    return filteredJobAds.value.slice(start, start + itemsPerPage);
});

const setTab = (tab) => {
    activeTab.value = tab;
};

const goToPageProjects = (page) => {
    currentPageProjects.value = page;
};
const goToPageJobAds = (page) => {
    currentPageJobAds.value = page;
};
</script>

<template>
    <Head title="JobHunt - Projects List" />
  
    <component :is="Layout">
        <template #header v-if="user">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Projects List
            </h2>
        </template>

        <div class="bg-gradient-to-r from-red-500 via-red-700 to-red-900 overflow-hidden shadow-lg sm:rounded-xl w-2/3 mx-auto my-10">
          <div class="text-center p-8 text-white">
              <div v-if="$page.props.auth && $page.props.auth.user" class="text-3xl font-bold mb-6">
                  👋 Welcome back, <span>{{ $page.props.auth.user.name }}</span>!
              </div>
              <div v-else class="text-3xl font-bold mb-6">
                  👋 Welcome, Guest! 
                  <a href="/login" class="underline hover:text-gray-200 transition">Login</a> to access more features.
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                <div class="bg-white p-6 text-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                  <h3 class="text-lg font-semibold text-left mb-4">
                      🔔 Notifications
                  </h3>
                  <p class="text-base">You have 2 unread messages from clients.</p>
                  <div class="mt-4 text-right">with love from JobHunt team</div>
              </div>
                <div class="bg-white p-6 text-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                  <h3 class="text-lg font-semibold text-left mb-4">
                      🕒 Next Deadline
                  </h3>
                  <p class="text-base">Project: <strong>Website Redesign</strong></p>
                  <p class="text-base">Due: <strong>3 days remaining</strong></p>
                </div>
            </div>
          </div>
      </div>
      
        <div class="filters mt-6">
          <div class="flex flex-wrap gap-4 justify-center">
              <select v-model="selectedNiche" class="p-2 border border-gray-300 rounded-lg">
                  <option value="">All Niches</option>
                  <option v-for="niche in [...new Set(pageProps.projects.map(p => p.niche))]" :key="niche" :value="niche">
                      {{ niche }}
                  </option>
              </select>
              <select v-model="selectedBudgetRange" class="p-2 border border-gray-300 rounded-lg">
                  <option v-for="range in budgetRanges" :key="range.value" :value="range.value">
                      {{ range.label }}
                  </option>
              </select>
          </div>
      </div>

        <!-- Tabs for switching between Projects and Freelancer Ads -->
        <div class="tabs flex justify-center gap-4 mt-6">
            <button :class="{'border-b-4 border-red-500 font-bold': activeTab === 'projects'}" @click="setTab('projects')" class="py-2 px-4">
                Projects
            </button>
            <button :class="{'border-b-4 border-red-500 font-bold': activeTab === 'jobAds'}" @click="setTab('jobAds')" class="py-2 px-4">
                Freelancer Ads
            </button>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Projects Tab -->
            <div v-if="activeTab === 'projects'">
              <ul v-if="paginatedProjects.length">
                <div class="flex flex-wrap -mx-2 sm:-mx-3">
                  <div v-for="project in paginatedProjects" :key="project.id" class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3 px-2 sm:px-3 mb-6 sm:mb-8">
                    <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                      <div class="p-4 sm:p-6">
                        <a :href="`/projects/${project.id}`" class="text-base sm:text-lg font-semibold text-gray-800 mb-3 hover:underline block truncate">
                          {{ project.title }}
                        </a>
                        <div class="creator-info flex items-center mt-2">
                          <a v-if="project.creator && project.creator.avatar" :href="`/user/${project.creator.username}`" class="flex items-center">
                            <img :src="project.creator.avatar.photo_url" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
                          </a>
                          <a v-else :href="`/user/${project.creator.username}`" class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center mr-3">
                              {{ project.creator.name.charAt(0).toUpperCase() }}
                            </div>
                          </a>
                          <a :href="`/user/${project.creator.username}`" class="text-gray-700 font-medium hover:text-blue-500 transition flex items-center">
                            {{ project.creator.name }}
                            <span v-if="project.creator.role === 'freelancer'" class="badge badge-accent ml-2">
                              Freelancer
                            </span>
                          </a>
                        </div>
                        <p class="text-gray-600 text-sm sm:text-base mb-1 sm:mb-2 mt-2">
                          <strong>Budget:</strong> ${{ project.budget }}
                        </p>
                        <p v-if="project.completion_date === null" class="text-gray-600 text-sm sm:text-base mb-1 sm:mb-2">
                          <strong>Completion Date:</strong> No deadline
                        </p>
                        <p v-else class="text-gray-600 text-sm sm:text-base mb-1 sm:mb-2">
                          <strong>Completion Date:</strong> {{ project.completion_date }}
                          <span class="ml-2 text-gray-500">
                            ({{ getTimeRemaining(project.completion_date) }})
                          </span>
                        </p>      
                        <p class="text-gray-600 text-sm sm:text-base">
                          <strong>Niche:</strong> {{ project.niche }}
                        </p>
                        <button
                        v-if="user"
                        @click="openReportModal(project.id, null)"
                        class="text-red-500 hover:underline text-sm mt-2"
                      >
                        🚩 Report
                      </button>
                      </div>
                    </div>
                  </div>
                </div>
              </ul>
              <div v-else class="text-gray-600">
                Users haven't created any projects yet.
              </div>
              <!-- Pagination for Projects -->
              <div v-if="totalPagesProjects > 1" class="flex justify-center mt-6 space-x-2">
                <button :disabled="currentPageProjects === 1" @click="currentPageProjects--" class="px-3 py-1 border rounded disabled:opacity-50">Prev</button>
                <button v-for="page in totalPagesProjects" :key="page" @click="goToPageProjects(page)" :class="{'bg-red-500 text-white': currentPageProjects === page, 'bg-white text-black': currentPageProjects !== page}" class="px-3 py-1 border rounded">
                  {{ page }}
                </button>
                <button :disabled="currentPageProjects === totalPagesProjects" @click="currentPageProjects++" class="px-3 py-1 border rounded disabled:opacity-50">Next</button>
              </div>
            </div>
          
            <!-- Freelancer Ads Tab -->
            <div v-if="activeTab === 'jobAds'">
              <ul v-if="paginatedJobAds.length">
                <div class="flex flex-wrap -mx-2 sm:-mx-3">
                  <div v-for="ad in paginatedJobAds" :key="ad.id" class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3 px-2 sm:px-3 mb-6 sm:mb-8">
                    <div class="card bg-base-100 w-full shadow-xl hover:shadow-2xl transition-shadow duration-300">
                      <figure>
                        <img v-if="ad.portfolios && ad.portfolios.length > 0" :src="ad.portfolios[0].example_url" alt="Portfolio example" class="object-cover w-full h-48" />
                        <img v-else src="https://via.placeholder.com/400" alt="Placeholder" class="object-cover w-full h-48" />
                      </figure>
                      <div class="card-body">
                        <h2 class="card-title">
                          <a :href="`/gigs/${ad.id}`" class="hover:underline">
                            {{ ad.Title || 'No title provided' }}
                          </a>
                        </h2>
                        <div class="creator-info flex items-center mt-2">
                          <a v-if="ad.creator && ad.creator.avatar" :href="`/users/${ad.creator.username}`" class="flex items-center">
                            <img :src="ad.creator.avatar.photo_url" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
                          </a>
                          <a v-else :href="`/user/${ad.creator.username}`" class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center mr-3">
                              {{ ad.creator.name.charAt(0).toUpperCase() }}
                            </div>
                          </a>
                          <a :href="`/user/${ad.creator.username}`" class="text-gray-700 font-medium hover:text-blue-500 transition flex items-center">
                            {{ ad.creator.name }}
                            <span v-if="ad.creator.role === 'freelancer'" class="badge badge-accent mx-2">
                              Freelancer
                            </span>
                          </a>
                        </div>
                        <p class="text-gray-600 text-sm">
                          <strong>Starts from:</strong> ${{ ad.Price }}
                        </p>
                        <button
                          v-if="user"
                          @click="openReportModal(null, ad.id)"
                          class="text-red-500 hover:underline text-sm mt-2"
                        >
                          🚩 Report
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </ul>
              <div v-else class="text-gray-600">
                No freelancer ads available.
              </div>
              <!-- Pagination for Job Ads -->
              <div v-if="totalPagesJobAds > 1" class="flex justify-center mt-6 space-x-2">
                <button :disabled="currentPageJobAds === 1" @click="currentPageJobAds--" class="px-3 py-1 border rounded disabled:opacity-50">Prev</button>
                <button v-for="page in totalPagesJobAds" :key="page" @click="goToPageJobAds(page)" :class="{'bg-red-500 text-white': currentPageJobAds === page, 'bg-white text-black': currentPageJobAds !== page}" class="px-3 py-1 border rounded">
                  {{ page }}
                </button>
                <button :disabled="currentPageJobAds === totalPagesJobAds" @click="currentPageJobAds++" class="px-3 py-1 border rounded disabled:opacity-50">Next</button>
              </div>
            </div>
          </div>
    </component>
    <div v-if="showReportModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h3 class="text-xl font-semibold mb-4">Report</h3>
        <textarea
          v-model="reportReason"
          placeholder="Write your reason..."
          class="w-full border border-gray-300 rounded p-2 mb-4"
          rows="4"
        ></textarea>
        <div class="flex justify-end space-x-2">
          <button @click="showReportModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
          <button @click="submitReport" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Submit</button>
        </div>
      </div>
    </div>
</template>
