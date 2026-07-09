import {ref, watch} from 'vue'

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



const resetFilters = (filters, defaults) => {
  for (const key of Object.keys(filters.value)) {
    delete filters.value[key]
  }
  Object.assign(filters.value, clone(defaults))
}

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

const mergeValue = (defaultValue, storedValue) => {
  if (isPlainObject(defaultValue) && isPlainObject(storedValue)) {
    return mergeFilters(defaultValue, storedValue)
  }

  return storedValue
}

const clone = (value) => {
  if (typeof structuredClone === 'function') {
    return structuredClone(value)
  }
  return JSON.parse(JSON.stringify(value))
}

const isPlainObject = (value) => Object.prototype.toString.call(value) === '[object Object]'
