import {ref, watch} from 'vue'

/**
 * @template {Record<string, any>} T
 * @typedef {import('vue').Ref<T> & {reset: () => void}} StoredListState
 */

/**
 * @template {Record<string, any>} T
 * @param {string} storageKey
 * @param {T} defaults
 * @returns {StoredListState<T>}
 */
export function useStoredListState(storageKey, defaults) {
  const state = ref(loadStoredState(storageKey, defaults))

  Object.defineProperty(state, 'reset', {
    value: () => resetFilters(state, defaults),
  })

  watch(() => state.value.filter, () => {

    state.value.pagination.current_page = 1
  }, { deep: true })

  watch(() => state.value.sort, () => {
    state.value.pagination.current_page = 1
  })

  watch(state, (value) => {
    if (typeof localStorage === 'undefined') {
      return
    }

    localStorage.setItem(storageKey, JSON.stringify(value))
  }, { deep: true })

  return state
}

/**
 * @template {Record<string, any>} T
 * @param {string} storageKey
 * @param {T} defaults
 * @returns {T}
 */
const loadStoredState = (storageKey, defaults) => {
  const defaultState = clone(defaults)

  if (typeof localStorage === 'undefined') {
    return defaultState
  }

  try {
    const raw = localStorage.getItem(storageKey)
    if (!raw) {
      return defaultState
    }

    return mergeFilters(defaultState, JSON.parse(raw))
  } catch (e) {
    return defaultState
  }
}


/**
 * @template {Record<string, any>} T
 * @param {import('vue').Ref<T>} filters
 * @param {T} defaults
 * @returns {void}
 */
const resetFilters = (filters, defaults) => {
  for (const key of Object.keys(filters.value)) {
    delete filters.value[key]
  }
  Object.assign(filters.value, clone(defaults))
}

/**
 * @template {Record<string, any>} T
 * @param {T} defaults
 * @param {unknown} stored
 * @returns {T}
 */
const mergeFilters = (defaults, stored) => {
  if (!isPlainObject(defaults) || !isPlainObject(stored)) {
    return defaults
  }

  const result = {
    ...stored,
    ...defaults,
  }

  for (const key of Object.keys(stored)) {
    result[key] = mergeValue(defaults[key], stored[key])
  }

  return result
}

/**
 * @param {unknown} defaultValue
 * @param {unknown} storedValue
 * @returns {unknown}
 */
const mergeValue = (defaultValue, storedValue) => {
  if (isPlainObject(defaultValue) && isPlainObject(storedValue)) {
    return mergeFilters(defaultValue, storedValue)
  }

  return storedValue
}

/**
 * @template T
 * @param {T} value
 * @returns {T}
 */
const clone = (value) => {
  if (typeof structuredClone === 'function') {
    return structuredClone(value)
  }
  return JSON.parse(JSON.stringify(value))
}

/**
 * @param {unknown} value
 * @returns {value is Record<string, any>}
 */
const isPlainObject = (value) => Object.prototype.toString.call(value) === '[object Object]'
