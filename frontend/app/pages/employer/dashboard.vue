<template>
  <div class="h-[100dvh] bg-slate-50 flex overflow-hidden">
    <EmployerSidebar />
    <div class="flex-1 ml-64 flex flex-col h-full relative">
      <main class="flex-1 overflow-x-hidden p-6 sm:p-10 overflow-y-auto">
        <div class="max-w-7xl mx-auto space-y-8">
          <div class="flex justify-between items-end">
            <div>
              <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Employer Dashboard</h1>
              <p class="text-slate-500 mt-1">Overview of your active jobs and candidate pipeline.</p>
            </div>
            <button @click="fetchStats" class="p-2 text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Refresh Data">
              <Icon name="lucide:refresh-cw" class="w-5 h-5" :class="{ 'animate-spin': pending }" />
            </button>
          </div>

          <!-- Loading State -->
          <div v-if="pending" class="flex justify-center py-20">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500"></div>
          </div>
          
          <div v-else class="space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <AdminWidget title="Active Jobs" :value="stats.active_jobs" icon="lucide:briefcase" color="indigo" />
              <AdminWidget title="Total Applications" :value="stats.total_applications" icon="lucide:file-text" color="emerald" />
              <AdminWidget title="Shortlisted" :value="stats.shortlisted" icon="lucide:star" color="amber" />
              <AdminWidget title="Interviews Today" :value="stats.today_interviews" icon="lucide:calendar-check" color="rose" />
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- Registration Trend -->
              <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-2">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Application Trend (Last 7 Days)</h3>
                <ClientOnly>
                  <apexchart type="area" height="300" :options="areaChartOptions" :series="charts.applications.series"></apexchart>
                  <template #fallback>
                    <div class="h-[300px] flex items-center justify-center bg-slate-50 rounded-xl">Loading chart...</div>
                  </template>
                </ClientOnly>
              </div>
              
              <!-- Application Status Distribution -->
              <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Candidate Pipeline</h3>
                <ClientOnly>
                  <apexchart type="donut" height="300" :options="donutChartOptions" :series="charts.pipeline.series"></apexchart>
                  <template #fallback>
                    <div class="h-[300px] flex items-center justify-center bg-slate-50 rounded-xl">Loading chart...</div>
                  </template>
                </ClientOnly>
              </div>
            </div>

            <!-- Secondary Stats & Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- Recent Applications -->
              <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                  <h2 class="text-xl font-bold text-slate-800">Recent Applications</h2>
                  <NuxtLink to="/employer/applicants" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">View All</NuxtLink>
                </div>
                <div v-if="!recentApplications.length" class="p-10 text-center text-slate-500">
                  <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <Icon name="lucide:file-x" class="w-8 h-8 text-slate-400" />
                  </div>
                  <p class="font-medium">No recent applications found.</p>
                  <p class="text-sm mt-1">Post a new job to start receiving applications.</p>
                  <NuxtLink to="/employer/jobs/create" class="inline-block mt-4 bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg">Post a Job</NuxtLink>
                </div>
                <div v-else class="overflow-x-auto">
                  <table class="w-full text-left border-collapse">
                    <thead>
                      <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-bold">Candidate</th>
                        <th class="p-4 font-bold">Applied For</th>
                        <th class="p-4 font-bold">Status</th>
                        <th class="p-4 font-bold">Date</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                      <tr v-for="app in recentApplications" :key="app.id" class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4">
                          <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-indigo-700">
                              {{ app.user?.name?.charAt(0) || 'C' }}
                            </div>
                            <div>
                              <p class="font-bold text-sm text-slate-900">{{ app.user?.name }}</p>
                              <p class="text-xs text-slate-500">{{ app.user?.email }}</p>
                            </div>
                          </div>
                        </td>
                        <td class="p-4">
                          <span class="text-sm font-semibold text-slate-700">{{ app.job?.title }}</span>
                        </td>
                        <td class="p-4">
                          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold" :class="{
                            'bg-amber-100 text-amber-700': app.status === 'pending',
                            'bg-blue-100 text-blue-700': app.status === 'reviewed',
                            'bg-indigo-100 text-indigo-700': app.status === 'shortlisted',
                            'bg-rose-100 text-rose-700': app.status === 'rejected',
                            'bg-emerald-100 text-emerald-700': app.status === 'hired'
                          }">
                            {{ app.status.charAt(0).toUpperCase() + app.status.slice(1) }}
                          </span>
                        </td>
                        <td class="p-4 text-sm text-slate-500">
                          {{ new Date(app.created_at).toLocaleDateString() }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Profile Completion & Other Stats -->
              <div class="space-y-6">
                <!-- Profile Completion -->
                <div class="bg-gradient-to-br from-indigo-900 to-indigo-800 rounded-2xl p-6 shadow-lg shadow-indigo-900/20 relative overflow-hidden group">
                  <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-colors"></div>
                  <div class="relative z-10">
                    <h3 class="text-lg font-bold text-white mb-1">Profile Completion</h3>
                    <p class="text-indigo-200 text-sm mb-6">Complete your profile to attract better candidates.</p>
                    
                    <div class="flex justify-between items-end mb-2">
                      <span class="text-4xl font-black text-white">{{ stats.profile_completion }}%</span>
                    </div>
                    <div class="w-full bg-indigo-950/50 rounded-full h-3 mb-4 overflow-hidden border border-white/10">
                      <div class="bg-gradient-to-r from-emerald-400 to-emerald-300 h-3 rounded-full relative" :style="{ width: `${stats.profile_completion}%` }">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                      </div>
                    </div>
                    <NuxtLink v-if="stats.profile_completion < 100" to="/employer/company" class="block text-center w-full bg-white/10 hover:bg-white/20 text-white font-bold py-2 rounded-xl transition-colors backdrop-blur-sm border border-white/10">
                      Complete Profile
                    </NuxtLink>
                  </div>
                </div>

                <AdminWidget title="Unread Messages" :value="stats.new_messages" icon="lucide:message-square" color="blue" />
                <AdminWidget title="Upcoming Interviews" :value="stats.upcoming_interviews" icon="lucide:calendar" color="fuchsia" />
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '~/stores/auth'
import EmployerSidebar from '~/components/EmployerSidebar.vue'
import AdminWidget from '~/components/AdminWidget.vue'

definePageMeta({ layout: false })

const authStore = useAuthStore()
const pending = ref(true)

const stats = ref({
  active_jobs: 0,
  total_applications: 0,
  shortlisted: 0,
  today_interviews: 0,
  upcoming_interviews: 0,
  new_messages: 0,
  unread_notifications: 0,
  profile_completion: 0
})

const recentApplications = ref<any[]>([])

const charts = ref<any>({
  applications: { series: [], categories: [] },
  pipeline: { series: [], labels: [] }
})

// ApexCharts Options
const areaChartOptions = computed(() => ({
  chart: {
    type: 'area',
    fontFamily: 'inherit',
    toolbar: { show: false },
    zoom: { enabled: false }
  },
  colors: ['#4f46e5'], // indigo-600
  dataLabels: { enabled: false },
  stroke: { curve: 'smooth', width: 3 },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.4,
      opacityTo: 0,
      stops: [0, 100]
    }
  },
  xaxis: {
    categories: charts.value.applications.categories,
    axisBorder: { show: false },
    axisTicks: { show: false },
    labels: { style: { colors: '#64748b' } }
  },
  yaxis: {
    labels: { style: { colors: '#64748b' } }
  },
  grid: {
    borderColor: '#f1f5f9',
    strokeDashArray: 4,
  }
}))

const donutChartOptions = computed(() => ({
  chart: { type: 'donut', fontFamily: 'inherit' },
  labels: charts.value.pipeline.labels,
  colors: ['#64748b', '#3b82f6', '#f59e0b', '#ef4444', '#10b981'], // Pending, Reviewed, Shortlisted, Rejected, Hired
  dataLabels: { enabled: false },
  plotOptions: {
    pie: {
      donut: {
        size: '70%',
        labels: {
          show: true,
          name: { color: '#64748b' },
          value: { color: '#0f172a', fontSize: '24px', fontWeight: 'bold' }
        }
      }
    }
  },
  legend: { position: 'bottom' },
  stroke: { show: false }
}))

const fetchStats = async () => {
  pending.value = true
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/employer/dashboard-stats', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    
    // Backend was updated to return stats, charts, recent_applications
    if (res.stats) {
      stats.value = res.stats
      charts.value = res.charts
      recentApplications.value = res.recent_applications || []
    } else {
      // Fallback if backend wasn't updated
      stats.value = res
      recentApplications.value = res.recent_applications || []
    }
  } catch (e) {
    console.error('Failed to fetch stats', e)
  } finally {
    pending.value = false
  }
}

onMounted(() => {
  fetchStats()
})
</script>
