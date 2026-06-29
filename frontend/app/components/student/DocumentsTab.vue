<template>
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden max-w-5xl mx-auto">
    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h3 class="text-2xl font-bold text-slate-900">My Documents</h3>
        <p class="text-slate-500 mt-1">Manage your resumes and other attachments.</p>
      </div>
      <button @click="showUploadForm = !showUploadForm" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold transition-colors shadow-sm shadow-indigo-600/30 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
        {{ showUploadForm ? 'Cancel' : 'Upload Document' }}
      </button>
    </div>

    <!-- Upload Form -->
    <div v-if="showUploadForm" class="px-6 sm:px-8 py-6 bg-slate-50 border-b border-slate-100">
      <form @submit.prevent="handleUpload" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
          <label class="block text-sm font-bold text-slate-700 mb-1">Document Type</label>
          <select v-model="uploadForm.type" class="block w-full rounded-xl border-slate-300 bg-white border py-2.5 px-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-900">
            <option value="Resume">Resume</option>
            <option value="Certificate">Certificate</option>
            <option value="Aadhaar">Aadhaar Card</option>
            <option value="ID Proof">Other ID Proof</option>
            <option value="Other">Other Document</option>
          </select>
        </div>
        <div class="flex-1 w-full">
          <label class="block text-sm font-bold text-slate-700 mb-1">Choose File</label>
          <input type="file" ref="fileInput" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-xl bg-white" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        </div>
        <button type="submit" :disabled="uploading" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-bold transition-colors shadow-sm disabled:opacity-50">
          {{ uploading ? 'Uploading...' : 'Save File' }}
        </button>
      </form>
    </div>

    <!-- Error/Success Messages -->
    <div v-if="errorMsg" class="mx-8 mt-6 bg-red-50 text-red-700 p-4 rounded-xl text-sm font-medium border border-red-100">
      {{ errorMsg }}
    </div>
    <div v-if="successMsg" class="mx-8 mt-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm font-medium border border-emerald-100">
      {{ successMsg }}
    </div>

    <div v-if="pending || uploading" class="p-12 text-center text-slate-500">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-4"></div>
      {{ uploading ? 'Uploading...' : 'Loading documents...' }}
    </div>
    
    <div v-else-if="!documents?.length" class="p-16 text-center">
      <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
      </div>
      <h4 class="text-lg font-bold text-slate-900 mb-1">No documents yet</h4>
      <p class="text-slate-500 mb-4">Upload your resume, certificates, or ID proofs.</p>
      <button @click="showUploadForm = true" class="text-indigo-600 font-bold hover:text-indigo-800">Upload Now</button>
    </div>

    <div v-else class="p-6 sm:p-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div v-for="doc in documents" :key="doc.id" 
          class="border rounded-2xl p-5 flex items-start gap-4 transition-all"
          :class="doc.is_default ? 'border-indigo-300 bg-indigo-50/30 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white'">
          
          <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
            :class="doc.is_default ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
          </div>
          
          <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start mb-1">
              <h4 class="font-bold text-slate-900 truncate">{{ doc.document_type || 'Resume' }}</h4>
              <span v-if="doc.is_default" class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Default Resume</span>
            </div>
            <p class="text-xs text-slate-500 mb-3">Uploaded {{ new Date(doc.created_at).toLocaleDateString() }}</p>
            
            <div class="flex gap-3">
              <a :href="doc.file_url" target="_blank" class="text-sm font-bold text-slate-700 hover:text-indigo-600">View</a>
              
              <button v-if="(doc.document_type === 'Resume' || !doc.document_type) && !doc.is_default" @click="makeDefault(doc.id)" class="text-sm font-bold text-slate-700 hover:text-indigo-600">
                Make Default
              </button>
              
              <button @click="deleteDoc(doc.id)" class="text-sm font-bold text-rose-600 hover:text-rose-800 ml-auto">
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()
const fileInput = ref<HTMLInputElement | null>(null)
const uploading = ref(false)
const errorMsg = ref('')
const successMsg = ref('')
const showUploadForm = ref(false)
const uploadForm = ref({ type: 'Resume' })

const { data: documents, pending, refresh } = await useFetch<any[]>('/api/applicant/documents', {
  headers: { Authorization: `Bearer ${authStore.token}` }
})

const handleUpload = async () => {
  const file = fileInput.value?.files?.[0]
  if (!file) {
    errorMsg.value = 'Please choose a file.'
    return
  }

  const formData = new FormData()
  formData.append('resume', file)
  formData.append('document_type', uploadForm.value.type)

  uploading.value = true
  errorMsg.value = ''
  successMsg.value = ''

  try {
    await $fetch('/api/applicant/resume', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: formData
    })
    successMsg.value = 'Document uploaded successfully!'
    await refresh()
    showUploadForm.value = false
  } catch (err: any) {
    errorMsg.value = err.data?.message || 'Failed to upload document.'
  } finally {
    uploading.value = false
    if (fileInput.value) fileInput.value.value = ''
  }
}

const makeDefault = async (id: number) => {
  try {
    await $fetch(`/api/applicant/documents/${id}/default`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    await refresh()
  } catch (err) {
    alert('Failed to set default document')
  }
}

const deleteDoc = async (id: number) => {
  if (!confirm('Are you sure you want to delete this document?')) return
  
  try {
    await $fetch(`/api/applicant/documents/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    await refresh()
  } catch (err) {
    alert('Failed to delete document')
  }
}
</script>