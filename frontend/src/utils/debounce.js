/**
 * @template {(...args: any[]) => any} T
 * @param {T} fn
 * @param {number} [delay]
 * @returns {(...args: Parameters<T>) => void}
 */
export function debounce(fn, delay = 300) {
  let timer = null

  return (...args) => {
    if (timer) {
      clearTimeout(timer)
    }

    timer = setTimeout(() => {
      fn(...args)
    }, delay)
  }
}
