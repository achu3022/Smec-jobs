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
            <h2 class="text-xl font-black text-slate-900 mb-3 flex items-center gap-2">
              <span class="text-primary-600">{{ section.icon }}</span> {{ section.title }}
            </h2>
            <div class="text-slate-600 text-sm leading-relaxed space-y-3">
              <p v-for="(para, i) in section.content" :key="i">{{ para }}</p>
              <ul v-if="section.list" class="list-disc list-inside space-y-1.5 text-slate-600">
                <li v-for="(item, i) in section.list" :key="i">{{ item }}</li>
              </ul>
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
    icon: '🌐',
    title: '1. Use of the Platform',
    content: [
      'SMECJobs provides an online platform connecting job seekers and employers. You may use the platform only for lawful purposes and in accordance with these terms.',
      'You must be at least 18 years of age to create an account on SMECJobs.',
    ]
  },
  {
    icon: '👤',
    title: '2. Accounts & Registration',
    content: [
      'You are responsible for maintaining the confidentiality of your account credentials. All activity under your account is your responsibility.',
      'You agree to provide accurate and complete information when registering and to keep it updated.',
      'We reserve the right to suspend or terminate accounts that violate these terms or that contain false information.',
    ]
  },
  {
    icon: '🚫',
    title: '3. Prohibited Conduct',
    content: ['You agree not to:'],
    list: [
      'Post false, misleading, or fraudulent job listings or profiles.',
      'Harass, spam, or send unsolicited messages to other users.',
      'Scrape, copy, or redistribute platform content without permission.',
      'Attempt to gain unauthorised access to any part of the platform.',
      'Use the platform for any illegal activity or to facilitate illegal employment.',
    ]
  },
  {
    icon: '📄',
    title: '4. Job Listings & Applications',
    content: [
      'Employers are solely responsible for the accuracy and legality of their job listings. SMECJobs does not guarantee the quality, safety, or availability of any job posted.',
      'SMECJobs does not guarantee employment to any applicant. We facilitate connections but are not a party to any employment contract.',
    ]
  },
  {
    icon: '🎓',
    title: '5. Courses & Training',
    content: [
      'Course listings are provided by SMECLABS. Enrollment, fees, and delivery are governed by SMECLABS terms and conditions separately communicated at the time of enrollment.',
    ]
  },
  {
    icon: '©️',
    title: '6. Intellectual Property',
    content: [
      'All content, design, and technology on SMECJobs is owned by or licensed to SMECLABS. You may not reproduce, distribute, or create derivative works without explicit written permission.',
      'By uploading a resume or profile, you grant SMECJobs a non-exclusive licence to display and share that content with relevant employers on the platform.',
    ]
  },
  {
    icon: '⚖️',
    title: '7. Limitation of Liability',
    content: [
      'SMECJobs is provided "as is" without warranties of any kind. We are not liable for any indirect, incidental, or consequential damages arising from your use of the platform.',
      'Our total liability for any claim is limited to the amount you paid to access the platform in the three months prior to the claim.',
    ]
  },
  {
    icon: '✏️',
    title: '8. Changes to Terms',
    content: [
      'We may update these Terms of Service at any time. Continued use of the platform after changes are posted constitutes your acceptance of the revised terms.',
    ]
  },
  {
    icon: '🏛️',
    title: '9. Governing Law',
    content: [
      'These terms are governed by the laws of India. Any disputes arising from your use of SMECJobs shall be subject to the exclusive jurisdiction of the courts in Kerala, India.',
    ]
  },
]
</script>
