<template>
  <div class="space-y-8 pb-12 relative">
    
    <!-- Profile & Stats Header -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <div class="lg:col-span-1 bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -left-8 -bottom-8 w-24 h-24 bg-primary-500/20 rounded-full blur-xl"></div>
        
        <div class="relative z-10 flex flex-col items-center text-center">
          <div class="w-24 h-24 bg-white rounded-2xl border-4 border-slate-700 mb-4 flex items-center justify-center shadow-inner overflow-hidden">
            <img v-if="companyLogoPreview || (companyForm.logo && typeof companyForm.logo === 'string')" :src="companyLogoPreview || getLogoUrl(companyForm.logo)" class="w-full h-full object-cover" />
            <svg v-else class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
          </div>
          <h2 class="text-2xl font-bold mb-1">{{ companyForm.name || authStore.user?.name }}</h2>
          <p class="text-slate-300 text-sm mb-4 line-clamp-1">{{ companyForm.industry || 'Update Profile Below' }}</p>
          <span class="bg-primary-500/20 text-primary-200 border border-primary-500/30 px-4 py-1 rounded-full text-xs font-semibold tracking-wide uppercase">Employer</span>
        </div>
      </div>

      <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-center">
          <p class="text-sm font-medium text-slate-500 mb-1">Active Jobs Posted</p>
          <h3 class="text-4xl font-bold text-slate-900">{{ activeJobsCount }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-center">
          <p class="text-sm font-medium text-slate-500 mb-1">Total Candidates</p>
          <h3 class="text-4xl font-bold text-slate-900">{{ totalCandidates }}</h3>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-center">
          <p class="text-sm font-medium text-slate-500 mb-1">Candidates Accepted</p>
          <h3 class="text-4xl font-bold text-slate-900">{{ acceptedCandidates }}</h3>
        </div>
      </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="flex space-x-4 border-b border-slate-200">
      <button @click="activeTab = 'jobs'" :class="['py-3 px-6 font-bold text-sm border-b-2 transition-colors', activeTab === 'jobs' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700']">Manage Jobs</button>
      <button @click="activeTab = 'post'; editingJobId = null; resetJobForm();" :class="['py-3 px-6 font-bold text-sm border-b-2 transition-colors', activeTab === 'post' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700']">Post a Job</button>
      <button @click="activeTab = 'company'" :class="['py-3 px-6 font-bold text-sm border-b-2 transition-colors', activeTab === 'company' ? 'border-primary-600 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700']">Company Profile</button>
    </div>

    <!-- Manage Jobs Tab -->
    <div v-if="activeTab === 'jobs'" class="space-y-6">
      <div v-if="pendingJobs" class="p-12 flex justify-center h-64 items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600"></div>
      </div>
      
      <div v-else-if="!jobs?.length" class="bg-white rounded-3xl border border-slate-100 p-16 text-center">
        <h3 class="text-xl font-bold text-slate-900 mb-2">No jobs posted yet</h3>
        <button @click="activeTab = 'post'" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-xl mt-4">Post your first Job</button>
      </div>

      <div v-else class="space-y-6">
        <div v-for="job in jobs" :key="job.id" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden" :class="{'opacity-75 bg-slate-50': !job.is_active}">
          <div class="p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
              <div class="flex items-center gap-3 mb-1">
                <NuxtLink :to="`/jobs/${job.uuid}`" class="text-2xl font-bold text-slate-900 block md:hidden">{{ job.title }}</NuxtLink>
                <h3 class="text-2xl font-bold text-slate-900 hidden md:block">{{ job.title }}</h3>
                <span v-if="!job.is_active" class="bg-slate-200 text-slate-700 text-xs font-bold px-2 py-1 rounded">INACTIVE</span>
              </div>
              <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 font-medium">
                <span>{{ job.location }}</span>
                <span class="bg-slate-100 px-2 py-1 rounded-md text-slate-700">{{ job.job_type }}</span>
                <span class="text-primary-600 font-bold">{{ job.applications?.length || 0 }} Candidates</span>
              </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
              <button @click="toggleJobStatus(job)" class="text-sm font-semibold px-4 py-2 rounded-lg border transition-colors" :class="job.is_active ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50'">
                {{ job.is_active ? 'Deactivate' : 'Activate' }}
              </button>
              <button @click="editJob(job)" class="text-sm font-semibold px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                Edit
              </button>
              <button @click="toggleJobExpansion(job.id)" class="text-slate-500 hover:text-primary-600 font-semibold text-sm flex items-center bg-slate-50 hover:bg-primary-50 px-4 py-2 rounded-lg transition-colors">
                {{ expandedJobs[job.id] ? 'Hide Candidates' : 'View Candidates' }}
              </button>
            </div>
          </div>

          <!-- Candidates List -->
          <div v-if="expandedJobs[job.id]" class="border-t border-slate-100 bg-slate-50">
            <div v-if="!job.applications?.length" class="p-8 text-center text-slate-500 text-sm">No candidates have applied to this position yet.</div>
            <ul v-else class="divide-y divide-slate-200">
              <li v-for="app in job.applications" :key="app.id" class="p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4 hover:bg-white transition-colors">
                
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-600 overflow-hidden">
                    <img v-if="app.user?.applicantProfile?.photo" :src="getLogoUrl(app.user.applicantProfile.photo)" class="w-full h-full object-cover"/>
                    <span v-else>{{ app.user?.name ? app.user.name.charAt(0).toUpperCase() : 'U' }}</span>
                  </div>
                  <div>
                    <h4 class="font-bold text-slate-900">{{ app.user?.name }}</h4>
                    <p class="text-xs text-slate-500">{{ app.user?.email }} • Applied {{ new Date(app.created_at).toLocaleDateString() }}</p>
                  </div>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide"
                        :class="app.status?.toLowerCase() === 'pending' ? 'bg-yellow-100 text-yellow-800' : (app.status?.toLowerCase() === 'accepted' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')">
                    {{ app.status }}
                  </span>
                  
                  <NuxtLink :to="`/applications/${app.id}`" class="text-sm font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3 py-1.5 rounded-lg flex items-center">
                    View Profile
                  </NuxtLink>
                  <a v-if="app.resume?.file_path" :href="getResumeUrl(app.resume.file_path)" target="_blank" class="text-sm font-semibold text-primary-600 hover:text-primary-800 bg-primary-50 px-3 py-1.5 rounded-lg flex items-center">
                    Resume
                  </a>

                  <div v-if="app.status?.toLowerCase() === 'pending'" class="flex gap-2 border-l border-slate-200 pl-3 ml-1">
                    <button @click="updateStatus(app, 'Accepted')" :disabled="isUpdatingStatus" class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded-lg transition-colors" title="Accept Candidate">Accept</button>
                    <button @click="updateStatus(app, 'Rejected')" :disabled="isUpdatingStatus" class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-lg transition-colors" title="Reject Candidate">Reject</button>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Post / Edit Job Tab -->
    <div v-if="activeTab === 'post'" class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-4xl mx-auto">
      <div class="mb-8">
        <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ editingJobId ? 'Edit Job Listing' : 'Create a New Job Listing' }}</h3>
      </div>
      
      <div v-if="jobSuccess" class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl font-medium border border-green-200 flex items-center">
        Job {{ editingJobId ? 'updated' : 'published' }} successfully!
      </div>
      <div v-if="jobError" class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl font-medium border border-red-200">
        {{ jobError }}
      </div>

      <form @submit.prevent="postJob" @input="jobSuccess = false; jobError = ''" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Job Title</label>
            <input type="text" v-model="jobForm.title" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Category</label>
            <select v-model="jobForm.job_category_id" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required>
              <option value="" disabled>Select a category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Location</label>
            <input type="text" v-model="jobForm.location" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Job Type</label>
            <select v-model="jobForm.job_type" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required>
              <option value="Full-time">Full-time</option><option value="Part-time">Part-time</option><option value="Contract">Contract</option><option value="Internship">Internship</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Min Salary (₹) - Optional</label>
            <input type="number" v-model="jobForm.salary_min" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Max Salary (₹) - Optional</label>
            <input type="number" v-model="jobForm.salary_max" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Experience Level</label>
            <select v-model="jobForm.experience_level" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500">
              <option value="">Any</option><option value="Entry Level">Entry Level</option><option value="Mid Level">Mid Level</option><option value="Senior Level">Senior Level</option><option value="Manager">Manager</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Education Level</label>
            <select v-model="jobForm.education_level" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500">
              <option value="">Any</option><option value="High School">High School</option><option value="Bachelor's Degree">Bachelor's Degree</option><option value="Master's Degree">Master's Degree</option><option value="PhD">PhD</option>
            </select>
          </div>
        </div>
        
        <div class="flex items-center">
          <input type="checkbox" id="is_remote" v-model="jobForm.is_remote" class="h-5 w-5 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
          <label for="is_remote" class="ml-2 block text-sm font-bold text-slate-700">This is a Remote position</label>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Full Description</label>
          <textarea v-model="jobForm.description" rows="6" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required></textarea>
        </div>
        
        <div class="pt-4 border-t border-slate-100 flex justify-end">
          <button type="submit" :disabled="isPosting" class="bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold py-3.5 px-8 rounded-xl shadow-md w-full sm:w-auto">
            {{ isPosting ? 'Saving...' : (editingJobId ? 'Update Job' : 'Publish Job') }}
          </button>
        </div>
      </form>
    </div>

    <!-- Company Profile Tab -->
    <div v-if="activeTab === 'company'" class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-4xl mx-auto">
      <div class="mb-8"><h3 class="text-2xl font-bold text-slate-900 mb-2">Company Profile</h3></div>
      
      <div v-if="companySuccess" class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl font-medium border border-green-200 flex items-center">Company profile saved successfully!</div>
      <div v-if="companyError" class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl font-medium border border-red-200">{{ companyError }}</div>

      <form @submit.prevent="saveCompany" class="space-y-6">
        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Company Logo</label>
          <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 flex shrink-0 items-center justify-center">
              <img v-if="companyLogoPreview" :src="companyLogoPreview" class="w-full h-full object-cover" />
              <img v-else-if="companyForm.logo && typeof companyForm.logo === 'string'" :src="getLogoUrl(companyForm.logo)" class="w-full h-full object-cover" />
            </div>
            <div>
               <label class="cursor-pointer bg-white border border-slate-300 font-bold text-slate-700 py-2 px-4 rounded-lg shadow-sm">
                  <span>Change Logo</span>
                  <input type="file" @change="onLogoChange" class="sr-only" accept="image/*">
                </label>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Company Name</label>
            <input type="text" v-model="companyForm.name" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Industry</label>
            <input type="text" v-model="companyForm.industry" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Headquarters / Location</label>
            <input type="text" v-model="companyForm.location" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Website URL</label>
            <input type="url" v-model="companyForm.website" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500">
          </div>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Company Description</label>
          <textarea v-model="companyForm.description" rows="4" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500" required></textarea>
        </div>
        
        <div class="pt-4 flex justify-end border-t border-slate-100">
          <button type="submit" :disabled="isSavingCompany" class="bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold py-3 px-8 rounded-xl">
            {{ isSavingCompany ? 'Saving...' : 'Save Profile' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, onErrorCaptured } from 'vue'
import { useAuthStore } from '~/stores/auth'

const renderError = ref<string | null>(null)

const authStore = useAuthStore()
const activeTab = ref('jobs')

// Jobs Fetching
const { data: jobs, pending: pendingJobs, refresh: refreshJobs } = await useFetch<any[]>('http://127.0.0.1:8000/api/employer/jobs', {
  headers: { Authorization: `Bearer ${authStore.token}` },
  server: false
})

const { data: categories } = await useFetch<any[]>('http://127.0.0.1:8000/api/categories', { server: false })

// Stats
const activeJobsCount = computed(() => jobs.value?.filter(j => j.is_active).length || 0)
const totalCandidates = computed(() => {
  if (!jobs.value) return 0
  return jobs.value.reduce((total, job) => total + (job.applications?.length || 0), 0)
})
const acceptedCandidates = computed(() => {
  if (!jobs.value) return 0
  return jobs.value.reduce((total, job) => {
    return total + (job.applications?.filter((app: any) => app.status === 'Accepted').length || 0)
  }, 0)
})

// Expanded Jobs logic
const expandedJobs = ref<Record<number, boolean>>({})
const toggleJobExpansion = (jobId: number) => {
  expandedJobs.value[jobId] = !expandedJobs.value[jobId]
}

// Company Profile Logic
const isSavingCompany = ref(false)
const companySuccess = ref(false)
const companyError = ref('')
const companyForm = reactive({ name: '', industry: '', location: '', description: '', website: '', logo: null as File | string | null })
const companyLogoPreview = ref<string | null>(null)

const fetchCompanyProfile = async () => {
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/employer/company', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    if (res.company) {
      companyForm.name = res.company.name || ''
      companyForm.industry = res.company.industry || ''
      companyForm.location = res.company.location || ''
      companyForm.description = res.company.description || ''
      companyForm.website = res.company.website || ''
      companyForm.logo = res.company.logo || null
    }
  } catch (e) {
    console.error('Failed to fetch company profile', e)
  }
}

onMounted(() => { fetchCompanyProfile() })

const getLogoUrl = (path: string) => `http://127.0.0.1:8000/storage/${path}`
const getResumeUrl = (path: string) => `http://127.0.0.1:8000/storage/${path}`

const onLogoChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    const file = target.files[0]
    companyForm.logo = file
    companyLogoPreview.value = URL.createObjectURL(file)
  }
}

const saveCompany = async () => {
  isSavingCompany.value = true
  companySuccess.value = false
  companyError.value = ''
  const formData = new FormData()
  formData.append('name', companyForm.name)
  formData.append('industry', companyForm.industry)
  formData.append('location', companyForm.location)
  formData.append('description', companyForm.description)
  formData.append('website', companyForm.website || '')
  if (companyForm.logo instanceof File) {
    formData.append('logo', companyForm.logo)
  } else {
    formData.append('logo_path', companyForm.logo || '')
  }
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/employer/company', {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: formData
    })
    companySuccess.value = true
    if (res.company?.logo) companyForm.logo = res.company.logo
    await refreshJobs()
  } catch (e: any) {
    companyError.value = e.data?.message || 'Failed to update company profile.'
  } finally {
    isSavingCompany.value = false
  }
}

// Job Post & Edit Logic
const isPosting = ref(false)
const jobSuccess = ref(false)
const jobError = ref('')
const editingJobId = ref<number | null>(null)

const jobForm = reactive({
  title: '', location: '', job_type: 'Full-time', job_category_id: '', description: '',
  salary_min: null as number|null, salary_max: null as number|null, experience_level: '', education_level: '', is_remote: false
})

const resetJobForm = () => {
  Object.assign(jobForm, {
    title: '', location: '', job_type: 'Full-time', job_category_id: '', description: '',
    salary_min: null, salary_max: null, experience_level: '', education_level: '', is_remote: false
  })
}

const editJob = (job: any) => {
  editingJobId.value = job.id
  Object.assign(jobForm, {
    title: job.title, location: job.location, job_type: job.job_type, job_category_id: job.job_category_id, description: job.description,
    salary_min: job.salary_min, salary_max: job.salary_max, experience_level: job.experience_level, education_level: job.education_level, is_remote: !!job.is_remote
  })
  activeTab.value = 'post'
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const postJob = async () => {
  isPosting.value = true
  jobSuccess.value = false
  jobError.value = ''
  try {
    if (editingJobId.value) {
      await $fetch(`http://127.0.0.1:8000/api/employer/jobs/${editingJobId.value}`, {
        method: 'PUT',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: jobForm
      })
    } else {
      await $fetch('http://127.0.0.1:8000/api/employer/jobs', {
        method: 'POST',
        headers: { Authorization: `Bearer ${authStore.token}` },
        body: jobForm
      })
    }
    jobSuccess.value = true
    if (!editingJobId.value) resetJobForm()
    await refreshJobs()
  } catch (e: any) {
    jobError.value = e.data?.message || 'Failed to save job listing.'
  } finally {
    isPosting.value = false
  }
}

const toggleJobStatus = async (job: any) => {
  try {
    await $fetch(`http://127.0.0.1:8000/api/employer/jobs/${job.id}/toggle-status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    await refreshJobs()
  } catch (e) {
    console.error('Failed to toggle status', e)
  }
}

// Application Status Management
const isUpdatingStatus = ref(false)
const updateStatus = async (application: any, status: string) => {
  isUpdatingStatus.value = true
  try {
    await $fetch(`http://127.0.0.1:8000/api/employer/applications/${application.id}/status`, {
      method: 'PUT',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: { status }
    })
    application.status = status
  } catch (e) {
    console.error('Failed to update status', e)
    alert('Failed to update application status.')
  } finally {
    isUpdatingStatus.value = false
  }
}

</script>
