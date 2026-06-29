<template>
  <div class="flex h-screen bg-gray-50 overflow-hidden">
    <AdminSidebar />
    <div class="flex-1 p-8 overflow-y-auto">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800">Job Management</h1>
          <p class="text-slate-500 mt-1">Manage and moderate all job postings.</p>
        </div>
        <button @click="downloadCSV('jobs')" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition-colors flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
          Export CSV
        </button>
      </div>
      
      <!-- Filters -->
      <div class="flex gap-4 mb-6">
        <button v-for="tab in tabs" :key="tab.value" 
          @click="activeStatus = tab.value; fetchJobs()"
          :class="['px-5 py-2 rounded-xl font-bold transition-colors shadow-sm', activeStatus === tab.value ? 'bg-primary-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100']">
          {{ tab.label }}
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="pending" class="p-12 flex justify-center">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600"></div>
      </div>
      
      <!-- Data Table -->
      <DataTable 
        v-else
        :title="`${tabs.find(t => t.value === activeStatus)?.label} Jobs`" 
        :columns="columns" 
        :items="jobs?.data || []"
        :searchable="true"
        :hasActions="true"
        @search="handleSearch"
      >
        <template #cell-title="{ item }">
          <div class="font-bold text-slate-800">{{ item.title }}</div>
          <div class="text-xs text-slate-500">{{ item.company?.name }} • {{ item.category?.name }}</div>
        </template>
        
        <template #cell-location="{ item }">
          <span class="text-slate-600">{{ item.location }}</span>
        </template>
        
        <template #cell-status="{ item }">
          <div class="flex flex-col gap-1 items-start">
            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase"
                  :class="item.is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600'">
              {{ item.is_active ? 'Active' : 'Closed' }}
            </span>
            <span v-if="item.is_featured" class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-yellow-100 text-yellow-700">
              Featured
            </span>
          </div>
        </template>

        <template #actions-cell="{ item }">
          <div class="flex justify-end gap-2">
            <button @click="openModal(item)" class="p-2 hover:bg-slate-100 rounded-lg transition-colors" title="Edit Job">
              <Icon name="lucide:pencil" class="w-5 h-5 text-blue-500" />
            </button>
            <button @click="toggleFeatured(item.id)" class="p-2 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition-colors" :title="item.is_featured ? 'Remove Featured' : 'Mark Featured'">
              <Icon name="lucide:star" class="w-5 h-5" />
            </button>
            <button @click="toggleStatus(item)" class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors" :title="item.is_active ? 'Close Job' : 'Activate Job'">
              <Icon :name="item.is_active ? 'lucide:pause-circle' : 'lucide:play-circle'" class="w-5 h-5" />
            </button>
            <button @click="deleteJob(item.id)" class="p-2 hover:bg-red-50 rounded-lg transition-colors" title="Delete Job">
              <Icon name="lucide:trash-2" class="w-5 h-5 text-rose-500" />
            </button>
          </div>
        </template>
      </DataTable>
      
      <!-- Pagination Controls -->
      <div v-if="jobs?.last_page > 1" class="flex justify-center gap-2 mt-6">
        <button 
          @click="page--; fetchJobs()" 
          :disabled="page === 1"
          class="px-4 py-2 bg-white rounded-lg border border-slate-200 disabled:opacity-50 font-bold text-slate-600"
        >Previous</button>
        <button 
          @click="page++; fetchJobs()" 
          :disabled="page === jobs?.last_page"
          class="px-4 py-2 bg-white rounded-lg border border-slate-200 disabled:opacity-50 font-bold text-slate-600"
        >Next</button>
      </div>

      <!-- Edit Modal -->
      <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-2xl overflow-hidden mt-10 md:mt-0">
          <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-xl font-bold text-slate-800">Edit Job Details</h3>
            <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>
          <form @submit.prevent="saveJob" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">Job Title</label>
                <input type="text" v-model="form.title" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500" required>
              </div>
              
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Category</label>
                <select v-model="form.job_category_id" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500" required>
                  <option v-for="cat in options.categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Company</label>
                <select v-model="form.company_id" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500" required>
                  <option v-for="comp in options.companies" :key="comp.id" :value="comp.id">{{ comp.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Location</label>
                <input type="text" v-model="form.location" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500">
              </div>
              
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Job Type</label>
                <select v-model="form.job_type" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500">
                  <option value="Full-time">Full-time</option>
                  <option value="Part-time">Part-time</option>
                  <option value="Contract">Contract</option>
                </select>
              </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
              <button type="button" @click="closeModal" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Cancel</button>
              <button type="submit" :disabled="isSaving" class="px-6 py-2.5 rounded-xl font-bold text-white bg-primary-600 hover:bg-primary-700 disabled:opacity-50 transition-colors">
                {{ isSaving ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import AdminSidebar from '~/components/AdminSidebar.vue'
import DataTable from '~/components/DataTable.vue'

const authStore = useAuthStore()

const tabs = [
  { label: 'All Jobs', value: 'all' },
  { label: 'Active', value: 'active' },
  { label: 'Closed', value: 'closed' },
  { label: 'Featured', value: 'featured' }
]

const columns = [
  { key: 'title', label: 'Job Title & Company' },
  { key: 'location', label: 'Location' },
  { key: 'status', label: 'Status' },
]

const jobs = ref<any>(null)
const options = ref<any>({ categories: [], companies: [] })
const pending = ref(true)
const isModalOpen = ref(false)
const isSaving = ref(false)
const activeStatus = ref('all')
const page = ref(1)
const searchQuery = ref('')
let searchTimeout: any = null

const form = ref({
  id: null,
  title: '',
  job_category_id: '',
  company_id: '',
  location: '',
  job_type: '',
})

const fetchJobs = async () => {
  pending.value = true
  try {
    const res: any = await $fetch('/api/admin/jobs', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      query: { search: searchQuery.value, status: activeStatus.value, page: page.value }
    })
    jobs.value = res
  } catch (e) {
    console.error('Failed to fetch jobs', e)
  } finally {
    pending.value = false
  }
}

const fetchOptions = async () => {
  try {
    const res: any = await $fetch('/api/admin/jobs/options', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    options.value = res
  } catch (e) {
    console.error('Failed to fetch options', e)
  }
}

const handleSearch = (val: string) => {
  searchQuery.value = val
  page.value = 1
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchJobs()
  }, 300)
}

const openModal = (job: any) => {
  form.value = { ...job }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const saveJob = async () => {
  isSaving.value = true
  try {
    await $fetch(`/api/admin/jobs/${form.value.id}`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: form.value
    })
    closeModal()
    fetchJobs()
  } catch (e: any) {
    alert(e.data?.message || 'Failed to update job')
  } finally {
    isSaving.value = false
  }
}

const toggleStatus = async (job: any) => {
  const action = job.is_active ? 'close' : 'activate'
  if (!confirm(`Are you sure you want to ${action} this job?`)) return
  try {
    await $fetch(`/api/admin/jobs/${job.id}/toggle-status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    fetchJobs()
  } catch (e) {
    alert('Failed to update job status')
  }
}

const toggleFeatured = async (id: number) => {
  try {
    await $fetch(`/api/admin/jobs/${id}/toggle-featured`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    fetchJobs()
  } catch (e) {
    alert('Failed to update featured status')
  }
}

const deleteJob = async (id: number) => {
  if (!confirm('Are you sure you want to permanently delete this job? This action cannot be undone.')) return
  try {
    await $fetch(`/api/admin/jobs/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    fetchJobs()
  } catch (e) {
    alert('Failed to delete job')
  }
}

const downloadCSV = async (type: string) => {
  try {
    const response = await fetch(`/api/admin/export/${type}`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    if (!response.ok) throw new Error('Export failed')
    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `smecjobs_${type}_export.csv`
    document.body.appendChild(a)
    a.click()
    window.URL.revokeObjectURL(url)
    document.body.removeChild(a)
  } catch (e) {
    alert('Failed to export. Please try again.')
  }
}

onMounted(() => {
  fetchOptions()
  fetchJobs()
})
</script>
