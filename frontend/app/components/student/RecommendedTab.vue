<template>
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
      <div>
        <h3 class="text-2xl font-bold text-slate-900">Recommended Jobs</h3>
        <p class="text-slate-500 mt-1">Jobs perfectly matched to your Job Alert preferences.</p>
      </div>
    </div>
    
    <div v-if="pending" class="py-12 flex justify-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
    </div>
    
    <div v-else-if="jobs.length === 0" class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
      <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-400">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
      </div>
      <h4 class="text-lg font-bold text-slate-900 mb-2">No recommendations found</h4>
      <p class="text-slate-500 mb-6 max-w-md mx-auto">We couldn't find any jobs matching your current job alerts right now. Try broadening your Job Alert settings.</p>
    </div>
    
    <div v-else class="grid grid-cols-1 gap-4">
      <div v-for="job in jobs" :key="job.id" class="border border-slate-200 rounded-2xl p-6 hover:border-primary-300 hover:shadow-md transition-all group bg-white flex flex-col md:flex-row md:items-center gap-6">
        
        <!-- Company Logo -->
        <div class="w-16 h-16 shrink-0 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center overflow-hidden">
          <img v-if="job.company?.logo" :src="job.company.logo" :alt="job.company?.name" class="w-full h-full object-cover">
          <span v-else class="text-slate-400 font-bold text-xl">{{ job.company?.name?.charAt(0) || 'J' }}</span>
        </div>
        
        <!-- Job Info -->
        <div class="flex-1 min-w-0">
          <NuxtLink :to="`/jobs/${job.uuid}`" class="block group-hover:text-primary-600 transition-colors">
            <h4 class="font-bold text-slate-900 text-lg truncate mb-1">{{ job.title }}</h4>
          </NuxtLink>
          <div class="flex items-center text-slate-600 text-sm gap-2 truncate">
            <span class="font-semibold text-slate-800">{{ job.company?.name }}</span>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <span>{{ job.location }}</span>
          </div>
          
          <div class="flex flex-wrap gap-2 mt-3">
            <span v-if="job.employment_type" class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg">{{ job.employment_type }}</span>
            <span v-if="job.salary_range" class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-lg border border-green-100">{{ job.salary_range }}</span>
          </div>
        </div>
        
        <!-- Actions -->
        <div class="flex md:flex-col gap-3 shrink-0">
          <NuxtLink :to="`/jobs/${job.uuid}`" class="flex-1 text-center bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors">
            Apply Now
          </NuxtLink>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()
const pending = ref(true)
const jobs = ref<any[]>([])

const fetchRecommendedJobs = async () => {
  pending.value = true
  try {
    const data: any = await $fetch('/api/applicant/recommended-jobs', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    jobs.value = data.jobs || []
  } catch (e) {
    console.error('Failed to load recommended jobs', e)
  } finally {
    pending.value = false
  }
}

onMounted(() => {
  fetchRecommendedJobs()
})
</script>