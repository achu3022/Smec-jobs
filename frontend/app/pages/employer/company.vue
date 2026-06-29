<template>
  <NuxtLayout name="employer">
    <div class="max-w-4xl mx-auto space-y-8">
      <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Company Profile</h1>
        <p class="text-slate-500 mt-1">Complete your employer profile to attract the best talent.</p>
      </div>

      <div v-if="pending" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
      </div>

      <form v-else @submit.prevent="saveProfile" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Cover Image -->
        <div class="h-48 bg-slate-100 relative group overflow-hidden">
          <img v-if="previewCover || form.cover_image" :src="previewCover || getImageUrl(form.cover_image)" class="w-full h-full object-cover" />
          <div v-else class="w-full h-full bg-gradient-to-r from-indigo-100 to-purple-100 flex items-center justify-center">
            <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
          </div>
          <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <label class="cursor-pointer bg-white text-slate-900 px-4 py-2 rounded-lg font-bold text-sm shadow-lg hover:bg-slate-50 transition-colors">
              Upload Cover Image
              <input type="file" class="hidden" accept="image/*" @change="handleCoverUpload" />
            </label>
          </div>
        </div>

        <div class="p-8 sm:p-10 relative">
          <!-- Logo -->
          <div class="absolute -top-16 left-10">
            <div class="w-32 h-32 bg-white rounded-2xl border-4 border-white shadow-xl overflow-hidden relative group">
              <img v-if="previewLogo || form.logo" :src="previewLogo || getImageUrl(form.logo)" class="w-full h-full object-contain p-2" />
              <div v-else class="w-full h-full bg-slate-50 flex items-center justify-center">
                <span class="text-slate-400 font-bold text-sm">Logo</span>
              </div>
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <label class="cursor-pointer text-white font-bold text-xs hover:underline">
                  Change
                  <input type="file" class="hidden" accept="image/*" @change="handleLogoUpload" />
                </label>
              </div>
            </div>
          </div>

          <div class="mt-20 space-y-8">
            <!-- Basic Info -->
            <div>
              <h3 class="text-lg font-bold text-slate-900 mb-4 border-b pb-2">Basic Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                  <label class="block text-sm font-bold text-slate-700 mb-1">Company Name *</label>
                  <input v-model="form.name" type="text" required class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Industry *</label>
                  <input v-model="form.industry" type="text" required placeholder="e.g. Information Technology" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Company Size</label>
                  <select v-model="form.company_size" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select size</option>
                    <option value="1-10">1-10 employees</option>
                    <option value="11-50">11-50 employees</option>
                    <option value="51-200">51-200 employees</option>
                    <option value="201-500">201-500 employees</option>
                    <option value="501-1000">501-1000 employees</option>
                    <option value="1000+">1000+ employees</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Year Established</label>
                  <input v-model="form.year_established" type="number" min="1800" max="2100" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Website</label>
                  <input v-model="form.website" type="url" placeholder="https://" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-bold text-slate-700 mb-1">About the Company *</label>
                  <textarea v-model="form.description" required rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
              </div>
            </div>

            <!-- Location & Contact -->
            <div>
              <h3 class="text-lg font-bold text-slate-900 mb-4 border-b pb-2">Location & Contact</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                  <label class="block text-sm font-bold text-slate-700 mb-1">Base Location / City *</label>
                  <input v-model="form.location" type="text" required class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Headquarters (Full Address)</label>
                  <textarea v-model="form.headquarters" rows="2" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Branch Locations</label>
                  <p class="text-xs text-slate-500 mb-1">Comma separated list of other cities</p>
                  <textarea v-model="branchLocationsInput" rows="2" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Primary Contact Person</label>
                  <input v-model="form.contact_person" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">HR Email / Phone</label>
                  <input v-model="hrContactInput" type="text" placeholder="hr@company.com, 1234567890" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
              </div>
            </div>

            <!-- Social Links -->
            <div>
              <h3 class="text-lg font-bold text-slate-900 mb-4 border-b pb-2">Social Profiles</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">LinkedIn Profile</label>
                  <input v-model="socialLinks.linkedin" type="url" placeholder="https://linkedin.com/company/..." class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Twitter (X) Profile</label>
                  <input v-model="socialLinks.twitter" type="url" placeholder="https://twitter.com/..." class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Facebook Page</label>
                  <input v-model="socialLinks.facebook" type="url" placeholder="https://facebook.com/..." class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-1">Instagram Profile</label>
                  <input v-model="socialLinks.instagram" type="url" placeholder="https://instagram.com/..." class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 flex items-center justify-end gap-4 border-t">
              <div v-if="successMsg" class="text-emerald-600 font-bold text-sm">{{ successMsg }}</div>
              <div v-if="errorMsg" class="text-rose-600 font-bold text-sm">{{ errorMsg }}</div>
              <button type="submit" :disabled="isSaving" class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold py-3 px-8 rounded-xl transition-colors shadow-md shadow-indigo-200 flex items-center gap-2">
                <svg v-if="isSaving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                {{ isSaving ? 'Saving...' : 'Save Profile Changes' }}
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </NuxtLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'

useSeoMeta({
  title: 'Company Profile | Employer Dashboard'
})

const authStore = useAuthStore()

const pending = ref(true)
const isSaving = ref(false)
const successMsg = ref('')
const errorMsg = ref('')

const form = reactive({
  name: '',
  description: '',
  industry: '',
  location: '',
  website: '',
  company_size: '',
  year_established: '',
  headquarters: '',
  contact_person: '',
  logo: null as string | null,
  cover_image: null as string | null
})

const branchLocationsInput = ref('')
const hrContactInput = ref('')
const socialLinks = reactive({
  linkedin: '',
  twitter: '',
  facebook: '',
  instagram: ''
})

const logoFile = ref<File | null>(null)
const coverFile = ref<File | null>(null)
const previewLogo = ref('')
const previewCover = ref('')

const getImageUrl = (path: string | null) => {
  if (!path) return ''
  return path.startsWith('http') ? path : `/storage/${path}`
}

onMounted(async () => {
  try {
    const res: any = await $fetch('/api/employer/company', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    
    if (res.company) {
      Object.keys(form).forEach(key => {
        if (res.company[key] !== undefined) {
          (form as any)[key] = res.company[key]
        }
      })
      
      if (res.company.branch_locations) {
        branchLocationsInput.value = (res.company.branch_locations as string[]).join(', ')
      }
      
      if (res.company.hr_contact_details) {
        hrContactInput.value = (res.company.hr_contact_details as string[]).join(', ')
      }
      
      if (res.company.social_links) {
        Object.assign(socialLinks, res.company.social_links)
      }
    }
  } catch (e) {
    console.error(e)
  } finally {
    pending.value = false
  }
})

const handleLogoUpload = (e: any) => {
  const file = e.target.files[0]
  if (file) {
    logoFile.value = file
    previewLogo.value = URL.createObjectURL(file)
  }
}

const handleCoverUpload = (e: any) => {
  const file = e.target.files[0]
  if (file) {
    coverFile.value = file
    previewCover.value = URL.createObjectURL(file)
  }
}

const saveProfile = async () => {
  isSaving.value = true
  successMsg.value = ''
  errorMsg.value = ''
  
  const formData = new FormData()
  formData.append('name', form.name)
  formData.append('description', form.description)
  formData.append('industry', form.industry)
  formData.append('location', form.location)
  if (form.website) formData.append('website', form.website)
  if (form.company_size) formData.append('company_size', form.company_size)
  if (form.year_established) formData.append('year_established', form.year_established)
  if (form.headquarters) formData.append('headquarters', form.headquarters)
  if (form.contact_person) formData.append('contact_person', form.contact_person)
  
  if (logoFile.value) formData.append('logo', logoFile.value)
  if (coverFile.value) formData.append('cover_image', coverFile.value)
  
  const branches = branchLocationsInput.value.split(',').map(s => s.trim()).filter(s => s)
  branches.forEach((b, i) => formData.append(`branch_locations[${i}]`, b))
  
  const hrContacts = hrContactInput.value.split(',').map(s => s.trim()).filter(s => s)
  hrContacts.forEach((h, i) => formData.append(`hr_contact_details[${i}]`, h))
  
  Object.entries(socialLinks).forEach(([key, val]) => {
    if (val) formData.append(`social_links[${key}]`, val)
  })

  try {
    const res: any = await $fetch('/api/employer/company', {
      method: 'POST', // Using POST for file uploads
      headers: {
        Authorization: `Bearer ${authStore.token}`
      },
      body: formData
    })
    successMsg.value = 'Profile updated successfully!'
  } catch (e: any) {
    errorMsg.value = e.data?.message || 'Failed to update profile.'
  } finally {
    isSaving.value = false
  }
}
</script>
