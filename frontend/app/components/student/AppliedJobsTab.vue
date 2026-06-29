<template>
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-5xl mx-auto">
    <h3 class="text-2xl font-bold text-slate-900 mb-6">Application History</h3>
    <div v-if="pending" class="p-12 flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600"></div>
    </div>
    <ul v-else-if="applications?.length" class="divide-y divide-slate-100">
      <li v-for="app in applications" :key="app.id" class="p-6 sm:px-8 hover:bg-slate-50 transition-colors group border border-slate-100 rounded-2xl mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <NuxtLink :to="'/jobs/' + app.job?.uuid" class="text-lg font-bold text-slate-900 group-hover:text-primary-600 mb-1">
              {{ app.job?.title }}
            </NuxtLink>
            <p class="text-sm font-semibold text-slate-600">{{ app.job?.company?.name }}</p>
            
            <!-- Interview Information -->
            <div v-if="app.interviews && app.interviews.length > 0" class="mt-3 text-sm text-primary-800 bg-primary-50 px-4 py-3 rounded-xl inline-flex flex-col gap-1 border border-primary-100 shadow-sm">
              <span class="font-bold flex items-center gap-1.5">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Interview Scheduled: {{ new Date(app.interviews[app.interviews.length - 1].scheduled_at).toLocaleString() }}
              </span>
              <span v-if="app.interviews[app.interviews.length - 1].interviewer_name" class="flex items-center gap-1.5 opacity-90 mt-1">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Interviewer: {{ app.interviews[app.interviews.length - 1].interviewer_name }}
              </span>
              <span v-if="app.interviews[app.interviews.length - 1].location_url" class="flex items-center gap-1.5 opacity-90 mt-1">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                <a :href="app.interviews[app.interviews.length - 1].location_url" target="_blank" class="underline hover:text-primary-700">Meeting Link / Location</a>
              </span>
            </div>
          </div>
          <div class="flex items-center justify-end">
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide shadow-sm"
                  :class="['new', 'Pending'].includes(app.status) ? 'bg-yellow-50 text-yellow-700' : (['hired', 'Accepted'].includes(app.status) ? 'bg-green-50 text-green-700' : (['rejected'].includes(app.status) ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700'))">
              {{ app.status.replace('_', ' ') }}
            </span>
          </div>
        </div>
      </li>
    </ul>
    <div v-else class="p-16 text-center">
      <p class="text-slate-500 mb-4">No applications yet.</p>
      <NuxtLink to="/" class="font-bold text-primary-600 hover:text-primary-700">Browse Jobs</NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
const authStore = useAuthStore()

const { data: applications, pending } = await useFetch<any[]>('/api/applicant/applications', {
  headers: { Authorization: `Bearer ${authStore.token}` }
})
</script>
