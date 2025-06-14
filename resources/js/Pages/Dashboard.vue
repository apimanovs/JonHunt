<script setup>
import { computed, ref } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import introJs from 'intro.js';
import 'intro.js/introjs.css';

const { props: pageProps } = usePage();
const Layout = computed(() => pageProps.auth ? AuthenticatedLayout : GuestLayout);
const user = computed(() => pageProps.auth || null);

const activeTab = ref('projects');

const selectedNiche = ref('');
const selectedBudgetRange = ref('');
const sortBy = ref('newest');

const budgetRanges = [
    { label: 'Any', value: '' },
    { label: 'Up to $500', value: '0-500' },
    { label: '$500 - $1000', value: '500-1000' },
    { label: 'Over $1000', value: '1000-999999' },
];

const sortOptions = [
    { label: 'Newest first', value: 'newest' },
    { label: 'Oldest first', value: 'oldest' },
    { label: 'Lowest budget first', value: 'low_budget' },
    { label: 'Highest budget first', value: 'high_budget' },
];

const getTimeRemaining = (deadline) => {
  if (!deadline) return '';
  const now = new Date();
  const end = new Date(deadline);
  const nowDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  const endDate = new Date(end.getFullYear(), end.getMonth(), end.getDate());
  const diffDays = Math.round((endDate - nowDate) / (1000 * 60 * 60 * 24));

  if (diffDays === 0) return "Today is deadline!";
  if (diffDays < 0) return "Deadline passed";
  return `${diffDays} days left`;
};

const filteredProjects = computed(() => {
    let projects = pageProps.projects.filter(p => p.Status === 'approved');

    if (selectedNiche.value) {
        projects = projects.filter(p => p.niche === selectedNiche.value);
    }
        if (selectedBudgetRange.value) {
        const [minStr, maxStr] = selectedBudgetRange.value.split('-');
        const min = Number(minStr);
        const max = Number(maxStr);

        projects = projects.filter(p => {
            const budget = p.budget || 0;
            if (!isNaN(min) && budget < min) return false;
            if (!isNaN(max) && budget > max) return false;
            return true;
        });
    }

    switch (sortBy.value) {
        case 'newest':
            projects.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            break;
        case 'oldest':
            projects.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            break;
        case 'low_budget':
            projects.sort((a, b) => (a.budget || 0) - (b.budget || 0));
            break;
        case 'high_budget':
            projects.sort((a, b) => (b.budget || 0) - (a.budget || 0));
            break;
    }

    return projects;
});

const filteredJobAds = computed(() => {
    let ads = pageProps.jobAds.filter(ad => ad.Status === 'approved');

        if (selectedBudgetRange.value) {
        const [minStr, maxStr] = selectedBudgetRange.value.split('-');
        const min = Number(minStr);
        const max = Number(maxStr);

        ads = ads.filter(ad => {
            const price = ad.Price || 0;
            if (!isNaN(min) && price < min) return false;
            if (!isNaN(max) && price > max) return false;
            return true;
        });
    }

    switch (sortBy.value) {
        case 'newest':
          ads.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            break;
        case 'oldest':
          ads.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            break;
        case 'low_budget':
          ads.sort((a, b) => (a.Price || 0) - (b.Price || 0));
            break;
        case 'high_budget':
          ads.sort((a, b) => (b.Price || 0) - (a.Price || 0));
            break;
    }

    return ads;
});

const itemsPerPage = 9;
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
    currentPageProjects.value = 1;
    currentPageJobAds.value = 1;
};

const goToPageProjects = (page) => {
    currentPageProjects.value = page;
};
const goToPageJobAds = (page) => {
    currentPageJobAds.value = page;
};

const showReportModal = ref(false);
const reportReason = ref("");
const reportTarget = ref({ project_id: null, job_ad_id: null });

const openReportModal = (projectId = null, jobAdId = null) => {
    reportTarget.value = { project_id: projectId, job_ad_id: jobAdId };
    reportReason.value = "";
    showReportModal.value = true;
};

const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const submitReport = async () => {
    if (!reportReason.value.trim()) {
        alert("Please enter a reason.");
        return;
    }

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
</script>


<template>
    <Head title="JobHunt - Projects List" />
  
    <component :is="Layout">
        <template #header v-if="user">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Projects List
            </h2>
        </template>

        <div class="text-center mb-10 mt-10">
          <h1 class="text-4xl font-extrabold mb-4 tracking-tight text-gray-900">
            🚀 Explore Opportunities
          </h1>
          <p class="text-gray-600 text-lg max-w-2xl mx-auto">
            Discover exciting projects and connect with talented freelancers ready to bring ideas to life.  
            Whether you're looking to hire or be hired, your next opportunity starts here!
          </p>
        </div>
        
      
        <div class="filters mt-6">
          <div class="flex flex-wrap justify-center gap-4">
            <div class="flex flex-col items-start">
              <label                 v-if="activeTab !== 'jobAds'"
              for="niche-select" class="text-sm font-medium text-gray-700">Niche</label>
              <select
                id="niche-select"
                v-if="activeTab !== 'jobAds'"
                v-model="selectedNiche"
                class="p-2 border border-gray-300 rounded-lg"
              >
                <option value="">Select a niche</option>
                <option
                  v-for="niche in [...new Set(pageProps.projects.map(p => p.niche))]"
                  :key="niche"
                  :value="niche"
                >
                  {{ niche }}
                </option>
              </select>
            </div>
        
            <div class="flex flex-col items-start">
              <label for="budget-select" class="text-sm font-medium text-gray-700">Budget</label>
              <select
                id="budget-select"
                v-model="selectedBudgetRange"
                class="p-2 border border-gray-300 rounded-lg"
              >
                <option value="">Select budget range</option>
                <option
                  v-for="range in budgetRanges"
                  :key="range.value"
                  :value="range.value"
                >
                  {{ range.label }}
                </option>
              </select>
            </div>
        
            <div class="flex flex-col items-start">
              <label for="sort-select" class="text-sm font-medium text-gray-700">Sort by</label>
              <select
                id="sort-select"
                v-model="sortBy"
                class="p-2 border border-gray-300 rounded-lg"
              >
                <option value="">Select sorting</option>
                <option
                  v-for="option in sortOptions"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </option>
              </select>
            </div>
          </div>
        </div>
        
        

        <!-- Tabs for switching between Projects and Freelancer Ads -->
        <div class="flex justify-center mt-8 mb-6">
          <div class="bg-gray-200 p-1 rounded-full flex space-x-2">
            <button
              @click="setTab('projects')"
              :class="activeTab === 'projects' ? 'bg-red-500 text-white' : 'text-gray-600'"
              class="px-5 py-2 rounded-full text-sm font-semibold transition"
            >
              Projects
            </button>
            <button
              @click="setTab('jobAds')"
              :class="activeTab === 'jobAds' ? 'bg-red-500 text-white' : 'text-gray-600'"
              class="px-5 py-2 rounded-full text-sm font-semibold transition"
            >
              Freelancer Ads
            </button>
          </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Projects Tab -->
            <div v-if="activeTab === 'projects'">
              <ul v-if="paginatedProjects.length">
                <div class="flex flex-wrap -mx-2 sm:-mx-3">
                  <div v-for="project in paginatedProjects" :key="project.id" class="w-full mb-6">
                    <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition duration-300 w-full">
                      <div class="flex items-center mb-4">
                        <a v-if="project.creator?.avatar" :href="`/user/${project.creator.username}`" class="flex items-center">
                          <img :src="project.creator.avatar.photo_url" alt="Avatar" class="w-12 h-12 rounded-full mr-3" />
                          <div>
                            <div class="text-gray-900 font-semibold">{{ project.creator.name }}</div>
                            <div class="text-gray-500 text-sm">@{{ project.creator.username }}</div>
                          </div>
                        </a>
                        <a v-else :href="`/user/${project.creator.username}`" class="flex items-center">
                          <div class="w-12 h-12 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center mr-3">
                            {{ project.creator.name.charAt(0).toUpperCase() }}
                          </div>
                          <div>
                            <div class="text-gray-900 font-semibold">{{ project.creator.name }}</div>
                            <div class="text-gray-500 text-sm">@{{ project.creator.username }}</div>
                          </div>
                        </a>
                      </div>
                  
                      <div class="mt-2">
                        <a :href="`/projects/${project.id}`" class="text-xl font-bold text-gray-800 hover:text-red-500 block mb-2">
                          {{ project.title }}
                        </a>
                        <p class="text-gray-700 mb-1"><strong>Budget:</strong> ${{ project.budget }}</p>
                        <p class="text-gray-700 mb-1"><strong>Niche:</strong> {{ project.niche }}</p>
                        <p class="text-gray-700 mb-1">
                          <strong>Deadline:</strong>
                          <span v-if="project.completion_date">{{ project.completion_date }} ({{ getTimeRemaining(project.completion_date) }})</span>
                          <span v-else>No deadline</span>
                        </p>
                      </div>
                  
                      <div class="mt-4 text-right">
                        <button
                          v-if="user"
                          @click="openReportModal(project.id, null)"
                          class="text-red-500 hover:underline text-sm"
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
                          <a :href="`/gigs/${ad.id}`" class="hover:underline truncate break-words">
                            {{ ad.Title || 'No title provided' }}
                          </a>
                        </h2>
                        <div class="creator-info flex items-center mt-2">
                          <a v-if="ad.creator && ad.creator.avatar" :href="`/user/${ad.creator.username}`" class="flex items-center">
                            <img :src="ad.creator.avatar.photo_url" alt="Avatar" class="w-10 h-10 rounded-full mr-3" />
                          </a>
                          <a v-else :href="`/user/${ad.creator.username}`" class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-400 text-white font-bold flex items-center justify-center mr-3">
                              {{ ad.creator.name.charAt(0).toUpperCase() }}
                            </div>
                          </a>
                          <a :href="`/user/${ad.creator.username}`" class="text-gray-700 font-medium hover:text-blue-500 transition flex items-center">
                            {{ ad.creator.name }}
                            <div v-if="ad.creator.role === 'freelancer'" class="m-3 text-xs  bg-red-500 rounded text-white">Freelancer</div>
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
