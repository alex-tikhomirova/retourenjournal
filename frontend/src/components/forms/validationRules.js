// Каждый валидатор возвращает true (ок) или строку с ошибкой
export const required = (msg = 'Обязательное поле') => (value) => {
    const ok = value !== null && value !== undefined && String(value).trim() !== ''
    return ok || msg
}

export const minLen = (n, msg) => (value) => {
    const v = value ?? ''
    return String(v).length >= n || (msg ?? `Минимум ${n} символов`)
}

export const email = (msg = 'Некорректный email') => (value) => {
    const v = String(value ?? '').trim()
    if (v === '') return true // пусть required решает пустоту
    const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)
    return ok || msg
}
