<template>
  <div class="w-64 bg-slate-900 text-white flex flex-col h-screen fixed">
    <!-- Branding -->
    <div class="h-20 flex items-center px-8 border-b border-slate-800 bg-slate-950">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-black text-lg shadow-lg">
          E
        </div>
        <div>
          <h1 class="text-xl font-bold tracking-tight text-white leading-none">Employer</h1>
          <p class="text-xs text-indigo-400 font-medium">Dashboard</p>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
      <nav class="space-y-1">
        <template v-for="(item, index) in menuItems" :key="index">
          <NuxtLink :to="item.path" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group relative text-slate-300 hover:text-white hover:bg-slate-800" exact-active-class="bg-indigo-600 text-white shadow-md shadow-indigo-900/50">
            <span class="text-xl opacity-80 group-hover:opacity-100 transition-opacity" :class="{ 'opacity-100': $route.path === item.path }">{{ item.icon }}</span>
            <span class="font-semibold text-sm">{{ item.name }}</span>
            <div v-if="item.badge" class="absolute right-3 bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
              {{ item.badge }}
            </div>
          </NuxtLink>
        </template>
      </nav>
    </div>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-slate-800 bg-slate-950">
      <div class="flex items-center gap-3 mb-4 px-2">
        <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-indigo-400">
          {{ authStore.user?.name?.charAt(0) || '?' }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold truncate text-white">{{ authStore.user?.name }}</p>
          <p class="text-xs text-slate-500 truncate">{{ authStore.user?.email }}</p>
        </div>
      </div>
      <button @click="logout" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-rose-600 hover:text-white text-slate-300 font-bold py-2.5 rounded-lg transition-colors text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        Logout
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const menuItems = [
  { name: 'Dashboard', path: '/employer/dashboard', icon: '📊' },
  { name: 'Company Profile', path: '/employer/company', icon: '🏢' },
  { name: 'Post a Job', path: '/employer/jobs/create', icon: '✨' },
  { name: 'Manage Jobs', path: '/employer/jobs', icon: '💼' },
  { name: 'Applicants', path: '/employer/applicants', icon: '👥', badge: 'New' },
  { name: 'Saved Candidates', path: '/employer/candidates/saved', icon: '⭐' },
  { name: 'Interviews', path: '/employer/interviews', icon: '📅' },
  { name: 'Messages', path: '/employer/messages', icon: '💬' },
  { name: 'Reports', path: '/employer/reports', icon: '📈' },
  { name: 'Settings', path: '/employer/settings', icon: '⚙️' }
]

const logout = async () => {
  if(confirm('Are you sure you want to log out?')) {
    await authStore.logout()
    router.push('/login')
  }
}
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #334155;
  border-radius: 4px;
}
</style>
