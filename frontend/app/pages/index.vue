<template>
  <div class="bg-white min-h-[calc(100vh-64px)]">
    
    <!-- Indeed-style Top Search Bar (Sticky) -->
    <div class="border-b border-slate-200 sticky top-0 bg-white z-20 py-4 px-4 sm:px-6 lg:px-8 shadow-sm">
      <div class="max-w-7xl mx-auto">
        <form @submit.prevent="executeSearch" class="flex flex-col md:flex-row gap-2 justify-center">
          <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" v-model="filters.q" placeholder="Job title, keywords, or company" class="pl-10 block w-full rounded-lg border-slate-300 border-2 py-3 px-4 focus:ring-primary-600 focus:border-primary-600 text-slate-900">
          </div>
          <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            </div>
            <input type="text" v-model="filters.location" placeholder="City, state, zip code, or remote" class="pl-10 block w-full rounded-lg border-slate-300 border-2 py-3 px-4 focus:ring-primary-600 focus:border-primary-600 text-slate-900">
          </div>
          <button type="submit" class="bg-primary-700 hover:bg-primary-800 text-white px-8 py-3 rounded-lg font-bold transition-colors">
            Find jobs
          </button>
        </form>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      
      <!-- Split Layout -->
      <div class="flex flex-col lg:flex-row gap-6 relative">
        
        <!-- Left Column: Job List -->
        <div class="w-full lg:w-[45%] flex-shrink-0">
          <h2 class="text-xl font-bold text-slate-900 mb-4">Jobs for you</h2>
          
          <div v-if="pending" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-700"></div>
          </div>
          
          <div v-else-if="jobs?.data?.length === 0" class="p-8 text-center text-slate-500 border border-slate-200 rounded-xl">
            No jobs found matching your criteria.
          </div>

          <div v-else class="space-y-4">
            <!-- Job Cards -->
            <div
              v-for="job in jobs?.data"
              :key="job.id"
              @click="handleCardClick(job)"
              :class="['border rounded-xl p-5 transition-colors relative block cursor-pointer', selectedJob?.id === job.id ? 'border-primary-600 border-2 shadow-sm bg-blue-50/30' : 'border-slate-300 hover:border-slate-400 bg-white']"
            >
              <div class="flex justify-between items-start mb-1">
                <h3 class="text-lg font-bold text-slate-900">{{ job.title }}</h3>
                <!-- Bookmark Icon placeholder -->
                <button class="text-slate-400 hover:text-slate-600 shrink-0 ml-2" @click.stop.prevent>
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                </button>
              </div>
              <div class="text-base text-slate-700">{{ job.company.name }}</div>
              <div class="text-sm text-slate-600 mb-3">{{ job.location }}</div>

              <!-- Tags -->
              <div class="flex flex-wrap gap-2 mb-4">
                <span v-if="job.salary_min" class="bg-slate-100 text-slate-800 text-xs font-bold px-2 py-1 rounded flex items-center">
                  <svg class="w-3 h-3 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                  ₹{{ (job.salary_min).toLocaleString() }} - ₹{{ (job.salary_max).toLocaleString() }} a month
                </span>
                <span class="bg-slate-100 text-slate-800 text-xs font-bold px-2 py-1 rounded">{{ job.job_type }}</span>
                <span v-if="job.is_remote" class="bg-slate-100 text-slate-800 text-xs font-bold px-2 py-1 rounded">Remote</span>
              </div>

              <!-- Snippet -->
              <div class="text-sm text-slate-600 line-clamp-2 pl-4 border-l-2 border-slate-200">
                {{ job.description }}
              </div>

              <div class="flex items-center justify-between mt-4">
                <div class="text-xs text-slate-500">
                  Posted recently
                </div>
                <div v-if="isMobile">
                  <span class="bg-primary-700 text-white text-sm font-bold py-1.5 px-4 rounded-lg inline-block">
                    Apply now
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Pagination Controls -->
            <div v-if="jobs?.last_page > 1" class="flex justify-center gap-2 mt-8 py-4">
              <button class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-100 disabled:opacity-50" :disabled="jobs.current_page === 1" @click="page--; executeSearch()">Previous</button>
              <button class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-100 disabled:opacity-50" :disabled="jobs.current_page === jobs.last_page" @click="page++; executeSearch()">Next</button>
            </div>
          </div>
        </div>

        <!-- Right Column: Job Detail Pane (Sticky) -->
        <div class="hidden lg:block w-full lg:w-[55%]">
          <div class="sticky top-[100px] border border-slate-300 rounded-xl bg-white overflow-hidden flex flex-col" style="height: calc(100vh - 120px);">
            
            <div v-if="!selectedJob" class="h-full flex items-center justify-center p-12 text-slate-500 bg-slate-50">
              Select a job to view details
            </div>

            <div v-else class="h-full flex flex-col">
              <!-- Detail Header -->
              <div class="p-6 border-b border-slate-200 shrink-0 bg-white">
                <h2 class="text-2xl font-bold text-slate-900 mb-1">{{ selectedJob.title }}</h2>
                <div class="text-lg text-slate-700 mb-1">
                  <span class="underline hover:text-primary-600 cursor-pointer">{{ selectedJob.company.name }}</span>
                </div>
                <div class="text-slate-600 mb-4">{{ selectedJob.location }}</div>
                
                <div class="flex items-center gap-3">
                  <button v-if="selectedJob.has_applied" disabled class="bg-green-100 text-green-800 font-bold py-2.5 px-6 rounded-lg cursor-not-allowed">
                    Applied
                  </button>
                  <NuxtLink v-else :to="'/jobs/' + selectedJob.uuid" class="bg-primary-700 hover:bg-primary-800 text-white font-bold py-2.5 px-6 rounded-lg transition-colors inline-block text-center">
                    Apply now
                  </NuxtLink>
                  <button @click="toggleSaveJob(selectedJob)" :disabled="isSavingJob" class="border-2 border-slate-200 hover:border-primary-600 p-2.5 rounded-lg font-bold transition-colors" :class="selectedJob.is_saved ? 'bg-primary-50 text-primary-700 border-primary-200' : 'bg-slate-50 text-slate-800 hover:bg-primary-50 hover:text-primary-700'">
                    <svg v-if="selectedJob.is_saved" class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                    <svg v-else class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                  </button>
                </div>
                <div v-if="applyMessage" :class="['mt-3 text-sm font-bold', applyError ? 'text-red-600' : 'text-green-600']">
                  {{ applyMessage }}
                </div>
              </div>

              <!-- Detail Scrollable Body -->
              <div class="p-6 overflow-y-auto flex-1 bg-white">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Job details</h3>
                
                <div class="space-y-6">
                  <!-- Pay Section -->
                  <div v-if="selectedJob.salary_min">
                    <div class="flex items-center text-slate-900 font-bold mb-2">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                      Pay
                    </div>
                    <span class="bg-green-50 text-green-800 text-sm font-bold px-3 py-1.5 rounded inline-block">
                      ₹{{ (selectedJob.salary_min).toLocaleString() }} - ₹{{ (selectedJob.salary_max).toLocaleString() }} a month
                    </span>
                  </div>

                  <!-- Job Type Section -->
                  <div>
                    <div class="flex items-center text-slate-900 font-bold mb-2">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                      Job type
                    </div>
                    <span class="bg-slate-100 text-slate-800 text-sm font-bold px-3 py-1.5 rounded inline-block">
                      {{ selectedJob.job_type }}
                    </span>
                  </div>

                  <hr class="border-slate-200">

                  <!-- Full Description -->
                  <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Full job description</h3>
                    <div class="prose prose-slate max-w-none text-slate-800 whitespace-pre-wrap">
                      {{ selectedJob.description }}
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch, onMounted, onUnmounted, computed } from 'vue'
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()
const router = useRouter()

// Detect mobile (below lg = 1024px) — drives card link vs click behaviour
const isMobile = ref(false)
const checkMobile = () => { isMobile.value = window.innerWidth < 1024 }
onMounted(() => { checkMobile(); window.addEventListener('resize', checkMobile) })
onUnmounted(() => { window.removeEventListener('resize', checkMobile) })

const filters = reactive({
  q: '',
  location: '',
  type: ''
})
const page = ref(1)

const { data: jobs, pending, execute } = await useFetch(() => '/api/jobs/search', {
  params: computed(() => ({
    q: filters.q,
    location: filters.location,
    type: filters.type,
    page: page.value
  })),
  headers: {
    Authorization: authStore.token ? `Bearer ${authStore.token}` : ''
  },
  watch: false
})

const selectedJob = ref<any>(null)

// When jobs load or change, auto-select the first job if none is selected
watch(jobs, (newJobs) => {
  if (newJobs?.data?.length > 0) {
    selectedJob.value = newJobs.data[0]
  } else {
    selectedJob.value = null
  }
}, { immediate: true })

const executeSearch = () => {
  execute()
}

const handleCardClick = (job: any) => {
  if (isMobile.value) {
    router.push(`/jobs/${job.uuid}`)
  } else {
    selectedJob.value = job
  }
}

// Applying for job logic
const applyMessage = ref('')
const applyError = ref(false)

const applyForJob = async (jobId: number) => {
  if (!authStore.isAuthenticated) {
    applyError.value = true
    applyMessage.value = 'Please log in to apply.'
    return
  }
  
  if (authStore.user?.role !== 'applicant') {
    applyError.value = true
    applyMessage.value = 'Only applicants can apply for jobs.'
    return
  }

  applyMessage.value = 'Applying...'
  applyError.value = false

  try {
    await $fetch(`/api/jobs/${jobId}/apply`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    })
    applyMessage.value = 'Successfully applied! Track it in your Dashboard.'
    applyError.value = false
  } catch (e: any) {
    applyError.value = true
    applyMessage.value = e.data?.message || 'Failed to apply.'
  }
}

const isSavingJob = ref(false)

const toggleSaveJob = async (job: any) => {
  if (!authStore.isAuthenticated) {
    applyError.value = true
    applyMessage.value = 'Please log in to save jobs.'
    return
  }

  isSavingJob.value = true
  try {
    const res: any = await $fetch(`/api/applicant/saved-jobs/${job.id}`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    })
    job.is_saved = (res.status === 'saved')
  } catch (e: any) {
    console.error('Failed to save job', e)
  } finally {
    isSavingJob.value = false
  }
}

useSeoMeta({
  title: 'Job Search | SMECJobs',
})
</script>
