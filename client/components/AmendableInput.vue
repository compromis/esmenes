<template>
  <div
    :class="[
      'input-field',
      `input-${inputType}`,
      `input-${variant}`,
      `input-${size}`,
      spanClass,
      {
        'input-has-errors': hasErrors,
        'input-has-value': hasValue || labelOnTop,
      },
    ]"
  >
    <label
      :for="name"
      :class="['input-label', 'text-sm', { 'visually-hidden': noLabel }]"
    >
      {{ label }}
    </label>
    <input
      v-if="inputType === 'input'"
      :id="name"
      ref="input"
      :name="name"
      :value="value"
      v-bind="$attrs"
      :class="[
        'input',
        { 'w-100': block, 'focus-dark': focusDark && variant === 'float' },
      ]"
      :aria-describedby="error ? name + 'Errors' : null"
      @input="$emit('input', $event.target.value)"
      @focus="focused = true"
      @blur="focused = false"
    />
    <div v-else class="input-wrapper">
      <pre class="input input-ghost">{{ value }}</pre>
      <textarea
        v-if="inputType === 'textarea'"
        :id="name"
        ref="input"
        :name="name"
        :value="value"
        v-bind="$attrs"
        :class="[
          'input',
          { 'w-100': block, 'focus-dark': focusDark && variant === 'float' },
        ]"
        :aria-describedby="error ? name + 'Errors' : null"
        @input="$emit('input', $event.target.value)"
        @focus="focused = true"
        @blur="focused = false"
      />
    </div>
    <div
      v-if="hasErrors"
      :id="name + 'Errors'"
      class="field-errors mt-1 text-sm"
    >
      {{ error }}
    </div>
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      default: null,
    },
    label: {
      type: String,
      default: null,
    },
    noLabel: {
      type: Boolean,
      default: false,
    },
    labelOnTop: {
      type: Boolean,
      default: false,
    },
    variant: {
      type: String,
      default: 'default',
      validator: (value) => ['default', 'float'].includes(value),
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
    error: {
      type: String,
      default: '',
    },
    block: {
      type: Boolean,
      default: true,
    },
    focusDark: {
      type: Boolean,
      default: false,
    },
    span: {
      type: [String, Number, Object],
      default: '4',
    },
  },

  emits: ['update:modelValue'],

  data() {
    return {
      focused: false,
    }
  },

  computed: {
    inputType() {
      if (this.$attrs.type === 'textarea') {
        return 'textarea'
      }

      return 'input'
    },

    hasErrors() {
      return this.error.length > 0
    },

    hasValue() {
      return this.focused || !!this.value
    },

    spanClass() {
      const { span } = this

      if (!span) return null

      if (typeof span === 'object') {
        return span
      }

      return `span-${span}`
    },
  },
}
</script>

<style lang="scss" scoped>
.input {
  &-textarea {
    display: flex;
    flex-direction: column;

    --input-padding: 1rem 1.5rem 0.5rem 1.5rem;

    .input-label {
      position: relative;
      left: 0;
      top: 0;
      transform: none !important;
      padding: var(--input-padding);
      font-size: $text-sm;
    }

    .input {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 2;
      display: block;
      padding: var(--input-padding);
      padding-top: 0;
      font-size: var(--input-font-size);
      resize: none;
    }

    &:focus-within {
      background: var(--gray-50);

      .input {
        background: var(--gray-50);
      }
    }
  }

  &-wrapper {
    position: relative;
    flex-grow: 1;
  }

  &-ghost {
    position: static !important;
    margin: 0;
    min-height: 100px;
    padding-bottom: 2em !important;
  }

  &-has-errors.input-float {
    .field-errors {
      position: static;
      padding: 0.5rem 1.5rem;
    }
  }
}
</style>
