<template>
  <div class="min-h-[calc(100vh-64px)] bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-slate-600 mt-1">Welcome back, {{ authStore.user?.name }}!</p>
      </div>

      <div v-if="authStore.user?.role === 'employer'">
        <EmployerDashboard />
      </div>

      <div v-else>
        <ApplicantDashboard />
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useRouter } from '#app'
import { onMounted } from 'vue'

const authStore = useAuthStore()
const router = useRouter()

onMounted(() => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
  } else if (authStore.user?.role === 'admin' || authStore.user?.role === 'super-admin' || authStore.user?.role === 'super_admin') {
    router.push('/admin/dashboard')
  }
})

useSeoMeta({
  title: 'Dashboard - SMECJobs',
})
</script>
