<template>
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-5xl mx-auto">
    <h3 class="text-2xl font-bold text-slate-900 mb-6">Resume Management</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Upload Section -->
      <div class="space-y-6">
        <div class="bg-slate-50 rounded-3xl shadow-sm border border-slate-100 p-8">
          <h4 class="text-lg font-bold text-slate-900 mb-6">Your Resume</h4>
          
          <!-- Current Resume Preview -->
          <div v-if="currentResume" class="mb-6 bg-white border border-slate-200 rounded-2xl p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-red-50 text-red-500 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
              </div>
              <div class="overflow-hidden">
                <h5 class="font-bold text-slate-900 truncate max-w-[200px]" :title="currentResume.file_path.split('/').pop()">{{ currentResume.file_path.split('/').pop() }}</h5>
                <p class="text-xs text-slate-500 mt-0.5">Uploaded on {{ new Date(currentResume.created_at).toLocaleDateString() }}</p>
              </div>
            </div>
            <button @click="showPdfModal = true" class="text-primary-600 hover:text-primary-800 font-bold text-sm bg-primary-50 hover:bg-primary-100 px-4 py-2 rounded-lg transition-colors">
              Preview
            </button>
          </div>

          <h4 class="text-sm font-bold text-slate-900 mb-4">{{ currentResume ? 'Update Resume' : 'Upload Resume' }}</h4>
          <div v-if="uploadSuccess" class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100">
            Resume uploaded securely.
          </div>
          <div v-if="uploadError" class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100">
            {{ uploadError }}
          </div>
          <form @submit.prevent="handleUpload" class="space-y-4">
            <div class="border-2 border-dashed border-slate-300 hover:border-primary-400 bg-white rounded-2xl p-8 text-center transition-all group">
              <label class="relative cursor-pointer font-bold text-primary-600 hover:text-primary-700 block mb-1">
                <span>Select a file to upload</span>
                <input type="file" ref="fileInput" @change="onFileChange" class="sr-only" accept=".pdf,.doc,.docx">
              </label>
              <p class="text-xs text-slate-400">PDF, DOC, DOCX up to 5MB</p>
            </div>
            <div v-if="selectedFile" class="text-sm text-slate-700 bg-white shadow-sm p-4 rounded-xl border border-slate-200 flex items-center justify-between">
              <span class="truncate">{{ selectedFile.name }}</span>
              <button type="button" @click="selectedFile = null" class="text-slate-400 hover:text-red-500 p-1">X</button>
            </div>
            <button type="submit" :disabled="!selectedFile || isUploading" class="w-full bg-slate-900 hover:bg-slate-800 disabled:opacity-50 text-white font-bold py-3.5 px-4 rounded-xl">
              {{ isUploading ? 'Uploading...' : 'Save Resume' }}
            </button>
          </form>
        </div>
      </div>

      <!-- Resume Builder / Options Placeholder -->
      <div class="space-y-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 h-full flex flex-col justify-center text-center">
          <div class="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          </div>
          <h4 class="text-lg font-bold text-slate-900 mb-2">Build Resume Online</h4>
          <p class="text-sm text-slate-500 mb-6">Create a professional resume in minutes using our free online builder. This feature is coming soon.</p>
          <NuxtLink to="/student/resume-builder" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-xl transition-colors inline-block">
            Build Resume Now
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>

  <!-- PDF Viewer Modal -->
  <div v-if="showPdfModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-900/50 backdrop-blur-sm" @click.self="showPdfModal = false">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl h-[90vh] flex flex-col overflow-hidden">
      <div class="flex justify-between items-center p-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-900">Resume Preview</h3>
        <button @click="showPdfModal = false" class="text-slate-400 hover:text-red-500 p-2 rounded-lg transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>
      <div class="flex-1 bg-slate-100 p-4 overflow-hidden relative min-h-[60vh]">
        <iframe v-if="currentResume?.file_url" :src="currentResume.file_url" class="w-full h-full border-0 rounded-lg"></iframe>
        <div v-else class="flex items-center justify-center h-full text-slate-500">
          Preview not available.
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

const currentResume = ref<any>(null)
const showPdfModal = ref(false)

const fetchResume = async () => {
  try {
    const data: any = await $fetch('http://127.0.0.1:8000/api/applicant/resume', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    currentResume.value = data.resume
  } catch (e) {
    console.error('Failed to fetch resume', e)
  }
}

onMounted(() => {
  fetchResume()
})

const fileInput = ref<HTMLInputElement | null>(null)
const selectedFile = ref<File | null>(null)
const isUploading = ref(false)
const uploadSuccess = ref(false)
const uploadError = ref('')

const onFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    selectedFile.value = target.files[0]
  }
}

const handleUpload = async () => {
  if (!selectedFile.value) return
  
  isUploading.value = true
  uploadSuccess.value = false
  uploadError.value = ''

  try {
    const formData = new FormData()
    formData.append('resume', selectedFile.value)

    await $fetch('http://127.0.0.1:8000/api/applicant/resume', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: formData
    })

    uploadSuccess.value = true
    selectedFile.value = null
    if (fileInput.value) fileInput.value.value = ''
    fetchResume()
  } catch (e: any) {
    uploadError.value = e.data?.message || 'Failed to upload resume. Please try again.'
  } finally {
    isUploading.value = false
  }
}
</script>