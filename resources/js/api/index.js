import endpoints from '@api/endpoints.js';

const API = {
    ...endpoints,
    // Métodos helpers reutilizables
    get: async (url, headersData, params) => {

        if (params && typeof params !== 'object') {
            throw new Error('params must be JSON object');
        }

        let newUrl = new URL(url);

        Object.keys(params).forEach(key => {
            newUrl.searchParams.append(key, params[key]);
        });

        url = newUrl.toString();

        try {
            console.log('GET: ' + url);

            const response = await fetch(url, {
                method: 'GET',
                headers: headersData
            });

            if (response.status === 401) throw new Error('Unauthorized');
            if (response.status === 404) throw new Error('Not Found');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            return response.json();
        } catch (error) {
            // Log técnica (Sentry, console, etc.)
            logErrorToService(error);

            // Normaliza errores
            if (error.name === 'TypeError') {
                throw new Error('Network connection failed');
            }

            throw error;
        }
    },
    post: async (url, headersData, bodyData) => {
        console.log(url, bodyData, headersData);
        const validation = validateRequest({ bodyData, headersData });
        if (validation !== true) {
            throw new Error(`Validación fallida: ${validation}`);
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: headersData,
                body: bodyData
            });

            if (response.status === 401) throw new Error('Unauthorized');
            if (response.status === 404) throw new Error('Not Found');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            //console.log(response);

            return response.json();
        } catch (error) {
            // Log técnica (Sentry, console, etc.)
            logErrorToService(error);

            // Normaliza errores
            if (error.name === 'TypeError') {
                throw new Error('Network connection failed');
            }

            throw error;
        }
    },
    delete: async (url, headersData) => {
        console.log(url, headersData);
        const validation = validateRequest({ headersData });
        if (validation !== true) {
            throw new Error(`Validación fallida: ${validation}`);
        }

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: headersData,
            });

            if (response.status === 401) throw new Error('Unauthorized');
            if (response.status === 404) throw new Error('Not Found');
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            //console.log(response);

            return response.json();
        } catch (error) {
            // Log técnica (Sentry, console, etc.)
            //logErrorToService(error);

            // Normaliza errores
            if (error.name === 'TypeError') {
                throw new Error('Network connection failed');
            }

            throw error;
        }
    }
};

/**
 * Valida body y headers antes de enviar una petición
 * @param {object} config - Configuración de la petición
 * @param {object} config.body - Body a validar
 * @param {object} config.headers - Headers a validar
 * @returns {boolean|string} True si es válido, mensaje de error si no
 */
function validateRequest({ body, headers }) {
    // Validación del body
    if (body && typeof body !== 'object') {
        return 'El body debe ser un objeto';
    }

    try {
        JSON.stringify(body); // Intenta serializar
    } catch (e) {
        return `Body no es serializable a JSON: ${e.message}`;
    }

    // Validación de headers
    if (headers && typeof headers !== 'object') {
        return 'Los headers deben ser un objeto';
    }

    // Validar tipos de valores en headers
    if (headers) {
        for (const [key, value] of Object.entries(headers)) {
            if (typeof value !== 'string' && typeof value !== 'number') {
                return `Header '${key}' debe ser string o number`;
            }
        }
    }

    return true; // Todo válido
}

export default API;
