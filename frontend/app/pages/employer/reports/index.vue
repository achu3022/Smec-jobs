<template>
  <NuxtLayout name="employer">
    <div class="space-y-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Recruitment Reports</h1>
          <p class="text-slate-500 mt-1">Analyze your hiring pipeline and job performance.</p>
        </div>
        <div class="flex gap-4">
          <button @click="downloadReport" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 hover:border-indigo-400 text-slate-700 hover:text-indigo-600 font-bold rounded-xl shadow-sm transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export to Excel
          </button>
        </div>
      </div>

      <!-- High-level Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
          <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-600">Active Jobs</h3>
          </div>
          <p class="text-3xl font-black text-slate-900">{{ stats?.total_jobs || 0 }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
          <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-600">Total Applicants</h3>
          </div>
          <p class="text-3xl font-black text-slate-900">{{ stats?.total_applications || 0 }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
          <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-600">Shortlisted</h3>
          </div>
          <p class="text-3xl font-black text-slate-900">{{ stats?.shortlisted || 0 }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
          <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="font-bold text-slate-600">Interviews</h3>
          </div>
          <p class="text-3xl font-black text-slate-900">{{ stats?.interviews_scheduled || 0 }}</p>
        </div>
      </div>

      <!-- Applicant Funnel & Top Jobs -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
          <h2 class="text-xl font-bold text-slate-900 mb-6">Recruitment Funnel</h2>
          
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3 w-48">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span class="font-semibold text-slate-700">Total Applicants</span>
              </div>
              <div class="flex-1 mx-4">
                <div class="h-4 bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full bg-blue-500" style="width: 100%"></div>
                </div>
              </div>
              <span class="font-bold text-slate-900 w-12 text-right">{{ stats?.total_applications || 0 }}</span>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3 w-48">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <span class="font-semibold text-slate-700">Shortlisted</span>
              </div>
              <div class="flex-1 mx-4">
                <div class="h-4 bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full bg-emerald-500" :style="`width: ${stats?.total_applications ? Math.round((stats?.shortlisted / stats?.total_applications) * 100) : 0}%`"></div>
                </div>
              </div>
              <span class="font-bold text-slate-900 w-12 text-right">{{ stats?.shortlisted || 0 }}</span>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3 w-48">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span class="font-semibold text-slate-700">Interviewing</span>
              </div>
              <div class="flex-1 mx-4">
                <div class="h-4 bg-slate-100 rounded-full overflow-hidden">
                  <div class="h-full bg-amber-500" :style="`width: ${stats?.total_applications ? Math.round((stats?.interviews_scheduled / stats?.total_applications) * 100) : 0}%`"></div>
                </div>
              </div>
              <span class="font-bold text-slate-900 w-12 text-right">{{ stats?.interviews_scheduled || 0 }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col items-center justify-center text-center">
          <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
          </div>
          <h3 class="text-lg font-bold text-slate-700 mb-1">More Advanced Analytics</h3>
          <p class="text-sm text-slate-500">Detailed job performance and candidate sourcing metrics are available in the upcoming Enterprise tier.</p>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'

useSeoMeta({
  title: 'Reports | Employer Dashboard'
})

const authStore = useAuthStore()

const { data: stats } = await useFetch<any>('/api/employer/dashboard-stats', {
  headers: { Authorization: `Bearer ${authStore.token}` }
})

const downloadReport = () => {
  alert('Downloading Excel report... (This would trigger an API endpoint returning a spreadsheet stream)')
}
</script>
