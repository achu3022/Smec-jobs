<template>
  <NuxtLayout name="employer">
    <div class="space-y-6">
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Manage Jobs</h1>
          <p class="text-slate-500 mt-1">View, edit, duplicate, and monitor the performance of your job listings.</p>
        </div>
        <NuxtLink to="/employer/jobs/create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-sm flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Post New Job
        </NuxtLink>
      </div>

      <div v-if="pending" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
      </div>
      
      <div v-else-if="!jobs || jobs.length === 0" class="bg-white rounded-3xl shadow-sm border border-slate-200 p-16 text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-2">No jobs posted yet</h2>
        <p class="text-slate-500 mb-8 max-w-md mx-auto">Create your first job listing to start receiving applications from talented candidates.</p>
        <NuxtLink to="/employer/jobs/create" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition-colors shadow-md shadow-indigo-200">
          Post your first job
        </NuxtLink>
      </div>

      <div v-else class="space-y-4">
        <div v-for="job in jobs" :key="job.id" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col sm:flex-row sm:items-center gap-6 transition-all hover:border-indigo-300">
          
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3 mb-2">
              <h3 class="text-lg font-bold text-slate-900 truncate">{{ job.title }}</h3>
              <span v-if="job.is_urgent" class="bg-rose-100 text-rose-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Urgent</span>
              <span v-if="!job.is_active" class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Paused</span>
              <span v-else class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Active</span>
            </div>
            
            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 mb-4">
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                {{ job.location }}
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                ₹{{ job.salary_min }} - ₹{{ job.salary_max }}
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ new Date(job.created_at).toLocaleDateString() }}
              </span>
            </div>

            <!-- Mini Analytics -->
            <div class="flex items-center gap-6 p-3 bg-slate-50 rounded-xl border border-slate-100 max-w-md">
              <div class="text-center flex-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Views</p>
                <p class="text-lg font-black text-slate-700">{{ job.views_count || 0 }}</p>
              </div>
              <div class="w-px h-8 bg-slate-200"></div>
              <div class="text-center flex-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Applications</p>
                <p class="text-lg font-black text-indigo-600">{{ job.applications?.length || 0 }}</p>
              </div>
            </div>
          </div>
          
          <!-- Actions -->
          <div class="flex flex-col gap-2 min-w-[140px]">
            <NuxtLink :to="`/jobs/${job.id}`" target="_blank" class="w-full text-center bg-white border border-slate-300 hover:border-slate-400 text-slate-700 font-bold py-2 px-4 rounded-lg transition-colors text-sm">
              Preview Job
            </NuxtLink>
            
            <div class="flex gap-2">
              <button @click="toggleStatus(job)" class="flex-1 bg-white border text-center font-bold py-2 px-2 rounded-lg transition-colors text-sm"
                :class="job.is_active ? 'border-amber-200 hover:border-amber-400 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 hover:border-emerald-400 text-emerald-700 hover:bg-emerald-50'">
                {{ job.is_active ? 'Pause' : 'Activate' }}
              </button>
              
              <!-- Dropdown Menu for More Actions -->
              <div class="relative group">
                <button class="w-10 h-10 flex items-center justify-center bg-white border border-slate-300 hover:border-slate-400 text-slate-600 rounded-lg transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10 py-2">
                  <button @click="toggleUrgent(job)" class="w-full text-left px-4 py-2 text-sm font-medium hover:bg-slate-50 transition-colors" :class="job.is_urgent ? 'text-slate-600' : 'text-rose-600'">
                    {{ job.is_urgent ? 'Remove Urgent Tag' : 'Mark as Urgent' }}
                  </button>
                  <button @click="duplicateJob(job)" class="w-full text-left px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Duplicate Job
                  </button>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'

useSeoMeta({
  title: 'Manage Jobs | Employer Dashboard'
})

const authStore = useAuthStore()
const { data: jobs, pending, refresh } = await useFetch<any[]>('/api/employer/jobs', {
  headers: {
    Authorization: authStore.token ? `Bearer ${authStore.token}` : ''
  }
})

const toggleStatus = async (job: any) => {
  try {
    await $fetch(`/api/employer/jobs/${job.id}/toggle-status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    job.is_active = !job.is_active
  } catch (e) {
    alert('Failed to update job status.')
  }
}

const toggleUrgent = async (job: any) => {
  try {
    await $fetch(`/api/employer/jobs/${job.id}/toggle-urgent`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    job.is_urgent = !job.is_urgent
  } catch (e) {
    alert('Failed to update urgency.')
  }
}

const duplicateJob = async (job: any) => {
  if (!confirm('Are you sure you want to duplicate this job?')) return
  try {
    await $fetch(`/api/employer/jobs/${job.id}/duplicate`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    refresh()
  } catch (e) {
    alert('Failed to duplicate job.')
  }
}
</script>
