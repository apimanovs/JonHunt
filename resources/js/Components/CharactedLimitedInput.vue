<!-- resources/js/Components/CharacterLimitedInput.vue -->
<script setup>
import { computed, toRefs } from 'vue';

const props = defineProps({
  modelValue: String,
  label: String,
  id: String,
  max: {
    type: Number,
    default: 300,
  },
  textarea: {
    type: Boolean,
    default: false,
  }
});

const emit = defineEmits(['update:modelValue']);
const { modelValue, max } = toRefs(props);

const charCount = computed(() => modelValue.value?.length || 0);
</script>

<template>
  <div class="mb-6">

    <component
    :is="textarea ? 'textarea' : 'input'"
    :id="id"
    :maxlength="max"
    :value="modelValue"
    @input="emit('update:modelValue', $event.target.value)"
    :class="[
      'w-full',
      textarea ? 'textarea textarea-bordered' : 'input input-bordered',
    ]"
  />

    <div class="text-right text-sm text-gray-500 mt-1">
      {{ charCount }} / {{ max }} characters
    </div>
  </div>
</template>
