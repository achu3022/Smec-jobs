<template>
  <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
    <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-0 group-hover:opacity-50 transition-opacity blur-xl" :class="colorMap[color]?.bgLight"></div>
    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" :class="[colorMap[color]?.bg, colorMap[color]?.text]">
      <Icon :name="icon" class="w-7 h-7" />
    </div>
    <div class="z-10">
      <p class="text-sm font-bold text-slate-500 mb-1">{{ title }}</p>
      <h3 class="text-3xl font-extrabold text-slate-900">{{ value }}</h3>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{
  title: string
  value: string | number
  icon: string
  color?: string
}>(), {
  color: 'indigo'
})

const colorMap: Record<string, any> = {
  indigo: { bgLight: 'bg-indigo-100', bg: 'bg-indigo-50', text: 'text-indigo-600' },
  blue: { bgLight: 'bg-blue-100', bg: 'bg-blue-50', text: 'text-blue-600' },
  emerald: { bgLight: 'bg-emerald-100', bg: 'bg-emerald-50', text: 'text-emerald-600' },
  amber: { bgLight: 'bg-amber-100', bg: 'bg-amber-50', text: 'text-amber-600' },
  rose: { bgLight: 'bg-rose-100', bg: 'bg-rose-50', text: 'text-rose-600' },
  fuchsia: { bgLight: 'bg-fuchsia-100', bg: 'bg-fuchsia-50', text: 'text-fuchsia-600' },
}
</script>
