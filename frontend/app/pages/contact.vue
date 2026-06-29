<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-primary-600/20 text-primary-300 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-6 border border-primary-500/30">Get in Touch</span>
        <h1 class="text-4xl md:text-5xl font-black mb-6">Contact <span class="text-primary-400">Us</span></h1>
        <p class="text-lg text-slate-300 max-w-xl mx-auto">Have a question, feedback, or need support? We're here to help. Reach out and we'll get back to you shortly.</p>
      </div>
    </section>

    <section class="py-16 bg-slate-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-5 gap-10">

        <!-- Contact Info -->
        <div class="lg:col-span-2 space-y-6">
          <div v-for="info in contactInfo" :key="info.label" class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-start gap-4">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center flex-shrink-0 text-xl shadow-inner">
              <Icon :name="info.icon" class="w-6 h-6" />
            </div>
            <div>
              <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ info.label }}</p>
              <p class="text-slate-800 font-semibold text-sm">{{ info.value }}</p>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="lg:col-span-3 bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
          <h2 class="text-2xl font-black text-slate-900 mb-6">Send us a message</h2>

          <div v-if="submitted" class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-6 text-center">
            <div class="mb-3 text-emerald-500">
              <Icon name="lucide:check-circle" class="w-10 h-10 mx-auto" />
            </div>
            <h3 class="font-bold text-lg mb-1">Message Sent!</h3>
            <p class="text-sm">Thanks for reaching out. We'll get back to you within 1–2 business days.</p>
          </div>

          <form v-else @submit.prevent="submitForm" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Your Name *</label>
                <input v-model="form.name" type="text" required placeholder="John Doe" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
              </div>
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Email Address *</label>
                <input v-model="form.email" type="email" required placeholder="you@example.com" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
              </div>
            </div>
            <div>
              <label class="block text-sm font-bold text-slate-700 mb-1.5">Subject *</label>
              <select v-model="form.subject" required class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition">
                <option value="" disabled>Select a topic</option>
                <option>Job Seeker Support</option>
                <option>Employer / Hiring Inquiry</option>
                <option>Course Enrollment</option>
                <option>Technical Issue</option>
                <option>Partnership</option>
                <option>Other</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-bold text-slate-700 mb-1.5">Message *</label>
              <textarea v-model="form.message" required rows="5" placeholder="Tell us how we can help..." class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition resize-none"></textarea>
            </div>
            <div v-if="error" class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3">{{ error }}</div>
            <button type="submit" :disabled="loading" class="w-full bg-primary-600 hover:bg-primary-700 disabled:opacity-60 text-white font-bold py-3 rounded-xl transition-colors shadow-sm">
              {{ loading ? 'Sending…' : 'Send Message' }}
            </button>
          </form>
        </div>

      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

useHead({ title: 'Contact Us — SMECJobs' })

const form = ref({ name: '', email: '', subject: '', message: '' })
const loading = ref(false)
const submitted = ref(false)
const error = ref('')

const contactInfo = [
  { icon: 'lucide:map-pin', label: 'Address', value: 'SMECLABS, Kerala, India' },
  { icon: 'lucide:mail', label: 'Email', value: 'support@smecjobs.com' },
  { icon: 'lucide:phone', label: 'Phone', value: '+91 98765 43210' },
  { icon: 'lucide:clock', label: 'Working Hours', value: 'Mon – Sat, 9:00 AM – 6:00 PM' },
]

const submitForm = async () => {
  loading.value = true
  error.value = ''
  try {
    await $fetch('http://127.0.0.1:8000/api/contact', {
      method: 'POST',
      body: form.value
    })
    submitted.value = true
  } catch (e: any) {
    error.value = e?.data?.message || 'Something went wrong. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>
