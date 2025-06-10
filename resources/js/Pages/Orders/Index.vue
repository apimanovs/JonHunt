<template>
  <AuthenticatedLayout>
    <Head title="My Orders" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
      <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 sm:p-10 space-y-10">

        <h1 class="text-3xl font-bold text-gray-900 dark:text-white text-center">📦 My Orders</h1>

        <section>
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">🧾 Orders as Client</h2>

          <div v-if="!ordersAsClient.length" class="text-gray-500 text-center">
            You have no orders as client.
          </div>

          <div v-else class="space-y-4">
            <div
              v-for="order in ordersAsClient"
              :key="order.id"
              class="p-5 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700"
            >
              <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-2 md:space-y-0">
                <div>
                  <p class="text-lg font-bold text-gray-900 dark:text-white">
                    💼 Job: {{ order.job_application?.job_ad?.Title || order.project_application?.project?.title || 'N/A' }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-300">
                    👤 Freelancer: {{ order.freelancer.name }}
                  </p>
                  <div class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                    📌 Status:
                    <span
                      class="ml-2 text-xs font-semibold px-3 py-1 rounded-full"
                      :class="{
                        'bg-yellow-200 text-yellow-800': order.status === 'pending',
                        'bg-blue-200 text-blue-800': order.status === 'in_progress',
                        'bg-purple-200 text-purple-800': order.status === 'submitted',
                        'bg-green-200 text-green-800': order.status === 'completed',
                        'bg-red-200 text-red-800': order.status === 'cancelled',
                        'bg-gray-300 text-gray-700': !['pending', 'in_progress', 'submitted', 'completed'].includes(order.status),
                      }"
                    >
                      {{ statusLabel(order.status) }}
                    </span>
                  </div>
                </div>
                <template v-if="['in_progress', 'submitted', 'completed'].includes(order.status)">
                  <Link
                    :href="route('orders.show', order.id)"
                    class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                  >
                    View
                  </Link>
                </template>
              </div>
            </div>
          </div>
        </section>

        <section>
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">🛠 Orders as Freelancer</h2>

          <div v-if="!ordersAsFreelancer.length" class="text-gray-500 text-center">
            You have no orders as freelancer.
          </div>

          <div v-else class="space-y-4">
            <div
              v-for="order in ordersAsFreelancer"
              :key="order.id"
              class="p-5 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700"
            >
              <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-2 md:space-y-0">
                <div>
                  <p class="text-lg font-bold text-gray-900 dark:text-white">
                    💼 Job: {{ order.job_application?.job_ad?.Title || order.project_application?.project?.title || 'N/A' }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-300">
                    👤 Client: {{ order.client.name }}
                  </p>

                    <div class="mt-4 max-w-4xl">
                        <h3 class="text-l font-semibold text-gray-700 dark:text-gray-200 mb-2 ">Requirements</h3>
                        <p class="whitespace-pre-line text-gray-800 dark:text-gray-300 leading-relaxed break-words">
                          {{ order.job_application.requirements }}
                        </p>
                    </div>
                    <br>
                  <div class="text-sm text-gray-600 dark:text-gray-300 flex items-center">
                    📌 Status:
                    <span
                      class="ml-2 text-xs font-semibold px-3 py-1 rounded-full"
                      :class="{
                        'bg-yellow-200 text-yellow-800': order.status === 'pending',
                        'bg-blue-200 text-blue-800': order.status === 'in_progress',
                        'bg-purple-200 text-purple-800': order.status === 'submitted',
                        'bg-green-200 text-green-800': order.status === 'completed',
                        'bg-red-200 text-red-800': order.status === 'cancelled',
                        'bg-gray-300 text-gray-700': !['pending', 'in_progress', 'submitted', 'completed'].includes(order.status),
                      }"
                    >
                      {{ statusLabel(order.status) }}
                    </span>
                  </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                  <template v-if="['in_progress', 'submitted', 'completed', 'cancelled'].includes(order.status)">
                    <Link
                      :href="route('orders.show', order.id)"
                      class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                    >
                      View
                    </Link>
                  </template>
                  <template v-if="auth.user?.id === order.freelancer_id && order.status === 'pending'">
                    <button
                      @click="respondToOrder(order.id, 'accept')"
                      class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
                    >
                      ✅ Accept
                    </button>
                    <button
                      @click="respondToOrder(order.id, 'decline')"
                      class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
                    >
                      ❌ Decline
                    </button>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </section>

      </div>
    </div>
  </AuthenticatedLayout>
</template>


<script setup>
import { usePage, Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const respondToOrder = async (orderId, action) => {
  try {
    await axios.post(route('orders.respond', orderId), { action })
    location.reload()
  } catch (err) {
    alert(err.response?.data?.message || 'Error responding to order.')
  }
}

const statusLabel = (status) => {
  switch (status) {
    case 'pending': return 'Pending';
    case 'in_progress': return 'In Progress';
    case 'submitted': return 'Submitted';
    case 'completed': return 'Completed';
    case 'cancelled': return 'Cancelled';
    default: return 'Unknown';
  }
};
const { props } = usePage()
const ordersAsClient = props.ordersAsClient
const ordersAsFreelancer = props.ordersAsFreelancer
const auth = props.auth || { user: null }
</script>
