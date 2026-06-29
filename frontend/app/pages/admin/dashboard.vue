<template>
  <div class="flex h-screen bg-slate-50 overflow-hidden">
    <AdminSidebar />
    <div class="flex-1 p-8 overflow-y-auto">
      <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex justify-between items-end">
          <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h1>
            <p class="text-slate-500 mt-1">Platform analytics and recent activities.</p>
          </div>
          <button @click="fetchStats" class="p-2 text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors" title="Refresh Data">
            <Icon name="lucide:refresh-cw" class="w-5 h-5" :class="{ 'animate-spin': pending }" />
          </button>
        </div>
        
        <div v-if="pending" class="flex justify-center py-20">
          <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-500"></div>
        </div>

        <div v-else class="space-y-6">
          <!-- Stats Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <AdminWidget title="Total Job Seekers" :value="stats.jobSeekers" icon="lucide:users" color="blue" />
            <AdminWidget title="Total Employers" :value="stats.employers" icon="lucide:building-2" color="indigo" />
            <AdminWidget title="Active Jobs" :value="stats.activeJobs" icon="lucide:briefcase" color="emerald" />
            <AdminWidget title="New Registrations" :value="stats.newRegistrations" icon="lucide:user-plus" color="amber" />
          </div>

          <!-- Charts Row -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Registration Trend -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-2">
              <h3 class="text-lg font-bold text-slate-800 mb-4">Registration Trend (Last 7 Days)</h3>
              <ClientOnly>
                <apexchart type="area" height="300" :options="areaChartOptions" :series="charts.registrations.series"></apexchart>
                <template #fallback>
                  <div class="h-[300px] flex items-center justify-center bg-slate-50 rounded-xl">Loading chart...</div>
                </template>
              </ClientOnly>
            </div>
            
            <!-- Application Status Distribution -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
              <h3 class="text-lg font-bold text-slate-800 mb-4">Application Status</h3>
              <ClientOnly>
                <apexchart type="donut" height="300" :options="donutChartOptions" :series="charts.applications.series"></apexchart>
                <template #fallback>
                  <div class="h-[300px] flex items-center justify-center bg-slate-50 rounded-xl">Loading chart...</div>
                </template>
              </ClientOnly>
            </div>
          </div>

          <!-- Secondary Stats & Tables Row -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Users -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 lg:col-span-2 overflow-hidden">
              <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Recent Registrations</h3>
                <NuxtLink to="/admin/users" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">View All</NuxtLink>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                      <th class="p-4 font-bold">User</th>
                      <th class="p-4 font-bold">Role</th>
                      <th class="p-4 font-bold">Joined</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    <tr v-for="user in recentUsers" :key="user.id" class="hover:bg-slate-50/80 transition-colors">
                      <td class="p-4">
                        <div class="flex items-center gap-3">
                          <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs uppercase">
                            {{ user.name.charAt(0) }}
                          </div>
                          <div>
                            <p class="font-bold text-sm text-slate-800">{{ user.name }}</p>
                            <p class="text-xs text-slate-500">{{ user.email }}</p>
                          </div>
                        </div>
                      </td>
                      <td class="p-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold" 
                          :class="user.role === 'employer' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700'">
                          {{ user.role }}
                        </span>
                      </td>
                      <td class="p-4 text-sm text-slate-600">
                        {{ new Date(user.created_at).toLocaleDateString() }}
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div v-if="!recentUsers.length" class="p-8 text-center text-slate-500">
                  No recent registrations.
                </div>
              </div>
            </div>

            <!-- More Stats -->
            <div class="space-y-6">
              <AdminWidget title="Applications Today" :value="stats.applicationsToday" icon="lucide:file-text" color="rose" />
              <AdminWidget title="Course Enquiries" :value="stats.courseEnquiries" icon="lucide:graduation-cap" color="fuchsia" />
              <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-lg shadow-slate-900/20 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-colors"></div>
                <div class="flex items-center gap-4 mb-4 relative z-10">
                  <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <Icon name="lucide:indian-rupee" class="w-6 h-6 text-emerald-400" />
                  </div>
                  <div>
                    <h3 class="font-bold text-slate-300">Total Revenue</h3>
                    <p class="text-xs text-slate-400">Lifetime earnings</p>
                  </div>
                </div>
                <p class="text-4xl font-black relative z-10">{{ stats.revenue }}</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import AdminSidebar from '~/components/AdminSidebar.vue'
import AdminWidget from '~/components/AdminWidget.vue'
import { useAuthStore } from '~/stores/auth'

definePageMeta({ layout: false })

const pending = ref(true)

const stats = ref({
  jobSeekers: 0,
  employers: 0,
  activeJobs: 0,
  applicationsToday: 0,
  newRegistrations: 0,
  revenue: '₹0',
  courseEnquiries: 0,
  recentActivities: 0,
})

const charts = ref<any>({
  registrations: { series: [], categories: [] },
  applications: { series: [], labels: [] }
})

const recentUsers = ref<any[]>([])

// ApexCharts Options
const areaChartOptions = computed(() => ({
  chart: {
    type: 'area',
    fontFamily: 'inherit',
    toolbar: { show: false },
    zoom: { enabled: false }
  },
  colors: ['#10b981'], // emerald-500
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
    categories: charts.value.registrations.categories,
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
  labels: charts.value.applications.labels,
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
    const res: any = await $fetch('http://127.0.0.1:8000/api/admin/dashboard', {
      headers: { Authorization: `Bearer ${useAuthStore().token}` }
    })
    
    stats.value = res.stats
    charts.value = res.charts
    recentUsers.value = res.recentUsers
  } catch (e) {
    console.error('Failed to load admin stats', e)
  } finally {
    pending.value = false
  }
}

onMounted(() => {
  fetchStats()
})
</script>
