<template>
  <div class="flex h-screen bg-gray-50 overflow-hidden">
    <AdminSidebar />
    <div class="flex-1 p-8 overflow-y-auto">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800">Course Enquiries</h1>
          <p class="text-slate-500 mt-1">Manage leads from prospective students inquiring about courses.</p>
        </div>
      </div>
      
      <!-- Filters -->
      <div class="flex gap-4 mb-6">
        <button v-for="tab in tabs" :key="tab.value" 
          @click="activeStatus = tab.value; fetchEnquiries()"
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
        :title="`${tabs.find(t => t.value === activeStatus)?.label} Leads`" 
        :columns="columns" 
        :items="enquiries?.data || []"
        :searchable="true"
        :hasActions="true"
        @search="handleSearch"
      >
        <template #cell-prospect="{ item }">
          <div class="font-bold text-slate-800">{{ item.name }} <span v-if="item.user_id" class="px-2 py-0.5 ml-2 bg-blue-100 text-blue-700 text-[10px] rounded-full uppercase">Registered</span></div>
          <div class="text-xs text-slate-500">{{ item.email }} • {{ item.phone }}</div>
        </template>
        
        <template #cell-course="{ item }">
          <span class="text-slate-700 font-medium">{{ item.course?.title || 'Unknown Course' }}</span>
        </template>

        <template #cell-message="{ item }">
          <div class="max-w-xs truncate text-slate-600 text-sm" :title="item.message">{{ item.message }}</div>
        </template>
        
        <template #cell-status="{ item }">
          <select @change="updateStatus(item, $event)" class="text-sm rounded-lg border-slate-200 py-1 pl-2 pr-6 font-bold"
            :class="{
              'bg-blue-50 text-blue-700': item.status === 'new',
              'bg-yellow-50 text-yellow-700': item.status === 'contacted',
              'bg-slate-100 text-slate-600': item.status === 'closed'
            }"
          >
            <option value="new" :selected="item.status === 'new'">New</option>
            <option value="contacted" :selected="item.status === 'contacted'">Contacted</option>
            <option value="closed" :selected="item.status === 'closed'">Closed</option>
          </select>
        </template>

        <template #actions-cell="{ item }">
          <div class="flex justify-end gap-2">
            <button @click="deleteEnquiry(item.id)" class="p-2 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
              <Icon name="lucide:trash-2" class="w-5 h-5 text-rose-500" />
            </button>
          </div>
        </template>
      </DataTable>
      
      <!-- Pagination Controls -->
      <div v-if="enquiries?.last_page > 1" class="flex justify-center gap-2 mt-6">
        <button 
          @click="page--; fetchEnquiries()" 
          :disabled="page === 1"
          class="px-4 py-2 bg-white rounded-lg border border-slate-200 disabled:opacity-50 font-bold text-slate-600"
        >Previous</button>
        <button 
          @click="page++; fetchEnquiries()" 
          :disabled="page === enquiries?.last_page"
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
  { label: 'All Leads', value: 'all' },
  { label: 'New', value: 'new' },
  { label: 'Contacted', value: 'contacted' },
  { label: 'Closed', value: 'closed' }
]

const columns = [
  { key: 'prospect', label: 'Prospect Details' },
  { key: 'course', label: 'Course' },
  { key: 'message', label: 'Message' },
  { key: 'status', label: 'Status' },
]

const enquiries = ref<any>(null)
const pending = ref(true)
const activeStatus = ref('all')
const page = ref(1)
const searchQuery = ref('')
let searchTimeout: any = null

const fetchEnquiries = async () => {
  pending.value = true
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/admin/enquiries', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      query: { search: searchQuery.value, status: activeStatus.value, page: page.value }
    })
    enquiries.value = res
  } catch (e) {
    console.error('Failed to fetch enquiries', e)
  } finally {
    pending.value = false
  }
}

const handleSearch = (val: string) => {
  searchQuery.value = val
  page.value = 1
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchEnquiries()
  }, 300)
}

const updateStatus = async (enquiry: any, event: any) => {
  const newStatus = event.target.value
  try {
    await $fetch(`http://127.0.0.1:8000/api/admin/enquiries/${enquiry.id}/status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { status: newStatus }
    })
    // Optionally refetch or let it stay in UI
  } catch (e) {
    alert('Failed to update status')
    // Reset UI
    event.target.value = enquiry.status
  }
}

const deleteEnquiry = async (id: number) => {
  if (!confirm('Are you sure you want to permanently delete this enquiry?')) return
  try {
    await $fetch(`http://127.0.0.1:8000/api/admin/enquiries/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    fetchEnquiries()
  } catch (e) {
    alert('Failed to delete enquiry')
  }
}

onMounted(() => {
  fetchEnquiries()
})
</script>
