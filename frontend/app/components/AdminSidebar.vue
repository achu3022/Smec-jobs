<template>
  <aside class="w-64 bg-slate-900 text-slate-300 min-h-screen flex flex-col transition-all duration-300">
    <div class="p-6 flex items-center gap-3">
      <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center font-bold text-white text-lg">S</div>
      <h2 class="text-xl font-extrabold text-white tracking-tight">SMEC Admin</h2>
    </div>
    
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
      <NuxtLink 
        v-for="item in menuItems" 
        :key="item.name" 
        :to="item.path"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium"
        active-class="bg-primary-600/10 text-primary-400 font-bold"
        exact-active-class="bg-primary-600/10 text-primary-400 font-bold"
      >
        <span class="text-xl">{{ item.icon }}</span>
        <span>{{ item.name }}</span>
      </NuxtLink>
    </nav>
    
    <div class="p-4 border-t border-slate-800">
      <button @click="logout" class="w-full flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-colors font-medium">
        <span class="text-xl">🚪</span>
        <span>Logout</span>
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
  { name: 'Dashboard', path: '/admin/dashboard', icon: '📊' },
  { name: 'Users', path: '/admin/users', icon: '👥' },
  { name: 'Jobs', path: '/admin/jobs', icon: '💼' },
  { name: 'Companies', path: '/admin/companies', icon: '🏢' },
  { name: 'Courses', path: '/admin/courses', icon: '🎓' },
  { name: 'Course Enquiries', path: '/admin/enquiries', icon: '📨' },
  { name: 'Job-Course Mapping', path: '/admin/mappings', icon: '🔗' },
  { name: 'Reports & Export', path: '/admin/reports', icon: '📊' },
  { name: 'Settings', path: '/admin/settings', icon: '⚙️' },
]

const logout = () => {
  authStore.logout()
  router.push('/login')
}
</script>
