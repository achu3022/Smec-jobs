<template>
  <div class="flex h-screen bg-slate-50 overflow-hidden">
    <AdminSidebar />
    <div class="flex-1 p-8 overflow-y-auto">
      <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-2 text-slate-800 tracking-tight">Platform Settings</h1>
        <p class="text-slate-500 mb-8">Manage your platform's global configurations and information.</p>

        <div v-if="pending" class="flex justify-center p-12">
          <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-500"></div>
        </div>

        <form v-else @submit.prevent="saveSettings" class="space-y-6">
          
          <!-- General Information -->
          <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
              <Icon name="lucide:globe" class="w-5 h-5 text-emerald-500" />
              General Information
            </h2>
            <div class="space-y-5">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Site Name</label>
                <input v-model="form.site_name" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" placeholder="e.g. SMECJobs" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Site Description</label>
                <textarea v-model="form.site_description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" placeholder="Brief description of the platform"></textarea>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
              <Icon name="lucide:phone" class="w-5 h-5 text-blue-500" />
              Contact Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Support Email</label>
                <input v-model="form.support_email" type="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" placeholder="support@example.com" />
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Support Phone</label>
                <input v-model="form.support_phone" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" placeholder="+1 234 567 8900" />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Physical Address</label>
                <textarea v-model="form.site_address" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" placeholder="Full office address"></textarea>
              </div>
            </div>
          </div>

          <!-- Social Links -->
          <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
              <Icon name="lucide:link" class="w-5 h-5 text-indigo-500" />
              Social Media Links
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Facebook URL</label>
                <input v-model="form.facebook_url" type="url" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" placeholder="https://facebook.com/..." />
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Twitter (X) URL</label>
                <input v-model="form.twitter_url" type="url" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" placeholder="https://twitter.com/..." />
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">LinkedIn URL</label>
                <input v-model="form.linkedin_url" type="url" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" placeholder="https://linkedin.com/..." />
              </div>
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Instagram URL</label>
                <input v-model="form.instagram_url" type="url" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-colors" placeholder="https://instagram.com/..." />
              </div>
            </div>
          </div>

          <div class="flex justify-end pt-4">
            <button type="submit" :disabled="isSaving" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-sm shadow-emerald-500/30 flex items-center gap-2 disabled:opacity-70">
              <Icon v-if="isSaving" name="lucide:loader-2" class="w-5 h-5 animate-spin" />
              <Icon v-else name="lucide:save" class="w-5 h-5" />
              {{ isSaving ? 'Saving...' : 'Save Settings' }}
            </button>
          </div>
          
          <div v-if="successMessage" class="mt-4 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl flex items-center gap-3">
            <Icon name="lucide:check-circle" class="w-5 h-5 flex-shrink-0" />
            <p class="font-medium">{{ successMessage }}</p>
          </div>
          <div v-if="errorMessage" class="mt-4 p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl flex items-center gap-3">
            <Icon name="lucide:alert-circle" class="w-5 h-5 flex-shrink-0" />
            <p class="font-medium">{{ errorMessage }}</p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import AdminSidebar from '~/components/AdminSidebar.vue'

const authStore = useAuthStore()

const pending = ref(true)
const isSaving = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const form = ref({
  site_name: '',
  site_description: '',
  support_email: '',
  support_phone: '',
  site_address: '',
  facebook_url: '',
  twitter_url: '',
  linkedin_url: '',
  instagram_url: ''
})

const fetchSettings = async () => {
  pending.value = true
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/admin/settings', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    
    // Populate form with existing settings
    if (res && typeof res === 'object') {
      Object.keys(form.value).forEach(key => {
        if (res[key] !== undefined) {
          (form.value as any)[key] = res[key]
        }
      })
    }
  } catch (e) {
    console.error('Failed to load settings', e)
    errorMessage.value = 'Failed to load settings.'
  } finally {
    pending.value = false
  }
}

const saveSettings = async () => {
  isSaving.value = true
  successMessage.value = ''
  errorMessage.value = ''
  
  try {
    await $fetch('http://127.0.0.1:8000/api/admin/settings', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: form.value
    })
    
    successMessage.value = 'Settings updated successfully!'
    setTimeout(() => { successMessage.value = '' }, 3000)
  } catch (e: any) {
    console.error('Failed to save settings', e)
    errorMessage.value = e.data?.message || 'Failed to save settings. Please try again.'
  } finally {
    isSaving.value = false
  }
}

onMounted(() => {
  fetchSettings()
})
</script>
