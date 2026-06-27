<template>
  <div class="space-y-6 max-w-5xl mx-auto">
    <div v-if="pendingSaved" class="bg-white rounded-3xl p-12 flex justify-center items-center shadow-sm border border-slate-100">
      <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600"></div>
    </div>
    <div v-else-if="!savedJobs?.length" class="bg-white rounded-3xl border border-slate-100 p-16 text-center shadow-sm">
      <div class="w-20 h-20 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-500">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
      </div>
      <h3 class="text-xl font-bold text-slate-900 mb-2">No Saved Jobs Yet</h3>
      <p class="text-slate-500 max-w-md mx-auto mb-6">You haven't saved any jobs yet. Browse our job listings and click "Save Job" to keep track of interesting opportunities.</p>
      <NuxtLink to="/" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-xl transition-colors">Browse Jobs</NuxtLink>
    </div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div v-for="job in savedJobs" :key="job.id" class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 hover:border-primary-300 transition-colors flex flex-col h-full">
        <div class="flex justify-between items-start mb-4 gap-4">
          <div>
            <h3 class="text-lg font-bold text-slate-900 line-clamp-2 leading-tight mb-1">{{ job.title }}</h3>
            <p class="text-sm text-slate-600 font-medium">{{ job.company?.name || 'Unknown Company' }}</p>
          </div>
          <button @click="unsaveJob(job.id)" class="text-slate-400 hover:text-red-500 p-2 -mr-2 -mt-2 transition-colors bg-slate-50 rounded-full" title="Remove from saved jobs">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
          </button>
        </div>
        
        <div class="flex flex-wrap gap-2 mb-6">
          <span class="bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-full flex items-center">
            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            {{ job.location }}
          </span>
          <span class="bg-primary-50 text-primary-700 text-xs font-bold px-3 py-1 rounded-full flex items-center">
            <svg class="w-3.5 h-3.5 mr-1 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            {{ job.job_type }}
          </span>
        </div>
        
        <div class="mt-auto flex gap-3 pt-4 border-t border-slate-100">
          <NuxtLink :to="`/jobs/${job.uuid}`" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white text-center font-bold py-2.5 px-4 rounded-xl text-sm transition-colors">
            Apply Now
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
const authStore = useAuthStore()

const { data: savedJobs, pending: pendingSaved, refresh: refreshSaved } = await useFetch<any[]>('http://127.0.0.1:8000/api/applicant/saved-jobs', {
  headers: { Authorization: `Bearer ${authStore.token}` }
})

const unsaveJob = async (jobId: number) => {
  try {
    await $fetch(`http://127.0.0.1:8000/api/jobs/${jobId}/save`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    refreshSaved()
  } catch (e) {
    console.error('Failed to unsave job', e)
  }
}
</script>
