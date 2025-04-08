/**
 * Media Manager JavaScript
 * Handles all media-related operations using Axios
 */

import axios from 'axios';

// Setup axios dengan CSRF token
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

const MediaManager = {
    /**
     * Upload media file
     * @param {FormData} formData - Form data containing file and metadata
     * @returns {Promise}
     */
    uploadMedia(formData) {
        return axios.post('/api/media', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    },

    /**
     * Delete media by ID
     * @param {number} id - Media ID
     * @returns {Promise}
     */
    deleteMedia(id) {
        return axios.delete(`/api/media/${id}`);
    },

    /**
     * Toggle slideshow status for media
     * @param {number} id - Media ID
     * @returns {Promise}
     */
    toggleSlideshow(id) {
        return axios.put(`/api/media/${id}/toggle-slideshow`);
    },

    /**
     * Update media type (gallery, document, etc)
     * @param {number} id - Media ID
     * @param {string} type - Media type
     * @returns {Promise}
     */
    updateMediaType(id, type) {
        return axios.put(`/api/media/${id}/update-type`, { type });
    }
};

export default MediaManager; 