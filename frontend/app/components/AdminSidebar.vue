<template>
  <aside class="w-64 bg-slate-950 text-slate-300 h-full flex flex-col border-r border-slate-800/50 shadow-2xl z-50">
    <div class="h-20 flex items-center px-8 border-b border-slate-800/50 bg-slate-950/50 backdrop-blur-md relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-transparent"></div>
      <div class="flex items-center gap-3 relative z-10">
        <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center font-black text-white text-lg shadow-lg shadow-emerald-500/20">S</div>
        <div>
          <h2 class="text-xl font-extrabold text-white tracking-tight leading-none">SMEC</h2>
          <p class="text-xs text-emerald-400 font-medium mt-0.5">Admin Portal</p>
        </div>
      </div>
    </div>
    
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto min-h-0">
      <NuxtLink 
        v-for="item in menuItems" 
        :key="item.name" 
        :to="item.path"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group relative text-slate-400 hover:text-white hover:bg-slate-800/50 hover:shadow-sm"
        active-class="bg-gradient-to-r from-emerald-600/20 to-transparent text-white border-l-2 border-emerald-500 rounded-l-none rounded-r-xl"
        exact-active-class="bg-gradient-to-r from-emerald-600/20 to-transparent text-white border-l-2 border-emerald-500 rounded-l-none rounded-r-xl"
      >
        <span class="opacity-70 group-hover:opacity-100 transition-opacity" :class="{ 'opacity-100 text-emerald-400': $route.path === item.path }">
          <Icon :name="item.icon" class="w-5 h-5" />
        </span>
        <span class="font-semibold text-sm">{{ item.name }}</span>
      </NuxtLink>
    </nav>
    
    <div class="p-4 border-t border-slate-800/50 bg-slate-950/80 backdrop-blur-sm">
      <button @click="logout" class="w-full flex items-center justify-center gap-2 bg-slate-800/50 hover:bg-rose-500/10 hover:text-rose-400 text-slate-400 border border-transparent hover:border-rose-500/20 font-bold py-2.5 rounded-xl transition-all text-sm">
        <Icon name="lucide:log-out" class="w-4 h-4" />
        Logout
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const menuItems = [
  { name: 'Dashboard', path: '/admin/dashboard', icon: 'lucide:layout-dashboard' },
  { name: 'Users', path: '/admin/users', icon: 'lucide:users' },
  { name: 'Jobs', path: '/admin/jobs', icon: 'lucide:briefcase' },
  { name: 'Companies', path: '/admin/companies', icon: 'lucide:building-2' },
  { name: 'Courses', path: '/admin/courses', icon: 'lucide:graduation-cap' },
  { name: 'Course Enquiries', path: '/admin/enquiries', icon: 'lucide:mail' },
  { name: 'Job-Course Mapping', path: '/admin/mappings', icon: 'lucide:link' },
  { name: 'Reports & Export', path: '/admin/reports', icon: 'lucide:bar-chart-2' },
  { name: 'Settings', path: '/admin/settings', icon: 'lucide:settings' },
]

const logout = async () => {
  if(confirm('Are you sure you want to log out?')) {
    await authStore.logout()
    router.push('/login')
  }
}
</script>
