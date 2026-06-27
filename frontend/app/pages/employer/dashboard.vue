<template>
  <NuxtLayout name="employer">
    <div class="space-y-8">
      <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Overview</h1>
        <p class="text-slate-500 mt-1">Here is what's happening with your job postings today.</p>
      </div>

      <!-- Stats Grid -->
      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="i in 4" :key="i" class="bg-white rounded-2xl p-6 border border-slate-200 animate-pulse h-32"></div>
      </div>
      
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Jobs -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-indigo-300 hover:shadow-md transition-all">
          <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Active Jobs</p>
            <h3 class="text-3xl font-black text-slate-900">{{ stats?.active_jobs || 0 }}</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
          </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-emerald-300 hover:shadow-md transition-all">
          <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Total Applications</p>
            <h3 class="text-3xl font-black text-slate-900">{{ stats?.total_applications || 0 }}</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          </div>
        </div>

        <!-- Shortlisted -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-amber-300 hover:shadow-md transition-all">
          <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Shortlisted</p>
            <h3 class="text-3xl font-black text-slate-900">{{ stats?.shortlisted || 0 }}</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
          </div>
        </div>

        <!-- Today's Interviews -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-rose-300 hover:shadow-md transition-all">
          <div>
            <p class="text-sm font-semibold text-slate-500 mb-1">Interviews Today</p>
            <h3 class="text-3xl font-black text-slate-900">{{ stats?.today_interviews || 0 }}</h3>
          </div>
          <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
          </div>
        </div>
      </div>

      <!-- Recent Applications & Suggested Actions -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Applications -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">Recent Applications</h2>
            <NuxtLink to="/employer/applicants" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">View All</NuxtLink>
          </div>
          <div v-if="pending" class="p-10 text-center text-slate-500">Loading...</div>
          <div v-else-if="!stats?.recent_applications?.length" class="p-10 text-center text-slate-500">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <p class="font-medium">No recent applications found.</p>
            <p class="text-sm mt-1">Post a new job to start receiving applications.</p>
            <NuxtLink to="/employer/jobs/create" class="inline-block mt-4 bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg">Post a Job</NuxtLink>
          </div>
          <div v-else>
            <ul class="divide-y divide-slate-100">
              <li v-for="app in stats.recent_applications" :key="app.id" class="p-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 bg-slate-200 rounded-full overflow-hidden flex-shrink-0 flex items-center justify-center font-bold text-slate-500">
                    {{ app.user?.name?.charAt(0) || 'U' }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ app.user?.name }}</p>
                    <p class="text-xs text-slate-500 truncate">Applied for <span class="font-semibold text-slate-700">{{ app.job?.title }}</span></p>
                  </div>
                  <div class="text-right">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      New
                    </span>
                    <p class="text-xs text-slate-400 mt-1">{{ new Date(app.created_at).toLocaleDateString() }}</p>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <!-- Secondary Stats & Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gradient-to-br from-indigo-900 to-slate-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden">
          <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
          <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl"></div>
          
          <div class="relative z-10 space-y-4">
            <h2 class="text-xl font-bold mb-4">Account Status</h2>
            
            <div class="flex items-center gap-4 bg-white/10 p-4 rounded-xl">
              <div class="flex-1">
                <p class="text-sm font-medium text-indigo-200 mb-1">Profile Completion</p>
                <div class="w-full bg-white/20 rounded-full h-2.5">
                  <div class="bg-indigo-400 h-2.5 rounded-full" :style="{ width: (stats?.profile_completion || 0) + '%' }"></div>
                </div>
              </div>
              <span class="text-lg font-bold">{{ stats?.profile_completion || 0 }}%</span>
            </div>

            <div class="flex items-center justify-between p-4 bg-white/10 rounded-xl">
              <div>
                <p class="font-bold">Unread Messages</p>
                <p class="text-xs text-indigo-200">From applicants</p>
              </div>
              <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center font-bold text-lg">
                {{ stats?.new_messages || 0 }}
              </div>
            </div>
          </div>

          <div class="relative z-10 space-y-4">
            <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
            
            <NuxtLink to="/employer/jobs/create" class="flex items-center gap-3 p-3 bg-white/10 hover:bg-white/20 rounded-xl transition-colors backdrop-blur-sm">
              <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
              </div>
              <div>
                <p class="font-bold text-sm">Post a New Job</p>
                <p class="text-xs text-indigo-200">Reach thousands of candidates</p>
              </div>
            </NuxtLink>
            
            <NuxtLink to="/employer/interviews" class="flex items-center gap-3 p-3 bg-white/10 hover:bg-white/20 rounded-xl transition-colors backdrop-blur-sm">
              <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center relative">
                <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span v-if="stats?.upcoming_interviews" class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">{{ stats.upcoming_interviews }}</span>
              </div>
              <div>
                <p class="font-bold text-sm">Upcoming Interviews</p>
                <p class="text-xs text-indigo-200">View your schedule</p>
              </div>
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

useSeoMeta({
  title: 'Employer Dashboard | SMECJobs'
})

const { data: stats, pending } = await useFetch<any>('http://127.0.0.1:8000/api/employer/dashboard-stats', {
  headers: {
    Authorization: authStore.token ? `Bearer ${authStore.token}` : ''
  }
})
</script>
