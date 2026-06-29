<template>
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden max-w-5xl mx-auto">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h3 class="text-2xl font-bold text-slate-900">Notifications</h3>
        <p class="text-slate-500 mt-1">Stay updated on your job applications and profile activity.</p>
      </div>
      <button 
        v-if="unreadCount > 0"
        @click="markAllAsRead" 
        :disabled="marking"
        class="text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-colors disabled:opacity-50">
        {{ marking ? 'Marking...' : 'Mark all as read' }}
      </button>
    </div>

    <div v-if="pending && currentPage === 1" class="p-12 text-center text-slate-500">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-4"></div>
      Loading notifications...
    </div>
    
    <div v-else-if="!localNotifications?.length" class="p-16 text-center">
      <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
      </div>
      <h4 class="text-lg font-bold text-slate-900 mb-1">All caught up!</h4>
      <p class="text-slate-500">You don't have any notifications right now.</p>
    </div>

    <div v-else class="divide-y divide-slate-100">
      <div v-for="notif in localNotifications" :key="notif.id" 
        class="p-6 sm:p-8 hover:bg-slate-50 transition-colors flex gap-4"
        :class="{ 'bg-indigo-50/30': !notif.is_read }">
        
        <div class="w-12 h-12 rounded-full flex-shrink-0 flex items-center justify-center shadow-sm"
          :class="notif.type === 'success' ? 'bg-emerald-100 text-emerald-600' : (notif.type === 'warning' ? 'bg-amber-100 text-amber-600' : 'bg-indigo-100 text-indigo-600')">
          <svg v-if="notif.type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
          <svg v-else-if="notif.type === 'warning'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        
        <div class="flex-1 min-w-0">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1">
            <h4 class="font-bold text-slate-900" :class="{ 'text-indigo-900': !notif.is_read }">{{ notif.title }}</h4>
            <span class="text-xs font-medium text-slate-400 shrink-0">{{ new Date(notif.created_at).toLocaleString() }}</span>
          </div>
          <p class="text-slate-600 text-sm leading-relaxed mb-3">{{ notif.message }}</p>
          
          <button v-if="notif.link" @click="handleNotificationClick(notif)" class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600 hover:text-indigo-800">
            View Details
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </button>
        </div>
        
        <div v-if="!notif.is_read" class="shrink-0 flex items-center">
          <div class="w-3 h-3 bg-indigo-600 rounded-full shadow-sm shadow-indigo-200"></div>
        </div>
      </div>
      
      <div v-if="currentPage < lastPage" class="p-6 text-center border-t border-slate-100">
        <button @click="fetchNotifications(currentPage + 1)" :disabled="isLoading" class="px-6 py-2 bg-white border border-slate-300 hover:border-indigo-400 text-slate-700 hover:text-indigo-600 rounded-xl text-sm font-bold shadow-sm transition-colors disabled:opacity-50">
          {{ isLoading ? 'Loading...' : 'Load More' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()
const router = useRouter()
const marking = ref(false)

const currentPage = ref(1)
const lastPage = ref(1)
const isLoadingMore = ref(false)
const localNotifications = ref<any[]>([])

const { data: initialData, pending, refresh } = await useFetch<any>('/api/applicant/notifications', {
  headers: { Authorization: `Bearer ${authStore.token}` },
  server: false
})

// Initialize local notifications from useFetch
watch(initialData, (newVal) => {
  if (newVal && newVal.data) {
    localNotifications.value = newVal.data
    currentPage.value = newVal.current_page
    lastPage.value = newVal.last_page
  }
}, { immediate: true })

const fetchNotifications = async (page = 1) => {
  isLoadingMore.value = true
  try {
    const res: any = await $fetch(`/api/applicant/notifications?page=${page}`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    
    localNotifications.value = [...localNotifications.value, ...res.data]
    currentPage.value = res.current_page
    lastPage.value = res.last_page
  } catch (err) {
    console.error(err)
  } finally {
    isLoadingMore.value = false
  }
}

const unreadCount = computed(() => {
  return localNotifications.value?.filter(n => !n.is_read).length || 0
})

const markAllAsRead = async () => {
  marking.value = true
  try {
    await $fetch('/api/applicant/notifications/read-all', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    localNotifications.value.forEach(n => n.is_read = true)
    
    // Refresh to trigger backend logic if it needs to switch to "10 recent" view
    await fetchNotifications(1)
  } catch (e) {
    console.error('Failed to mark all as read', e)
  } finally {
    marking.value = false
  }
}

const handleNotificationClick = async (notif: any) => {
  if (!notif.is_read) {
    notif.is_read = true
    try {
      await $fetch(`/api/applicant/notifications/${notif.id}/read`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
    } catch (e) {
      console.error('Failed to mark as read', e)
    }
  }
  
  if (notif.link) {
    router.push(notif.link)
  }
}
</script>