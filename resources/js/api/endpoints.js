import API_BASE_URL from '@api/config.js';

const ENDPOINTS = {
    AUTH: {
        LOGIN: `${API_BASE_URL}/auth/login`,
        LOGOUT: `${API_BASE_URL}/auth/logout`
    },
    USERS: {
        BASE: `${API_BASE_URL}/users`,
        BY_ID: (id) => `${API_BASE_URL}/users/${id}`,
        FAVORITES: `${API_BASE_URL}/users/favorites`,
        AVATAR: `${API_BASE_URL}/users/avatar`,
        BANNER: `${API_BASE_URL}/users/banner`,
        SCORE_FORMAT: `${API_BASE_URL}/users/score-format`,
    },
    SONGS: {
        SEASONAL: `${API_BASE_URL}/songs/seasonal`,
        RANKING: `${API_BASE_URL}/songs/ranking`,
        FILTER: `${API_BASE_URL}/songs/filter`,
        LIKE: (song) => `${API_BASE_URL}/songs/${song}/like`,
        DISLIKE: (song) => `${API_BASE_URL}/songs/${song}/dislike`,
        FAVORITE: (song) => `${API_BASE_URL}/songs/${song}/favorite`,
        COMMENTS: `${API_BASE_URL}/songs/comments`,
        RATE: (song) => `${API_BASE_URL}/songs/${song}/rate`,
        REPORTS: `${API_BASE_URL}/songs/reports`,
        GETCOMMENTS: (song) => `${API_BASE_URL}/songs/${song}/comments`,
    },
    POSTS: {
        ANIMES: `${API_BASE_URL}/animes`,
    },
    ARTISTS: {
        FILTER: `${API_BASE_URL}/artists/filter`,
        SONGS: (id) => `${API_BASE_URL}/artists/${id}/filter`,
    },
    COMMENTS: {
        DELETE: (id) => `${API_BASE_URL}/comments/${id}`,
        LIKE: (id) => `${API_BASE_URL}/comments/${id}/like`,
        DISLIKE: (id) => `${API_BASE_URL}/comments/${id}/dislike`,
        REPLY: (id) => `${API_BASE_URL}/comments/${id}/reply`
    },
    VARIANTS: {
        GETVIDEOS: (id) => `${API_BASE_URL}/variants/${id}/get-videos`
    },
    REQUESTS: {
        STORE:`${API_BASE_URL}/requests`,
    }
};

export default ENDPOINTS;
