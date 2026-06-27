<template>
  <div class="flex min-h-screen bg-gray-50">
    <AdminSidebar />
    <div class="flex-1 p-8 overflow-y-auto">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800">Job ↔ Course Mappings</h1>
          <p class="text-slate-500 mt-1">Connect job categories to relevant SMECLabs courses for automatic recommendations.</p>
        </div>
      </div>
      
      <!-- Create Mapping Form -->
      <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm mb-8">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Add New Mapping</h2>
        <form @submit.prevent="createMapping" class="flex flex-col md:flex-row gap-4 items-end">
          <div class="flex-1">
            <label class="block text-sm font-bold text-slate-700 mb-2">Job Category</label>
            <select v-model="form.job_category_id" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required>
              <option value="" disabled>Select a job category</option>
              <option v-for="cat in options?.categories || []" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>
          <div class="flex-none flex items-center justify-center pb-4 text-slate-400 px-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
          </div>
          <div class="flex-1">
            <label class="block text-sm font-bold text-slate-700 mb-2">Recommended Course Category</label>
            <select v-model="form.course_id" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required>
              <option value="" disabled>Select a course category</option>
              <option v-for="course in options?.courses || []" :key="course.id" :value="course.id">{{ course.name }}</option>
            </select>
          </div>
          <button type="submit" :disabled="isSaving" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-xl h-[50px] transition-colors disabled:opacity-50">
            {{ isSaving ? 'Mapping...' : 'Create Mapping' }}
          </button>
        </form>
      </div>

      <!-- Mappings List -->
      <div v-if="pending" class="p-12 flex justify-center">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600"></div>
      </div>

      <div v-else class="grid grid-cols-1 gap-6">
        <div v-for="category in mappings" :key="category.id" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center font-bold">
              💼
            </div>
            <h3 class="text-xl font-bold text-slate-800">{{ category.name }}</h3>
            <span class="ml-auto bg-slate-200 text-slate-600 text-xs font-bold px-3 py-1 rounded-full">
              {{ category.course_categories?.length || 0 }} linked categories
            </span>
          </div>
          
          <div class="p-6">
            <div v-if="!category.course_categories || category.course_categories.length === 0" class="text-slate-400 text-sm italic">
              No course categories mapped to this job category yet.
            </div>
            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="course in category.course_categories" :key="course.id" class="flex justify-between items-center p-4 border border-slate-200 rounded-xl hover:border-primary-300 transition-colors bg-white shadow-sm">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center text-sm">🎓</div>
                  <span class="font-semibold text-slate-700">{{ course.name }}</span>
                </div>
                <button @click="deleteMapping(category.id, course.id)" class="text-slate-400 hover:text-red-500 p-2 transition-colors rounded-lg hover:bg-red-50" title="Remove Mapping">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <div v-if="mappings?.length === 0" class="bg-white rounded-3xl p-12 text-center border border-slate-100">
          <p class="text-slate-500 font-medium">No job categories found.</p>
        </div>
      </div>
      
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import AdminSidebar from '~/components/AdminSidebar.vue'

const authStore = useAuthStore()

const mappings = ref<any[]>([])
const options = ref<any>({ categories: [], courses: [] })
const pending = ref(true)
const isSaving = ref(false)

const form = ref({
  job_category_id: '',
  course_id: ''
})

const fetchMappings = async () => {
  pending.value = true
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/admin/mappings', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    mappings.value = res
  } catch (e) {
    console.error('Failed to fetch mappings', e)
  } finally {
    pending.value = false
  }
}

const fetchOptions = async () => {
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/admin/mappings/options', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    options.value = res
  } catch (e) {
    console.error('Failed to fetch options', e)
  }
}

const createMapping = async () => {
  if (!form.value.job_category_id || !form.value.course_id) return
  isSaving.value = true
  try {
    await $fetch('http://127.0.0.1:8000/api/admin/mappings', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: form.value
    })
    form.value.course_id = '' // reset course selection after success
    await fetchMappings()
  } catch (e) {
    alert('Failed to create mapping. It might already exist.')
  } finally {
    isSaving.value = false
  }
}

const deleteMapping = async (categoryId: number, courseId: number) => {
  if (!confirm('Are you sure you want to remove this mapping?')) return
  try {
    await $fetch('http://127.0.0.1:8000/api/admin/mappings', {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: {
        job_category_id: categoryId,
        course_id: courseId
      }
    })
    await fetchMappings()
  } catch (e) {
    alert('Failed to delete mapping')
  }
}

onMounted(() => {
  fetchOptions()
  fetchMappings()
})
</script>
