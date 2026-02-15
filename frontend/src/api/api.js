import axios from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_URL ?? 'http://localhost:8000'

// отдельный инстанс (без baseURL) — для csrf-cookie, чтобы не ловить циклы
const raw = axios.create({
    baseURL: API_BASE_URL,
    withCredentials: true,
    headers: { Accept: 'application/json' },
})

// основной инстанс
export const api = axios.create({
    baseURL: API_BASE_URL,
    withCredentials: true,
    withXSRFToken: true,
    xsrfCookieName: 'XSRF-TOKEN',
    xsrfHeaderName: 'X-XSRF-TOKEN',
    headers: { Accept: 'application/json' },
})

let csrfInFlight = null

async function ensureCsrfCookie() {
    if (!csrfInFlight) {
        csrfInFlight = raw.get('/sanctum/csrf-cookie')
            .finally(() => { csrfInFlight = null })
    }
    return csrfInFlight
}

function needsCsrf(method) {
    // GET/HEAD/OPTIONS/TRACE обычно не требуют CSRF
    return ['post', 'put', 'patch', 'delete'].includes((method || '').toLowerCase())
}

// --- REQUEST interceptor ---
api.interceptors.request.use(async (config) => {
    if (needsCsrf(config.method)) {
        await ensureCsrfCookie()
    }
    return config
})

// --- RESPONSE interceptor ---
api.interceptors.response.use(
    (response) => response,
    async (error) => {
        // позже тут можно:
        // - 401: authStore.user = null + router.push('/login')
        // - 419: повторить csrf-cookie и ретраить 1 раз
        throw error
    }
)
