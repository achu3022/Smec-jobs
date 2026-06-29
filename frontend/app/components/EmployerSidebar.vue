<template>
  <div class="w-64 bg-slate-950 text-slate-300 flex flex-col h-full fixed border-r border-slate-800/50 shadow-2xl z-50">
    <!-- Branding -->
    <div class="h-20 flex items-center px-8 border-b border-slate-800/50 bg-slate-950/50 backdrop-blur-md relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-transparent"></div>
      <div class="flex items-center gap-3 relative z-10">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-black text-lg text-white shadow-lg shadow-indigo-500/20">
          E
        </div>
        <div>
          <h1 class="text-xl font-bold tracking-tight text-white leading-none">Employer</h1>
          <p class="text-xs text-indigo-400 font-medium mt-0.5">Dashboard</p>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar min-h-0">
      <nav class="space-y-1">
        <template v-for="(item, index) in menuItems" :key="index">
          <NuxtLink :to="item.path" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group relative text-slate-400 hover:text-white hover:bg-slate-800/50 hover:shadow-sm" exact-active-class="bg-gradient-to-r from-indigo-600/20 to-transparent text-white border-l-2 border-indigo-500 rounded-l-none rounded-r-xl">
            <span class="opacity-70 group-hover:opacity-100 transition-opacity" :class="{ 'opacity-100 text-indigo-400': $route.path === item.path }">
              <Icon :name="item.icon" class="w-5 h-5" />
            </span>
            <span class="font-semibold text-sm">{{ item.name }}</span>
            <div v-if="item.name === 'Messages' && stats?.new_messages > 0" class="absolute right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-lg">
              {{ stats.new_messages }}
            </div>
            <div v-else-if="item.badge && item.name !== 'Messages'" class="absolute right-3 bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
              {{ item.badge }}
            </div>
          </NuxtLink>
        </template>
      </nav>
    </div>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-slate-800/50 bg-slate-950/80 backdrop-blur-sm">
      <div class="flex items-center gap-3 mb-4 px-2">
        <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700/50 flex items-center justify-center font-bold text-indigo-400 shadow-inner">
          {{ authStore.user?.name?.charAt(0) || '?' }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold truncate text-white">{{ authStore.user?.name }}</p>
          <p class="text-[11px] font-medium text-slate-500 truncate">{{ authStore.user?.email }}</p>
        </div>
      </div>
      <button @click="logout" class="w-full flex items-center justify-center gap-2 bg-slate-800/50 hover:bg-rose-500/10 hover:text-rose-400 text-slate-400 border border-transparent hover:border-rose-500/20 font-bold py-2.5 rounded-xl transition-all text-sm">
        <Icon name="lucide:log-out" class="w-4 h-4" />
        Logout
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useRouter } from 'vue-router'
import { onMounted, onUnmounted, ref } from 'vue'

const authStore = useAuthStore()
const router = useRouter()

const { data: stats, refresh } = await useFetch<any>('/api/employer/dashboard-stats', {
  headers: { Authorization: `Bearer ${authStore.token}` },
  server: false,
  immediate: false // don't fetch if unauthenticated
})

let pollInterval: any
onMounted(() => {
  if (authStore.isAuthenticated && authStore.user?.role === 'employer') {
    refresh()
    pollInterval = setInterval(() => {
      refresh()
    }, 5000)
  }
})

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval)
})

const menuItems = [
  { name: 'Dashboard', path: '/employer/dashboard', icon: 'lucide:layout-dashboard' },
  { name: 'Company Profile', path: '/employer/company', icon: 'lucide:building-2' },
  { name: 'Post a Job', path: '/employer/jobs/create', icon: 'lucide:plus-circle' },
  { name: 'Manage Jobs', path: '/employer/jobs', icon: 'lucide:briefcase' },
  { name: 'Applicants', path: '/employer/applicants', icon: 'lucide:users', badge: 'New' },
  { name: 'Saved Candidates', path: '/employer/candidates/saved', icon: 'lucide:star' },
  { name: 'Interviews', path: '/employer/interviews', icon: 'lucide:calendar' },
  { name: 'Messages', path: '/employer/messages', icon: 'lucide:message-square' },
  { name: 'Reports', path: '/employer/reports', icon: 'lucide:bar-chart-3' },
  { name: 'Settings', path: '/employer/settings', icon: 'lucide:settings' }
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
