<template>
  <div>
    <!-- Hero Section -->
    <div class="bg-slate-900 pt-32 pb-20 px-4 relative overflow-hidden">
      <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/40 to-slate-900/80 backdrop-blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">Upskill with <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">SMECLabs</span></h1>
        <p class="text-lg text-slate-300 max-w-2xl mx-auto mb-10">Industry-leading courses to elevate your career. Learn from experts and get certified in cutting-edge technologies.</p>
        
        <div class="max-w-xl mx-auto relative">
          <input type="text" v-model="searchQuery" placeholder="Search courses by title or category..." class="w-full bg-white/10 border border-white/20 text-white placeholder-slate-400 py-4 px-6 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 backdrop-blur-md">
        </div>
      </div>
    </div>
    
    <!-- Success Toast -->
    <div v-if="toastMsg" class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
      {{ toastMsg }}
    </div>

    <!-- Courses Grid -->
    <div class="max-w-7xl mx-auto px-4 py-16">
      <div v-if="pending" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
      
      <div v-else-if="filteredCourses.length === 0" class="text-center py-20">
        <div class="mb-6 flex justify-center text-primary-500">
          <Icon name="lucide:graduation-cap" class="w-20 h-20" />
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">No courses found</h3>
        <p class="text-slate-500">Try adjusting your search terms.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="course in filteredCourses" :key="course.id" class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden">
          <div class="p-8 flex-1">
            <div class="flex justify-between items-start mb-4">
              <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg uppercase tracking-wider">{{ course.category?.name || 'General' }}</span>
              <span class="text-sm font-bold text-slate-400">{{ course.course_code }}</span>
            </div>
            
            <h3 class="text-2xl font-bold text-slate-900 mb-3 leading-tight">{{ course.title }}</h3>
            
            <p class="text-slate-600 text-sm mb-6 line-clamp-3">
              {{ course.description || 'Comprehensive training program designed to equip you with industry-ready skills.' }}
            </p>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div class="bg-slate-50 p-3 rounded-xl">
                <div class="text-xs text-slate-500 mb-1">Duration</div>
                <div class="font-bold text-slate-800 text-sm flex items-center gap-1">
                  <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                  {{ course.duration || 'Flexible' }}
                </div>
              </div>
              <div class="bg-slate-50 p-3 rounded-xl">
                <div class="text-xs text-slate-500 mb-1">Fee</div>
                <div class="font-bold text-slate-800 text-sm flex items-center gap-1">
                  <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                  {{ course.fee_1 ? `₹${course.fee_1}` : 'Contact Us' }}
                </div>
              </div>
            </div>
          </div>
          
          <div class="p-4 border-t border-slate-100 bg-slate-50" v-if="!authStore.isAuthenticated || authStore.user?.role === 'applicant'">
            <button @click="handleEnquire(course)" :disabled="isSubmitting === course.id" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-colors disabled:opacity-50">
              {{ isSubmitting === course.id ? 'Sending...' : 'Enquire Now' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Guest Enquiry Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
      <div class="bg-white rounded-3xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
          <div>
            <h3 class="text-xl font-bold text-slate-800">Course Enquiry</h3>
            <p class="text-xs text-slate-500 mt-1">{{ selectedCourse?.title }}</p>
          </div>
          <button @click="closeModal" class="text-slate-400 hover:text-slate-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>
        <form @submit.prevent="submitGuestEnquiry" class="p-6">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-bold text-slate-700 mb-1">Full Name</label>
              <input type="text" v-model="form.name" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-blue-500" required>
            </div>
            
            <div>
              <label class="block text-sm font-bold text-slate-700 mb-1">Email Address</label>
              <input type="email" v-model="form.email" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-blue-500" required>
            </div>
            
            <div>
              <label class="block text-sm font-bold text-slate-700 mb-1">Phone Number</label>
              <input type="text" v-model="form.phone" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-blue-500" required>
            </div>

            <div>
              <label class="block text-sm font-bold text-slate-700 mb-1">Message (Optional)</label>
              <textarea v-model="form.message" rows="3" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-2 px-3 focus:ring-blue-500"></textarea>
            </div>
          </div>
          
          <div class="mt-6">
            <button type="submit" :disabled="isSubmitting === selectedCourse?.id" class="w-full px-6 py-3 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 transition-colors">
              {{ isSubmitting === selectedCourse?.id ? 'Sending Enquiry...' : 'Submit Enquiry' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useHead } from '#imports'
import { useAuthStore } from '~/stores/auth'

useHead({
  title: 'Training Courses - SMECLabs'
})

const authStore = useAuthStore()
const { data: courses, pending } = await useFetch('http://127.0.0.1:8000/api/courses')

const searchQuery = ref('')
const isModalOpen = ref(false)
const selectedCourse = ref<any>(null)
const isSubmitting = ref<number | null>(null)
const toastMsg = ref('')

const form = ref({
  name: '',
  email: '',
  phone: '',
  message: ''
})

const filteredCourses = computed(() => {
  if (!courses.value) return []
  if (!searchQuery.value) return courses.value
  
  const query = searchQuery.value.toLowerCase()
  return (courses.value as any[]).filter(c => 
    c.title.toLowerCase().includes(query) || 
    (c.category?.name && c.category.name.toLowerCase().includes(query))
  )
})

const showToast = (msg: string) => {
  toastMsg.value = msg
  setTimeout(() => { toastMsg.value = '' }, 3000)
}

const handleEnquire = async (course: any) => {
  if (authStore.isAuthenticated) {
    // Auto submit using bearer token
    isSubmitting.value = course.id
    try {
      await $fetch(`http://127.0.0.1:8000/api/courses/${course.id}/enquire`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` }
      })
      showToast('Enquiry sent successfully!')
    } catch (e) {
      alert('Failed to send enquiry. Please try again.')
    } finally {
      isSubmitting.value = null
    }
  } else {
    // Open guest modal
    selectedCourse.value = course
    form.value = { name: '', email: '', phone: '', message: '' }
    isModalOpen.value = true
  }
}

const closeModal = () => {
  isModalOpen.value = false
  selectedCourse.value = null
}

const submitGuestEnquiry = async () => {
  if (!selectedCourse.value) return
  isSubmitting.value = selectedCourse.value.id
  try {
    await $fetch(`http://127.0.0.1:8000/api/courses/${selectedCourse.value.id}/enquire`, {
      method: 'POST',
      body: form.value
    })
    closeModal()
    showToast('Enquiry sent successfully!')
  } catch (e: any) {
    alert(e.data?.message || 'Failed to submit enquiry')
  } finally {
    isSubmitting.value = null
  }
}
</script>
