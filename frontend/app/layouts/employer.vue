<template>
  <div class="min-h-screen bg-slate-50 flex">
    <EmployerSidebar />
    
    <div class="flex-1 ml-64 flex flex-col min-h-screen relative">
      <main class="flex-1 overflow-x-hidden p-6 sm:p-10">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useRouter } from 'vue-router'
import { onMounted } from 'vue'

const authStore = useAuthStore()
const router = useRouter()

onMounted(() => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
  } else if (authStore.user?.role !== 'employer') {
    router.push('/')
  }
})
</script>
