<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <div class="flex-1 p-8 overflow-y-auto">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-slate-800">User Management</h1>
        <button @click="downloadCSV('users')" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition-colors flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
          Export CSV
        </button>
      </div>
      
      <!-- Role Filters -->
      <div class="flex gap-4 mb-6">
        <button v-for="tab in tabs" :key="tab.value" 
          @click="activeRole = tab.value; fetchUsers()"
          :class="['px-5 py-2 rounded-xl font-bold transition-colors shadow-sm', activeRole === tab.value ? 'bg-primary-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100']">
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
        :title="`${tabs.find(t => t.value === activeRole)?.label} List`" 
        :columns="columns" 
        :items="users?.data || []"
        :searchable="true"
        :hasActions="true"
        @search="handleSearch"
      >
        <template #cell-name="{ item }">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-500">
              {{ item.name.charAt(0).toUpperCase() }}
            </div>
            <div>
              <div class="font-bold text-slate-800">{{ item.name }}</div>
              <div class="text-xs text-slate-500">{{ item.email }}</div>
            </div>
          </div>
        </template>
        
        <template #cell-role="{ item }">
          <span class="px-3 py-1 rounded-full text-xs font-bold uppercase"
                :class="roleColors[item.role] || 'bg-slate-100 text-slate-600'">
            {{ item.role.replace('_', ' ') }}
          </span>
        </template>
        
        <template #cell-created_at="{ item }">
          {{ new Date(item.created_at).toLocaleDateString() }}
        </template>

        <template #actions-cell="{ item }">
          <div class="flex justify-end gap-2">
            <button @click="toggleStatus(item)" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors" :title="item.deleted_at ? 'Activate' : 'Suspend'">
              {{ item.deleted_at ? '✅' : '⏸️' }}
            </button>
            <button @click="deleteUser(item)" class="p-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors" title="Delete">
              🗑️
            </button>
          </div>
        </template>
      </DataTable>
      
      <!-- Pagination Controls (Simple) -->
      <div v-if="users?.last_page > 1" class="flex justify-center gap-2 mt-6">
        <button 
          @click="page--; fetchUsers()" 
          :disabled="page === 1"
          class="px-4 py-2 bg-white rounded-lg border border-slate-200 disabled:opacity-50 font-bold text-slate-600"
        >Previous</button>
        <button 
          @click="page++; fetchUsers()" 
          :disabled="page === users?.last_page"
          class="px-4 py-2 bg-white rounded-lg border border-slate-200 disabled:opacity-50 font-bold text-slate-600"
        >Next</button>
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
  { label: 'All Users', value: 'all' },
  { label: 'Job Seekers', value: 'applicant' },
  { label: 'Employers', value: 'employer' },
  { label: 'Admins', value: 'admin' },
]

const columns = [
  { key: 'name', label: 'User' },
  { key: 'role', label: 'Role' },
  { key: 'created_at', label: 'Registered On' },
]

const roleColors: Record<string, string> = {
  'super-admin': 'bg-purple-100 text-purple-800',
  'admin': 'bg-purple-100 text-purple-800',
  'employer': 'bg-blue-100 text-blue-800',
  'applicant': 'bg-green-100 text-green-800',
}

const activeRole = ref('all')
const searchQuery = ref('')
const page = ref(1)
const users = ref<any>(null)
const pending = ref(false)
let searchTimeout: any = null

const fetchUsers = async () => {
  pending.value = true
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/admin/users', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      query: {
        role: activeRole.value,
        search: searchQuery.value,
        page: page.value
      }
    })
    users.value = res
  } catch (e) {
    console.error('Failed to fetch users', e)
  } finally {
    pending.value = false
  }
}

const handleSearch = (val: string) => {
  searchQuery.value = val
  page.value = 1
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchUsers()
  }, 300)
}

const toggleStatus = async (user: any) => {
  if (!confirm(`Are you sure you want to ${user.deleted_at ? 'activate' : 'suspend'} this user?`)) return
  try {
    await $fetch(`http://127.0.0.1:8000/api/admin/users/${user.id}/toggle-status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    fetchUsers()
  } catch (e) {
    alert('Failed to update user status')
  }
}

const deleteUser = async (user: any) => {
  if (!confirm('Are you sure you want to permanently delete this user? This cannot be undone.')) return
  try {
    await $fetch(`http://127.0.0.1:8000/api/admin/users/${user.id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    fetchUsers()
  } catch (e) {
    alert('Failed to delete user')
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
  fetchUsers()
})
</script>
