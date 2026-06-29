<template>
  <div class="flex h-screen bg-gray-50 overflow-hidden">
    <AdminSidebar />
    <div class="flex-1 p-8 overflow-y-auto">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800">Course Management</h1>
          <p class="text-slate-500 mt-1">Manage SMECLabs courses available on the platform.</p>
        </div>
        <button @click="openModal()" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-sm transition-colors flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Add Course
        </button>
      </div>
      
      <!-- Loading State -->
      <div v-if="pending" class="p-12 flex justify-center">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600"></div>
      </div>
      
      <!-- Data Table -->
      <DataTable 
        v-else
        title="Courses List" 
        :columns="columns" 
        :items="courses?.data || []"
        :searchable="true"
        :hasActions="true"
        @search="handleSearch"
      >
        <template #cell-title="{ item }">
          <div class="font-bold text-slate-800">{{ item.title }}</div>
          <div class="text-xs text-slate-500">{{ item.course_code }} • {{ item.category?.name }}</div>
        </template>
        
        <template #cell-price="{ item }">
          <span class="font-medium text-slate-700">₹{{ item.fee_1 || 'N/A' }}</span>
        </template>
        
        <template #cell-duration="{ item }">
          <span class="text-slate-600">{{ item.duration || 'N/A' }}</span>
        </template>

        <template #actions-cell="{ item }">
          <div class="flex justify-end gap-2">
            <button @click="openModal(item)" class="p-2 hover:bg-slate-100 rounded-lg transition-colors" title="Edit">
              <Icon name="lucide:pencil" class="w-5 h-5 text-blue-500" />
            </button>
            <button @click="deleteCourse(item.id)" class="p-2 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
              <Icon name="lucide:trash-2" class="w-5 h-5 text-rose-500" />
            </button>
          </div>
        </template>
      </DataTable>
      
      <!-- Pagination Controls (Simple) -->
      <div v-if="courses?.last_page > 1" class="flex justify-center gap-2 mt-6">
        <button 
          @click="page--; fetchCourses()" 
          :disabled="page === 1"
          class="px-4 py-2 bg-white rounded-lg border border-slate-200 disabled:opacity-50 font-bold text-slate-600"
        >Previous</button>
        <button 
          @click="page++; fetchCourses()" 
          :disabled="page === courses?.last_page"
          class="px-4 py-2 bg-white rounded-lg border border-slate-200 disabled:opacity-50 font-bold text-slate-600"
        >Next</button>
      </div>

      <!-- Course Modal -->
      <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-2xl overflow-hidden mt-10 md:mt-0">
          <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-xl font-bold text-slate-800">{{ form.id ? 'Edit Course' : 'Add New Course' }}</h3>
            <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>
          <form @submit.prevent="saveCourse" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">Course Title</label>
                <input type="text" v-model="form.title" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500" required>
              </div>
              
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Course Code</label>
                <input type="text" v-model="form.course_code" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500" required>
              </div>

              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Category</label>
                <select v-model="form.course_category_id" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500" required>
                  <option value="" disabled>Select category</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Provider</label>
                <input type="text" v-model="form.provider" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500">
              </div>

              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Duration</label>
                <input type="text" v-model="form.duration" placeholder="e.g. 6 Months" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500">
              </div>

              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Fee (₹)</label>
                <input type="number" v-model="form.fee_1" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500">
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-bold text-slate-700 mb-1">Description</label>
              <textarea v-model="form.description" rows="4" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-primary-500"></textarea>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
              <button type="button" @click="closeModal" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Cancel</button>
              <button type="submit" :disabled="isSaving" class="px-6 py-2.5 rounded-xl font-bold text-white bg-primary-600 hover:bg-primary-700 disabled:opacity-50 transition-colors">
                {{ isSaving ? 'Saving...' : 'Save Course' }}
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
  { key: 'title', label: 'Course' },
  { key: 'duration', label: 'Duration' },
  { key: 'price', label: 'Fee' },
]

const courses = ref<any>(null)
const categories = ref<any[]>([])
const pending = ref(true)
const isModalOpen = ref(false)
const isSaving = ref(false)
const page = ref(1)
const searchQuery = ref('')
let searchTimeout: any = null

const form = ref({
  id: null,
  title: '',
  course_code: '',
  course_category_id: '',
  provider: 'SMECLabs',
  duration: '',
  fee_1: null,
  description: ''
})

const fetchCourses = async () => {
  pending.value = true
  try {
    const res: any = await $fetch('/api/admin/courses', {
      headers: { Authorization: `Bearer ${authStore.token}` },
      query: { search: searchQuery.value, page: page.value }
    })
    courses.value = res
  } catch (e) {
    console.error('Failed to fetch courses', e)
  } finally {
    pending.value = false
  }
}

const fetchCategories = async () => {
  try {
    const res: any = await $fetch('/api/admin/course-categories', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    categories.value = res
  } catch (e) {
    console.error('Failed to fetch course categories', e)
  }
}

const handleSearch = (val: string) => {
  searchQuery.value = val
  page.value = 1
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchCourses()
  }, 300)
}

const openModal = (course: any = null) => {
  if (course) {
    form.value = { ...course }
  } else {
    form.value = { id: null, title: '', course_code: '', course_category_id: '', provider: 'SMECLabs', duration: '', fee_1: null, description: '' }
  }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const saveCourse = async () => {
  isSaving.value = true
  try {
    if (form.value.id) {
      await $fetch(`/api/admin/courses/${form.value.id}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value
      })
    } else {
      await $fetch('/api/admin/courses', {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: form.value
      })
    }
    closeModal()
    fetchCourses()
  } catch (e: any) {
    alert(e.data?.message || 'Failed to save course')
  } finally {
    isSaving.value = false
  }
}

const deleteCourse = async (id: number) => {
  if (!confirm('Are you sure you want to delete this course?')) return
  try {
    await $fetch(`/api/admin/courses/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    fetchCourses()
  } catch (e) {
    alert('Failed to delete course')
  }
}

onMounted(() => {
  fetchCategories()
  fetchCourses()
})
</script>
