import {computed, ref} from 'vue'

/**
 * @typedef {Object.<string, string[]>} FormErrors
 */

/**
 * @typedef {object} FormErrorsHelper
 * @property {import('vue').Ref<FormErrors>} errors
 * @property {(name: string) => string[]} getErrors
 * @property {(name: string) => string} getError
 * @property {() => string[]} getAllErrors
 * @property {() => string} getFirstError
 * @property {import('vue').ComputedRef<string>} errorText
 * @property {(name: string) => boolean} hasError
 * @property {() => void} clearErrors
 * @property {(name: string) => void} clearError
 * @property {(value?: FormErrors | null) => void} setErrors
 * @property {(responseOrError: object) => void} setErrorsFromResponse
 * @property {<T>(request: () => Promise<T>) => Promise<T | undefined>} handleRequest
 */

/**
 * @returns {FormErrorsHelper}
 */
export function useFormErrors() {
  const errors = /** @type {import('vue').Ref<FormErrors>} */ (ref({}))

  const getErrors = (name) => errors.value[name] ?? []

  const getError = (name) => getErrors(name)[0] ?? ''

  const getAllErrors = () => Object.values(errors.value).flat()

  const getFirstError = () => getAllErrors()[0] ?? ''

  const errorText = computed(getFirstError)

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
    const data = responseOrError?.response?.data ?? responseOrError?.data

    if (data?.errors) {
      setErrors(data.errors)
      return
    }

    const message = data?.message ?? responseOrError?.message
    setErrors(message ? {_general: [message]} : {})
  }

  const handleRequest = async (request) => {
    clearErrors()

    try {
      return await request()
    } catch (error) {
      setErrorsFromResponse(error)
      return undefined
    }
  }

  return {
    errors,
    getErrors,
    getError,
    getAllErrors,
    getFirstError,
    errorText,
    hasError,
    clearErrors,
    clearError,
    setErrors,
    setErrorsFromResponse,
    handleRequest,
  }
}
