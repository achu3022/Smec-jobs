<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-primary-600/20 text-primary-300 text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full mb-6 border border-primary-500/30">Legal</span>
        <h1 class="text-4xl md:text-5xl font-black mb-4">Privacy <span class="text-primary-400">Policy</span></h1>
        <p class="text-slate-400 text-sm">Last updated: June 2026</p>
      </div>
    </section>

    <section class="py-16 bg-white">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-slate max-w-none space-y-10">

          <div v-for="section in sections" :key="section.title" class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
            <h2 class="text-xl font-black text-slate-900 mb-3 flex items-center gap-2">
              <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <Icon :name="section.icon" class="w-5 h-5" />
              </div>
              {{ section.title }}
            </h2>
            <div class="text-slate-600 text-sm leading-relaxed space-y-3">
              <p>{{ section.content }}</p>
            </div>
          </div>

          <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6 text-center">
            <p class="text-slate-700 text-sm">Questions about this policy? <NuxtLink to="/contact" class="text-primary-600 font-bold hover:underline">Contact us</NuxtLink></p>
          </div>

        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
useHead({ title: 'Privacy Policy — SMECJobs' })

const sections = [
  {
    title: 'Information Collection',
    icon: 'lucide:database',
    content: 'We collect personal information such as your name, email, and resume when you create an account. This information is used strictly to provide you with job matching services.'
  },
  {
    title: 'Data Sharing',
    icon: 'lucide:share-2',
    content: 'Your profile information is shared with employers when you apply for a job or if you opt-in to make your profile public. We do not sell your personal data to third parties.'
  },
  {
    title: 'Policy Updates',
    icon: 'lucide:refresh-cw',
    content: 'We may update this Privacy Policy from time to time. Continued use of SMECJobs after changes are posted constitutes your acceptance of the updated policy.'
  }
]
</script>
