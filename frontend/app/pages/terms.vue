<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-primary-600/20 text-primary-300 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-6 border border-primary-500/30">Legal</span>
        <h1 class="text-4xl md:text-5xl font-black mb-4">Terms of <span class="text-primary-400">Service</span></h1>
        <p class="text-slate-400 text-sm">Last updated: June 2026</p>
      </div>
    </section>

    <section class="py-16 bg-white">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">

          <!-- Intro -->
          <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6">
            <p class="text-slate-700 text-sm leading-relaxed">By accessing or using SMECJobs, you agree to be bound by these Terms of Service. Please read them carefully before using the platform. If you do not agree, please do not use the service.</p>
          </div>

          <div v-for="section in sections" :key="section.title" class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 mb-4">
              <Icon :name="section.icon" class="w-6 h-6" />
            </div>
            <h2 class="text-xl font-black text-slate-900 mb-3">{{ section.title }}</h2>
            <div class="text-slate-600 text-sm leading-relaxed space-y-3">
              <p>{{ section.content }}</p>
            </div>
          </div>

          <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center">
            <p class="text-slate-600 text-sm">For questions about these terms, <NuxtLink to="/contact" class="text-primary-600 font-bold hover:underline">contact our team</NuxtLink>.</p>
          </div>

        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
useHead({ title: 'Terms of Service — SMECJobs' })

const sections = [
  {
    icon: 'lucide:globe',
    title: '1. Acceptance of Terms',
    content: 'By accessing or using the SMEC Jobs platform, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you may not use our services.'
  },
  {
    icon: 'lucide:user',
    title: '2. User Accounts',
    content: 'You are responsible for maintaining the confidentiality of your account credentials. You agree to provide accurate, current, and complete information during registration and to update such information to keep it accurate.'
  },
  {
    icon: 'lucide:ban',
    title: '3. Acceptable Use',
    content: 'You agree not to use the platform for any unlawful purpose, to post false or misleading information, to harass other users, or to attempt to gain unauthorized access to our systems.'
  },
  {
    icon: 'lucide:file-text',
    title: '4. Content and Postings',
    content: 'Users are solely responsible for the content they post (resumes, job listings). SMEC Jobs reserves the right to remove any content that violates these terms or is deemed inappropriate.'
  },
  {
    icon: 'lucide:graduation-cap',
    title: '5. Training and Courses',
    content: 'Enrollment in SMECLABS courses through the platform is subject to separate terms and conditions provided at the time of enrollment. Course availability and pricing are subject to change.'
  },
  {
    icon: 'lucide:scale',
    title: '6. Limitation of Liability',
    content: 'SMEC Jobs acts as a platform to connect employers and candidates. We do not guarantee employment, the accuracy of job listings, or the quality of candidates. We are not liable for any damages arising from your use of the platform.'
  },
  {
    icon: 'lucide:edit',
    title: '7. Modifications to Terms',
    content: 'We reserve the right to modify these terms at any time. Continued use of the platform following any changes constitutes your acceptance of the new terms.'
  },
  {
    icon: 'lucide:building',
    title: '8. Governing Law',
    content: 'These terms shall be governed by and construed in accordance with the laws of India. Any disputes shall be subject to the exclusive jurisdiction of the courts in Kerala, India.'
  }
]
</script>
