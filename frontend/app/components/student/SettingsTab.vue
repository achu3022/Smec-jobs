<template>
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
      <div>
        <h3 class="text-2xl font-bold text-slate-900">Job Alert Settings</h3>
        <p class="text-slate-500 mt-1">Set your preferences to receive personalized job recommendations.</p>
      </div>
      <button @click="showAddModal = true" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create New Alert
      </button>
    </div>
    
    <!-- Active Alerts List -->
    <div v-if="pending" class="py-12 flex justify-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
    </div>
    
    <div v-else-if="alerts.length === 0" class="text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
      <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-400">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
      </div>
      <h4 class="text-lg font-bold text-slate-900 mb-2">No Job Alerts Yet</h4>
      <p class="text-slate-500 mb-6 max-w-sm mx-auto">Create a job alert to tell us what kind of jobs you're looking for, and we'll recommend the best matches.</p>
      <button @click="showAddModal = true" class="text-primary-600 font-bold hover:text-primary-800 transition-colors">
        + Create your first alert
      </button>
    </div>
    
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="alert in alerts" :key="alert.id" class="border border-slate-200 rounded-2xl p-5 hover:border-primary-300 hover:shadow-md transition-all group bg-white">
        <div class="flex justify-between items-start mb-3">
          <div class="flex items-center gap-3">
            <button @click="toggleAlert(alert.id)" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="alert.email_alerts_active ? 'bg-primary-600' : 'bg-slate-200'">
              <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="alert.email_alerts_active ? 'translate-x-5' : 'translate-x-0'"></span>
            </button>
            <h4 class="font-bold text-slate-900 text-lg truncate">{{ alert.keyword || 'Any Role' }}</h4>
          </div>
          <button @click="deleteAlert(alert.id)" class="text-slate-400 hover:text-red-500 p-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
          </button>
        </div>
        
        <div class="space-y-2 mt-4 text-sm text-slate-600">
          <div v-if="alert.location" class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            {{ alert.location }}
          </div>
          <div v-if="alert.industry" class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            {{ alert.industry }}
          </div>
          <div v-if="alert.job_type" class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            {{ alert.job_type }}
          </div>
        </div>
      </div>
    </div>
    
    <!-- Add Modal -->
    <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" @click.self="showAddModal = false">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-slate-100">
          <h3 class="font-bold text-slate-900 text-xl">Create Job Alert</h3>
          <button @click="showAddModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>
        
        <form @submit.prevent="saveAlert" class="p-6 space-y-5">
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Keywords / Job Title <span class="text-red-500">*</span></label>
            <input type="text" v-model="form.keyword" placeholder="e.g. Software Engineer, Marketing" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2.5 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Location</label>
            <input type="text" v-model="form.location" placeholder="e.g. Kochi, Remote, Dubai" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2.5 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-bold text-slate-700 mb-1">Employment Type</label>
              <select v-model="form.job_type" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2.5 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
                <option value="">Any Type</option>
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
                <option value="Contract">Contract</option>
                <option value="Freelance">Freelance</option>
                <option value="Remote">Remote</option>
                <option value="Internship">Internship</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-bold text-slate-700 mb-1">Industry</label>
              <input type="text" v-model="form.industry" placeholder="e.g. IT, Healthcare" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2.5 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
            </div>
          </div>
          
          <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-6">
            <button type="button" @click="showAddModal = false" class="px-5 py-2.5 font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancel</button>
            <button type="submit" :disabled="isSaving" class="bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold px-6 py-2.5 rounded-xl transition-colors">
              {{ isSaving ? 'Saving...' : 'Save Alert' }}
            </button>
          </div>
        </form>
      </div>
    </div>
    
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

const pending = ref(true)
const alerts = ref<any[]>([])
const showAddModal = ref(false)
const isSaving = ref(false)

const form = ref({
  keyword: '',
  location: '',
  job_type: '',
  industry: ''
})

const fetchAlerts = async () => {
  pending.value = true
  try {
    const data: any = await $fetch('/api/applicant/job-alerts', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    alerts.value = data.alerts || []
  } catch (e) {
    console.error('Failed to load job alerts', e)
  } finally {
    pending.value = false
  }
}

const saveAlert = async () => {
  isSaving.value = true
  try {
    await $fetch('/api/applicant/job-alerts', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: form.value
    })
    showAddModal.value = false
    form.value = { keyword: '', location: '', job_type: '', industry: '' }
    fetchAlerts()
  } catch (e) {
    console.error('Failed to save alert', e)
  } finally {
    isSaving.value = false
  }
}

const toggleAlert = async (id: number) => {
  const alertIndex = alerts.value.findIndex(a => a.id === id)
  if (alertIndex > -1) {
    // Optimistic update
    alerts.value[alertIndex].email_alerts_active = !alerts.value[alertIndex].email_alerts_active
    try {
      await $fetch(`/api/applicant/job-alerts/${id}/toggle`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
    } catch (e) {
      // Revert on error
      alerts.value[alertIndex].email_alerts_active = !alerts.value[alertIndex].email_alerts_active
    }
  }
}

const deleteAlert = async (id: number) => {
  if (!confirm('Are you sure you want to delete this job alert?')) return
  try {
    await $fetch(`/api/applicant/job-alerts/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    alerts.value = alerts.value.filter(a => a.id !== id)
  } catch (e) {
    console.error('Failed to delete alert', e)
  }
}

onMounted(() => {
  fetchAlerts()
})
</script>