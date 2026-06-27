<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <div class="flex-1 p-8 overflow-y-auto">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800">Company Management</h1>
          <p class="text-slate-500 mt-1">Manage employers and their company profiles.</p>
        </div>
        <button @click="downloadCSV('companies')" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition-colors flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
          Export CSV
        </button>
      </div>
      
      <!-- Loading State -->
      <div v-if="pending" class="p-12 flex justify-center">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600"></div>
      </div>
      
      <!-- Data Table -->
      <DataTable 
        v-else
        title="Registered Companies" 
        :columns="columns" 
        :items="companies?.data || []"
        :searchable="true"
        :hasActions="true"
        @search="handleSearch"
      >
        <template #cell-name="{ item }">
          <div class="flex items-center gap-3">
            <div class="w-16 h-10 bg-transparent flex items-center justify-center shrink-0">
              <img v-if="item.logo" :src="item.logo.startsWith('http') ? item.logo : `http://127.0.0.1:8000/storage/${item.logo}`" class="max-w-full max-h-full object-contain" />
              <span v-else class="text-xl font-bold text-slate-400">{{ item.name.charAt(0).toUpperCase() }}</span>
            </div>
            <div>
              <div class="font-bold text-slate-800">{{ item.name }}</div>
              <div class="text-xs text-slate-500">Owner: {{ item.user?.name || 'Unknown' }}</div>
            </div>
          </div>
        </template>
        
        <template #cell-industry="{ item }">
          <span class="text-slate-600">{{ item.industry || 'N/A' }}</span>
        </template>
        
        <template #cell-location="{ item }">
          <span class="text-slate-600">{{ item.location || 'N/A' }}</span>
        </template>

        <template #actions-cell="{ item }">
          <div class="flex justify-end gap-2">
            <button @click="openModal(item)" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors" title="Edit Company">
              ✏️
            </button>
            <button @click="toggleStatus(item)" class="p-2 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition-colors" :title="item.deleted_at ? 'Restore Company' : 'Suspend Company'">
              {{ item.deleted_at ? '✅' : '⏸️' }}
            </button>
            <button @click="deleteCompany(item.id)" class="p-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors" title="Delete Permanently">
              🗑️
            </button>
          </div>
        </template>
      </DataTable>
      
      <!-- Pagination Controls (Simple) -->
      <div v-if="companies?.last_page > 1" class="flex justify-center gap-2 mt-6">
        <button 
          @click="page--; fetchCompanies()" 
          :disabled="page === 1"
          class="px-4 py-2 bg-white rounded-lg border border-slate-200 disabled:opacity-50 font-bold text-slate-600"
        >Previous</button>
        <button 
          @click="page++; fetchCompanies()" 
          :disabled="page === companies?.last_page"
          class="px-4 py-2 bg-white rounded-lg border border-slate-200 disabled:opacity-50 font-bold text-slate-600"
        >Next</button>
      </div>

      <!-- Edit Modal -->
      <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-2xl overflow-hidden mt-10 md:mt-0">
          <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-xl font-bold text-slate-800">Edit Company Details</h3>
            <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>
          <form @submit.prevent="saveCompany" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">Company Name</label>
                <input type="text" v-model="form.name" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500" required>
              </div>
              
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Industry</label>
                <input type="text" v-model="form.industry" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500">
              </div>
              
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Location</label>
                <input type="text" v-model="form.location" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500">
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">Website URL</label>
                <input type="url" v-model="form.website" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500">
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-bold text-slate-700 mb-1">Description</label>
              <textarea v-model="form.description" rows="4" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500"></textarea>
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

const columns = [
  { key: 'name', label: 'Company' },
  { key: 'industry', label: 'Industry' },
  { key: 'location', label: 'Location' },
]

const companies = ref<any>(null)
const pending = ref(true)
const isModalOpen = ref(false)
const isSaving = ref(false)
const page = ref(1)
const searchQuery = ref('')
let searchTimeout: any = null

const form = ref({
  id: null,
  name: '',
  industry: '',
  location: '',
  website: '',
  description: ''
})

const fetchCompanies = async () => {
  pending.value = true
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/admin/companies', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      query: { search: searchQuery.value, page: page.value }
    })
    companies.value = res
  } catch (e) {
    console.error('Failed to fetch companies', e)
  } finally {
    pending.value = false
  }
}

const handleSearch = (val: string) => {
  searchQuery.value = val
  page.value = 1
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchCompanies()
  }, 300)
}

const openModal = (company: any) => {
  form.value = { ...company }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const saveCompany = async () => {
  isSaving.value = true
  try {
    await $fetch(`http://127.0.0.1:8000/api/admin/companies/${form.value.id}`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: form.value
    })
    closeModal()
    fetchCompanies()
  } catch (e: any) {
    alert(e.data?.message || 'Failed to update company')
  } finally {
    isSaving.value = false
  }
}

const toggleStatus = async (company: any) => {
  const action = company.deleted_at ? 'restore' : 'suspend'
  if (!confirm(`Are you sure you want to ${action} this company?`)) return
  try {
    await $fetch(`http://127.0.0.1:8000/api/admin/companies/${company.id}/toggle-status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    fetchCompanies()
  } catch (e) {
    alert('Failed to update company status')
  }
}

const deleteCompany = async (id: number) => {
  if (!confirm('Are you sure you want to permanently delete this company? This action cannot be undone.')) return
  try {
    await $fetch(`http://127.0.0.1:8000/api/admin/companies/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    fetchCompanies()
  } catch (e) {
    alert('Failed to delete company')
  }
}

const downloadCSV = async (type: string) => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/admin/export/${type}`, {
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
  fetchCompanies()
})
</script>
