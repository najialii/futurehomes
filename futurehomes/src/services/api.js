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

  async getServices() {
    return this.request('/services');
  }

  async getService(id) {
    return this.request(`/services/${id}`);
  }

  async getProjects() {
    return this.request('/projects');
  }

  async getProject(id) {
    return this.request(`/projects/${id}`);
  }

  async getProjectsByService(serviceId) {
    return this.request(`/services/${serviceId}/projects`);
  }

  async getFeaturedProjects() {
    return this.request('/projects/featured');
  }

  async getPartners() {
    return this.request('/partners');
  }

  async getTestimonials() {
    return this.request('/testimonials');
  }

  async getStats() {
    return this.request('/stats');
  }

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

  async getPages() {
    return this.request('/pages');
  }

  async getPage(slug) {
    return this.request(`/pages/${slug}`);
  }

  async getCompany() {
    return this.request('/company');
  }

  async submitContact(data) {
    return this.request('/contact', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  async getHero() {
    return this.request('/hero');
  }

  async getContactInfo() {
    return this.request('/contact-info');
  }

  async getSocialLinks() {
    return this.request('/social-links');
  }
}

export default new ApiService();