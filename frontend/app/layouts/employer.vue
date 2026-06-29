<template>
  <div class="h-[100dvh] bg-slate-50 flex overflow-hidden">
    <EmployerSidebar />
    
    <div class="flex-1 ml-64 flex flex-col h-full relative">
      <main class="flex-1 overflow-x-hidden p-6 sm:p-10 overflow-y-auto">
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
