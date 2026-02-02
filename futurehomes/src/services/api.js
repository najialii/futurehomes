// API service for Future Homes CMS
const API_BASE_URL = process.env.NODE_ENV === 'production' 
  ? '/api' 
  : 'http://localhost:8000/api';

class ApiService {
  constructor() {
    this.baseURL = API_BASE_URL;
  }

  async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const config = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers,
      },
      ...options,
    };

    try {
      const response = await fetch(url, config);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      return await response.json();
    } catch (error) {
      console.error('API request failed:', error);
      throw error;
    }
  }

  // Services API
  async getServices() {
    return this.request('/services');
  }

  async getService(id) {
    return this.request(`/services/${id}`);
  }

  // Projects API
  async getProjects() {
    return this.request('/projects');
  }

  async getProject(id) {
    return this.request(`/projects/${id}`);
  }

  async getProjectsByService(serviceId) {
    return this.request(`/services/${serviceId}/projects`);
  }

  // Partners API
  async getPartners() {
    return this.request('/partners');
  }

  // Testimonials API
  async getTestimonials() {
    return this.request('/testimonials');
  }

  // Stats API
  async getStats() {
    return this.request('/stats');
  }

  // Designs API
  async getDesigns() {
    return this.request('/designs');
  }

  async getDesign(id) {
    return this.request(`/designs/${id}`);
  }

  async getFeaturedDesigns() {
    return this.request('/designs/featured');
  }

  async getDesignsByCategory(category) {
    return this.request(`/designs/category/${category}`);
  }

  async getDesignCategories() {
    return this.request('/designs/categories');
  }

  async getDesignTags() {
    return this.request('/designs/tags');
  }

  // Pages API
  async getPages() {
    return this.request('/pages');
  }

  async getPage(slug) {
    return this.request(`/pages/${slug}`);
  }

  // Company API
  async getCompany() {
    return this.request('/company');
  }

  // Contact API
  async submitContact(data) {
    return this.request('/contact', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }
}

export default new ApiService();