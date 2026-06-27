<template>
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-5xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
       <h3 class="text-2xl font-bold text-slate-900 mb-2">My Profile</h3>
    </div>
    
    <div v-if="profileSuccess" class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl font-medium border border-green-200">
      Profile updated successfully!
    </div>
    <div v-if="profileError" class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl font-medium border border-red-200">
      {{ profileError }}
    </div>

    <form @submit.prevent="saveProfile" class="space-y-12">
      
      <!-- Personal Information -->
      <section>
        <h4 class="text-xl font-bold text-slate-800 mb-6 border-b pb-2">Personal Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Profile Photo</label>
            <input type="file" @change="onPhotoChange" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Father's Name</label>
            <input type="text" v-model="profileForm.father_name" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Mother's Name</label>
            <input type="text" v-model="profileForm.mother_name" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Date of Birth</label>
            <input type="date" v-model="profileForm.dob" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Gender</label>
            <select v-model="profileForm.gender" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Marital Status</label>
            <select v-model="profileForm.marital_status" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
              <option value="Single">Single</option>
              <option value="Married">Married</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Nationality</label>
            <input type="text" v-model="profileForm.nationality" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Blood Group</label>
            <input type="text" v-model="profileForm.blood_group" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <!-- Documents -->
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Aadhaar Number</label>
            <input type="text" v-model="profileForm.aadhaar" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">PAN Number</label>
            <input type="text" v-model="profileForm.pan" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Passport Number</label>
            <input type="text" v-model="profileForm.passport" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Driving Licence</label>
            <input type="text" v-model="profileForm.driving_licence" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Voter ID</label>
            <input type="text" v-model="profileForm.voter_id" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
        </div>
      </section>

      <!-- Contact Information -->
      <section>
        <h4 class="text-xl font-bold text-slate-800 mb-6 border-b pb-2">Contact Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Mobile Number *</label>
            <input type="text" v-model="profileForm.mobile" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Alternate Mobile</label>
            <input type="text" v-model="profileForm.alt_mobile" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Current Address</label>
            <textarea v-model="profileForm.address_current" rows="2" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900"></textarea>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Permanent Address</label>
            <textarea v-model="profileForm.address_permanent" rows="2" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900"></textarea>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">District</label>
            <input type="text" v-model="profileForm.district" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">State</label>
            <input type="text" v-model="profileForm.state" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Country</label>
            <input type="text" v-model="profileForm.country" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">PIN Code</label>
            <input type="text" v-model="profileForm.pin_code" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
        </div>
      </section>

      <!-- Career Information -->
      <section>
        <h4 class="text-xl font-bold text-slate-800 mb-6 border-b pb-2">Career Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Total Experience (Years)</label>
            <input type="text" v-model="profileForm.total_experience" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Relevant Experience (Years)</label>
            <input type="text" v-model="profileForm.relevant_experience" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Current Salary (CTC) ₹</label>
            <input type="number" v-model="profileForm.current_salary" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Expected Salary (CTC) ₹</label>
            <input type="number" v-model="profileForm.expected_salary" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Notice Period</label>
            <input type="text" v-model="profileForm.notice_period" placeholder="e.g. 30 days" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div class="flex items-center mt-8">
            <input type="checkbox" id="immediate_joiner" v-model="profileForm.immediate_joiner" class="h-5 w-5 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
            <label for="immediate_joiner" class="ml-2 block text-sm font-bold text-slate-700">Immediate Joiner</label>
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Preferred Job Location</label>
            <input type="text" v-model="profileForm.pref_location" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Preferred Industry</label>
            <input type="text" v-model="profileForm.pref_industry" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Preferred Employment Type</label>
            <input type="text" v-model="profileForm.pref_employment_type" placeholder="e.g. Full-time, Remote" class="w-full rounded-xl border-slate-300 bg-slate-50 border py-3 px-4 focus:ring-primary-500 focus:border-primary-500 text-slate-900">
          </div>
          <div class="flex items-center mt-8">
            <input type="checkbox" id="willing_to_relocate" v-model="profileForm.willing_to_relocate" class="h-5 w-5 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
            <label for="willing_to_relocate" class="ml-2 block text-sm font-bold text-slate-700">Willing to Relocate</label>
          </div>
          <div class="flex items-center mt-8">
            <input type="checkbox" id="willing_to_travel" v-model="profileForm.willing_to_travel" class="h-5 w-5 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
            <label for="willing_to_travel" class="ml-2 block text-sm font-bold text-slate-700">Willing to Travel</label>
          </div>
        </div>
      </section>

      <!-- Future Sections (Skills, Languages, Projects, etc) will go here -->
      <section>
        <h4 class="text-xl font-bold text-slate-800 mb-6 border-b pb-2">Additional Details (Coming Soon)</h4>
        <p class="text-slate-500">Skills, Languages, Projects, Certifications, and Social Links features are currently being built.</p>
      </section>

      <div class="pt-4 flex justify-end border-t border-slate-100">
        <button type="submit" :disabled="isSavingProfile" class="bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold py-3 px-8 rounded-xl transition-colors">
          {{ isSavingProfile ? 'Saving...' : 'Save Profile' }}
        </button>
      </div>
    </form>
    
    <!-- Education Section -->
    <div class="mt-16 pt-8 border-t-2 border-slate-100">
      <h4 class="text-xl font-bold text-slate-800 mb-6 flex justify-between items-center">
        Education History
        <button @click="showAddEducation = true" class="text-sm bg-slate-900 text-white py-2 px-4 rounded-lg hover:bg-slate-800 transition-colors">+ Add Education</button>
      </h4>

      <!-- Existing Educations -->
      <div v-if="educations && educations.length" class="space-y-4">
        <div v-for="edu in educations" :key="edu.id" class="p-5 border border-slate-200 rounded-xl bg-slate-50 flex justify-between items-start shadow-sm">
          <div>
            <h5 class="font-bold text-slate-900 text-lg">{{ edu.qualification }} <span v-if="edu.course_name" class="font-medium text-slate-600">- {{ edu.course_name }}</span></h5>
            <p class="text-sm text-slate-600 mt-1"><span class="font-semibold">{{ edu.university }}</span> <span v-if="edu.passing_year">({{ edu.passing_year }})</span></p>
            <div class="flex gap-4 mt-2 text-xs text-slate-500 font-medium bg-white px-3 py-1.5 rounded-lg border border-slate-200 inline-flex">
              <span v-if="edu.branch">Branch: {{ edu.branch }}</span>
              <span v-if="edu.board">Board: {{ edu.board }}</span>
              <span v-if="edu.percentage">Score: {{ edu.percentage }}</span>
            </div>
          </div>
          <button @click="deleteEducation(edu.id)" class="text-red-500 hover:text-red-700 text-sm font-bold bg-white border border-red-100 hover:border-red-200 rounded-lg px-3 py-1.5 transition-colors">Delete</button>
        </div>
      </div>
      <div v-else class="text-sm text-slate-500 p-8 border-2 border-dashed rounded-xl border-slate-200 text-center bg-slate-50">
        No education added yet. Click "+ Add Education" to add your academic background.
      </div>

      <!-- Add Education Form inline -->
      <div v-if="showAddEducation" class="mt-6 p-6 border border-slate-200 rounded-xl bg-white shadow-sm ring-4 ring-primary-50">
        <h5 class="font-bold text-slate-800 mb-4 text-lg">Add New Education</h5>
        <form @submit.prevent="saveEducation" class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div><label class="block text-xs font-bold text-slate-700 mb-1">Qualification</label><input type="text" v-model="eduForm.qualification" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500" required></div>
          <div><label class="block text-xs font-bold text-slate-700 mb-1">Course Name</label><input type="text" v-model="eduForm.course_name" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500"></div>
          <div><label class="block text-xs font-bold text-slate-700 mb-1">Branch / Specialization</label><input type="text" v-model="eduForm.branch" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500"></div>
          <div><label class="block text-xs font-bold text-slate-700 mb-1">College / University</label><input type="text" v-model="eduForm.university" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500"></div>
          <div><label class="block text-xs font-bold text-slate-700 mb-1">Board</label><input type="text" v-model="eduForm.board" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500"></div>
          <div><label class="block text-xs font-bold text-slate-700 mb-1">Passing Year</label><input type="text" v-model="eduForm.passing_year" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500"></div>
          <div><label class="block text-xs font-bold text-slate-700 mb-1">Percentage / CGPA</label><input type="text" v-model="eduForm.percentage" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500"></div>
          
          <div class="md:col-span-2 flex justify-end gap-3 mt-4 pt-4 border-t border-slate-100">
            <button type="button" @click="showAddEducation = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancel</button>
            <button type="submit" :disabled="isSavingEdu" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors">{{ isSavingEdu ? 'Saving...' : 'Save Education' }}</button>
          </div>
        </form>
      </div>

    </div>

    <!-- Experience Section -->
    <div class="mt-16 pt-8 border-t-2 border-slate-100">
      <h4 class="text-xl font-bold text-slate-800 mb-6 flex justify-between items-center">
        Experience History
        <button @click="showAddExperience = true" class="text-sm bg-slate-900 text-white py-2 px-4 rounded-lg hover:bg-slate-800 transition-colors">+ Add Experience</button>
      </h4>

      <!-- Existing Experiences -->
      <div v-if="experiences && experiences.length" class="space-y-4">
        <div v-for="exp in experiences" :key="exp.id" class="p-5 border border-slate-200 rounded-xl bg-slate-50 flex justify-between items-start shadow-sm">
          <div>
            <h5 class="font-bold text-slate-900 text-lg">{{ exp.job_role }} <span v-if="exp.company" class="font-medium text-slate-600">at {{ exp.company }}</span></h5>
            <p class="text-sm text-slate-600 mt-1">
              <span class="font-semibold">{{ exp.year }}</span>
              <span v-if="exp.is_current" class="ml-2 bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">Currently Working Here</span>
            </p>
          </div>
          <button @click="deleteExperience(exp.id)" class="text-red-500 hover:text-red-700 text-sm font-bold bg-white border border-red-100 hover:border-red-200 rounded-lg px-3 py-1.5 transition-colors">Delete</button>
        </div>
      </div>
      <div v-else class="text-sm text-slate-500 p-8 border-2 border-dashed rounded-xl border-slate-200 text-center bg-slate-50">
        No experience added yet. Click "+ Add Experience" to add your work history.
      </div>

      <!-- Add Experience Form inline -->
      <div v-if="showAddExperience" class="mt-6 p-6 border border-slate-200 rounded-xl bg-white shadow-sm ring-4 ring-primary-50">
        <h5 class="font-bold text-slate-800 mb-4 text-lg">Add New Experience</h5>
        <form @submit.prevent="saveExperience" class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div><label class="block text-xs font-bold text-slate-700 mb-1">Job Role</label><input type="text" v-model="expForm.job_role" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500" required></div>
          <div><label class="block text-xs font-bold text-slate-700 mb-1">Company</label><input type="text" v-model="expForm.company" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500" required></div>
          <div><label class="block text-xs font-bold text-slate-700 mb-1">Year (e.g. 2020 - 2022)</label><input type="text" v-model="expForm.year" class="w-full rounded-lg border-slate-300 bg-slate-50 border py-2.5 px-3 text-sm focus:ring-primary-500 focus:border-primary-500"></div>
          <div class="flex items-center mt-6">
            <input type="checkbox" id="is_current" v-model="expForm.is_current" class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
            <label for="is_current" class="ml-2 block text-sm font-bold text-slate-700">Currently working here</label>
          </div>
          
          <div class="md:col-span-2 flex justify-end gap-3 mt-4 pt-4 border-t border-slate-100">
            <button type="button" @click="showAddExperience = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancel</button>
            <button type="submit" :disabled="isSavingExp" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors">{{ isSavingExp ? 'Saving...' : 'Save Experience' }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

// State
const profileForm = ref<any>({})
const profileSuccess = ref(false)
const profileError = ref('')
const isSavingProfile = ref(false)

const showAddEducation = ref(false)
const isSavingEdu = ref(false)
const eduForm = ref({ qualification: '', course_name: '', branch: '', university: '', board: '', passing_year: '', percentage: '' })
const educations = ref<any[]>([])

const showAddExperience = ref(false)
const isSavingExp = ref(false)
const expForm = ref({ job_role: '', company: '', year: '', is_current: false })
const experiences = ref<any[]>([])

// Fetch user profile data
onMounted(async () => {
  try {
    const data: any = await $fetch('http://127.0.0.1:8000/api/applicant/profile', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    if (data.profile) {
      profileForm.value = { ...data.profile }
      // Sync photo to auth store so widget shows it immediately
      if (data.profile.photo_url) {
        authStore.user = { ...authStore.user, photo: data.profile.photo_url }
      }
    }
    if (data.educations) {
      educations.value = data.educations
    }
    if (data.experiences) {
      experiences.value = data.experiences
    }
  } catch (e) {
    console.error('Failed to load profile', e)
  }
})

// Handlers
const onPhotoChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    profileForm.value.photo = target.files[0]
  }
}

const saveProfile = async () => {
  isSavingProfile.value = true
  profileSuccess.value = false
  profileError.value = ''
  
  try {
    const formData = new FormData()
    
    // Append all properties to FormData
    for (const key in profileForm.value) {
      const val = profileForm.value[key]
      if (val === null || val === undefined) continue
      // Only send 'photo' if it's a new File — skip the existing path string
      if (key === 'photo' && !(val instanceof File)) continue
      formData.append(key, val === true ? '1' : (val === false ? '0' : val))
    }

    const response = await $fetch<any>('http://127.0.0.1:8000/api/applicant/profile', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: formData
    })

    // Update auth store so the widget avatar reflects the new photo immediately
    if (response?.profile?.photo_url) {
      authStore.user = { ...authStore.user, photo: response.profile.photo_url }
    }
    
    profileSuccess.value = true
    setTimeout(() => profileSuccess.value = false, 3000)
    
  } catch (e: any) {
    profileError.value = e.data?.message || 'Failed to update profile'
  } finally {
    isSavingProfile.value = false
  }
}

const saveEducation = async () => {
  isSavingEdu.value = true
  try {
    const newEdu = await $fetch<any>('http://127.0.0.1:8000/api/applicant/education', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: eduForm.value
    })
    educations.value.push(newEdu)
    showAddEducation.value = false
    eduForm.value = { qualification: '', course_name: '', branch: '', university: '', board: '', passing_year: '', percentage: '' }
  } catch (e) {
    console.error('Failed to save education', e)
  } finally {
    isSavingEdu.value = false
  }
}

const deleteEducation = async (id: number) => {
  if (!confirm('Are you sure you want to delete this education entry?')) return
  try {
    await $fetch(`http://127.0.0.1:8000/api/applicant/education/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    educations.value = educations.value.filter(e => e.id !== id)
  } catch (e) {
    console.error('Failed to delete education', e)
  }
}

const saveExperience = async () => {
  isSavingExp.value = true
  try {
    const newExp = await $fetch<any>('http://127.0.0.1:8000/api/applicant/experience', {
      method: 'POST',
      headers: { Authorization: `Bearer ${authStore.token}` },
      body: expForm.value
    })
    experiences.value.push(newExp)
    showAddExperience.value = false
    expForm.value = { job_role: '', company: '', year: '', is_current: false }
  } catch (e) {
    console.error('Failed to save experience', e)
  } finally {
    isSavingExp.value = false
  }
}

const deleteExperience = async (id: number) => {
  if (!confirm('Are you sure you want to delete this experience entry?')) return
  try {
    await $fetch(`http://127.0.0.1:8000/api/applicant/experience/${id}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${authStore.token}` }
    })
    experiences.value = experiences.value.filter(e => e.id !== id)
  } catch (e) {
    console.error('Failed to delete experience', e)
  }
}
</script>