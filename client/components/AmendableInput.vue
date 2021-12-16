<template>
  <div
    ref="wrapper"
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
        :style="{ height }"
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
    minHeight: {
      type: Number,
      default: 0,
    },
  },

  emits: ['update:modelValue'],

  data() {
    return {
      focused: false,
      initialHeight: 0,
      height: 0,
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

  watch: {
    value() {
      this.calcHeight()
    },
  },

  mounted() {
    this.initialHeight = Math.max(
      this.minHeight,
      this.$refs.wrapper.offsetHeight
    )
    this.calcHeight()
  },

  methods: {
    calcHeight() {
      const inputHeight = this.$refs.input.scrollHeight
      const height = Math.max(this.initialHeight, inputHeight)
      this.height = height + 'px'
    },
  },
}
</script>

<style lang="scss" scoped>
.input {
  &-textarea {
    display: flex;
    flex-direction: column;

    --b-input-padding: 1rem 1.5rem 0.5rem 1.5rem;

    .input-label {
      position: relative;
      left: 0;
      top: 0;
      transform: none !important;
      padding: var(--b-input-padding);
      font-size: $text-sm;
    }

    .input {
      display: block;
      padding: var(--b-input-padding);
      padding-top: 0;
      font-size: var(--b-input-font-size);
      resize: none;
      overflow: hidden;
    }

    &:focus-within {
      background: var(--b-gray-50);

      .input {
        background: var(--b-gray-50);
      }
    }
  }

  &-wrapper {
    position: relative;
    display: flex;
    flex-grow: 1;
  }

  &-has-errors.input-float {
    .field-errors {
      position: static;
      padding: 0.5rem 1.5rem;
    }
  }
}
</style>
