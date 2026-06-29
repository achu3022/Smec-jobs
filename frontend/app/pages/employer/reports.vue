<template>
  <div class="h-[100dvh] bg-slate-50 flex overflow-hidden">
    <EmployerSidebar />
    <div class="flex-1 ml-64 flex flex-col h-full relative">
      <main class="flex-1 overflow-x-hidden p-6 sm:p-10 overflow-y-auto">
        <div class="max-w-7xl mx-auto space-y-8">
          <div class="flex justify-between items-end">
            <div>
              <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Analytics & Reports</h1>
              <p class="text-slate-500 mt-1">Track the performance of your job postings and applicant pipelines.</p>
            </div>
            <button @click="fetchReports" class="p-2 text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Refresh Data">
              <Icon name="lucide:refresh-cw" class="w-5 h-5" :class="{ 'animate-spin': pending }" />
            </button>
          </div>

          <!-- Loading State -->
          <div v-if="pending" class="flex justify-center py-20">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500"></div>
          </div>
          
          <div v-else class="space-y-6">
            <!-- KPI Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <AdminWidget title="Total Jobs Posted" :value="kpis.total_jobs" icon="lucide:briefcase" color="indigo" />
              <AdminWidget title="Total Applications" :value="kpis.total_applications" icon="lucide:users" color="blue" />
              <AdminWidget title="Total Hires" :value="kpis.total_hires" icon="lucide:check-circle" color="emerald" />
              <AdminWidget title="Hiring Rate" :value="`${kpis.hiring_rate}%`" icon="lucide:trending-up" color="rose" />
            </div>

            <!-- Chart Row -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
              <h3 class="text-lg font-bold text-slate-800 mb-4">Job Popularity (Applications per Job)</h3>
              <ClientOnly>
                <apexchart v-if="chartData.series.length > 0" type="bar" height="350" :options="barChartOptions" :series="[{ name: 'Applications', data: chartData.series }]"></apexchart>
                <div v-else class="h-[350px] flex items-center justify-center bg-slate-50 rounded-xl text-slate-500">
                  No data available for chart.
                </div>
                <template #fallback>
                  <div class="h-[350px] flex items-center justify-center bg-slate-50 rounded-xl">Loading chart...</div>
                </template>
              </ClientOnly>
            </div>

            <!-- Job Performance Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h2 class="text-xl font-bold text-slate-800">Detailed Job Performance</h2>
                <button @click="exportToCSV" :disabled="!jobs.length" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                  <Icon name="lucide:download" class="w-4 h-4" />
                  Export CSV
                </button>
              </div>
              <div v-if="!jobs.length" class="p-10 text-center text-slate-500">
                <p class="font-medium">No jobs found.</p>
              </div>
              <div v-else class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                      <th class="p-4 font-bold">Job Title</th>
                      <th class="p-4 font-bold">Status</th>
                      <th class="p-4 font-bold text-center">Applications</th>
                      <th class="p-4 font-bold text-center">Shortlisted</th>
                      <th class="p-4 font-bold text-center">Hired</th>
                      <th class="p-4 font-bold text-right">Conversion</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    <tr v-for="job in jobs" :key="job.id" class="hover:bg-slate-50/80 transition-colors">
                      <td class="p-4">
                        <p class="font-bold text-sm text-slate-900">{{ job.title }}</p>
                        <p class="text-xs text-slate-500">{{ job.type }} • {{ job.location }}</p>
                      </td>
                      <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold" :class="job.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700'">
                          {{ job.is_active ? 'Active' : 'Closed' }}
                        </span>
                      </td>
                      <td class="p-4 text-center">
                        <span class="font-bold text-slate-700">{{ job.total_applications }}</span>
                      </td>
                      <td class="p-4 text-center">
                        <span class="font-bold text-indigo-600">{{ job.shortlisted_applications }}</span>
                      </td>
                      <td class="p-4 text-center">
                        <span class="font-bold text-emerald-600">{{ job.hired_applications }}</span>
                      </td>
                      <td class="p-4 text-right">
                        <span class="text-sm font-semibold text-slate-500">
                          {{ job.total_applications > 0 ? Math.round((job.hired_applications / job.total_applications) * 100) : 0 }}%
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
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

const kpis = ref({
  total_jobs: 0,
  total_applications: 0,
  total_hires: 0,
  hiring_rate: 0
})
const jobs = ref<any[]>([])
const chartData = ref({
  categories: [],
  series: []
})

// ApexCharts Options for Bar Chart
const barChartOptions = computed(() => ({
  chart: {
    type: 'bar',
    fontFamily: 'inherit',
    toolbar: { show: false },
  },
  colors: ['#6366f1'], // indigo-500
  plotOptions: {
    bar: {
      borderRadius: 4,
      horizontal: false,
      columnWidth: '50%',
    }
  },
  dataLabels: { enabled: false },
  xaxis: {
    categories: chartData.value.categories,
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

const fetchReports = async () => {
  pending.value = true
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/employer/reports', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    
    kpis.value = res.kpis
    chartData.value = res.chart
    jobs.value = res.job_performance
  } catch (e) {
    console.error('Failed to fetch reports', e)
  } finally {
    pending.value = false
  }
}

const exportToCSV = () => {
  if (!jobs.value.length) return
  
  // CSV Headers
  let csvContent = "data:text/csv;charset=utf-8," 
    + "Job Title,Type,Location,Status,Total Applications,Shortlisted,Hired,Conversion Rate\n";
    
  jobs.value.forEach(job => {
    const title = `"${job.title.replace(/"/g, '""')}"`; // Escape quotes
    const type = `"${job.type || ''}"`;
    const location = `"${job.location || ''}"`;
    const status = job.is_active ? 'Active' : 'Closed';
    const applications = job.total_applications || 0;
    const shortlisted = job.shortlisted_applications || 0;
    const hired = job.hired_applications || 0;
    const conversion = applications > 0 ? Math.round((hired / applications) * 100) + '%' : '0%';
    
    const row = [title, type, location, status, applications, shortlisted, hired, conversion].join(",");
    csvContent += row + "\n";
  });
  
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", `job_performance_report_${new Date().toISOString().split('T')[0]}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

onMounted(() => {
  fetchReports()
})
</script>
