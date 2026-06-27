<template>
  <div>
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-slate-900">Platform Overview</h2>
      <p class="text-slate-600 mt-1">Live metrics from the SMECJobs database.</p>
    </div>

    <div v-if="pending" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
    </div>
    
    <div v-else-if="error" class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100">
      Failed to load statistics. Please ensure you have admin privileges.
    </div>

    <div v-else>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
          <div class="flex items-center text-primary-600 mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
          </div>
          <h3 class="text-slate-500 text-sm font-medium uppercase tracking-wider">Total Users</h3>
          <p class="text-4xl font-bold text-slate-900 mt-2">{{ stats?.total_users || 0 }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
          <div class="flex items-center text-blue-500 mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
          </div>
          <h3 class="text-slate-500 text-sm font-medium uppercase tracking-wider">Active Jobs</h3>
          <p class="text-4xl font-bold text-slate-900 mt-2">{{ stats?.total_jobs || 0 }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
          <div class="flex items-center text-purple-500 mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          </div>
          <h3 class="text-slate-500 text-sm font-medium uppercase tracking-wider">Applications Submitted</h3>
          <p class="text-4xl font-bold text-slate-900 mt-2">{{ stats?.total_applications || 0 }}</p>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50">
          <h3 class="text-lg font-bold text-slate-900">Recent Users</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-white">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Joined</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
              <tr v-if="usersPending">
                <td colspan="4" class="px-6 py-4 text-center text-sm text-slate-500">Loading users...</td>
              </tr>
              <tr v-else-if="!users?.data?.length">
                <td colspan="4" class="px-6 py-4 text-center text-sm text-slate-500">No users found.</td>
              </tr>
              <tr v-else v-for="user in users.data" :key="user.id" class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                      {{ user.name.charAt(0) }}
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-slate-900">{{ user.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ user.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                        :class="user.role === 'admin' ? 'bg-purple-100 text-purple-800' : (user.role === 'employer' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800')">
                    {{ user.role }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ new Date(user.created_at).toLocaleDateString() }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

const { data: stats, pending, error } = await useFetch('http://127.0.0.1:8000/api/admin/stats', {
  headers: {
    Authorization: `Bearer ${authStore.token}`
  },
  server: false
})

const { data: users, pending: usersPending } = await useFetch('http://127.0.0.1:8000/api/admin/users', {
  headers: {
    Authorization: `Bearer ${authStore.token}`
  },
  server: false
})
</script>
