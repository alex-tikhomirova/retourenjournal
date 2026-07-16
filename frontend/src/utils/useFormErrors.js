import {ref} from 'vue'

/**
 * @typedef {Object.<string, string[]>} FormErrors
 */

/**
 * @typedef {object} FormErrorsHelper
 * @property {import('vue').Ref<FormErrors>} errors
 * @property {(name: string) => string[]} getErrors
 * @property {(name: string) => string} getError
 * @property {(name: string) => boolean} hasError
 * @property {() => void} clearErrors
 * @property {(name: string) => void} clearError
 * @property {(value?: FormErrors | null) => void} setErrors
 * @property {(responseOrError: object) => void} setErrorsFromResponse
 */

/**
 * @returns {FormErrorsHelper}
 */
export function useFormErrors() {
  const errors = /** @type {import('vue').Ref<FormErrors>} */ (ref({}))

  const getErrors = (name) => errors.value[name] ?? []

  const getError = (name) => getErrors(name)[0] ?? ''

  const hasError = (name) => getErrors(name).length > 0

  const clearErrors = () => {
    errors.value = {}
  }

  const clearError = (name) => {
    if (!hasError(name)) {
      return
    }

    const nextErrors = {...errors.value}
    delete nextErrors[name]
    errors.value = nextErrors
  }

  const setErrors = (value) => {
    errors.value = value ?? {}
  }

  const setErrorsFromResponse = (responseOrError) => {
    setErrors(responseOrError?.response?.data?.errors ?? responseOrError?.data?.errors)
  }

  return {
    errors,
    getErrors,
    getError,
    hasError,
    clearErrors,
    clearError,
    setErrors,
    setErrorsFromResponse,
  }
}
