import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import { useParams, useNavigate, Link } from "react-router-dom";
import ApiService from "./services/api";

// Animation Variants
const containerVariants = {
  hidden: { opacity: 0, y: 50 },
  visible: {
    opacity: 1,
    y: 0,
    transition: { duration: 0.6, staggerChildren: 0.2 },
  },
};

const itemVariants = {
  hidden: { opacity: 0, scale: 0.95 },
  visible: { opacity: 1, scale: 1 },
};

// Image Loader Component
const ImageLoader = ({ src, alt }) => {
  const [imageLoaded, setImageLoaded] = useState(false);
  const [imageError, setImageError] = useState(false);

  const handleImageLoad = () => {
    setImageLoaded(true);
    setImageError(false);
  };

  const handleImageError = (e) => {
    console.error('Image failed to load:', src, e);
    setImageError(true);
    setImageLoaded(false);
  };

  return (
    <div className="relative w-full h-56">
      {!imageLoaded && !imageError && (
        <div className="absolute inset-0 bg-gray-300 animate-pulse rounded-xl flex items-center justify-center">
          <span className="text-gray-500 text-sm">جاري التحميل...</span>
        </div>
      )}

      {imageError && (
        <div className="absolute inset-0 bg-red-100 rounded-xl flex items-center justify-center">
          <span className="text-red-500 text-sm">فشل في تحميل الصورة</span>
        </div>
      )}

      <img
        src={src}
        alt={alt}
        className={`
          w-full h-full object-cover transform 
          group-hover:scale-105 transition-transform duration-500 rounded-xl
          ${imageLoaded ? 'opacity-100' : 'opacity-0 absolute'}
        `}
        onLoad={handleImageLoad}
        onError={handleImageError}
        loading="lazy"
      />
    </div>
  );
};

function ServiceProjects() {
  const { serviceId } = useParams();
  const navigate = useNavigate();
  const [projects, setProjects] = useState([]);
  const [service, setService] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchServiceAndProjects = async () => {
      try {
        setLoading(true);
        
        // Fetch service details and projects in parallel
        const [serviceResponse, projectsResponse] = await Promise.all([
          ApiService.getService(serviceId),
          ApiService.getProjectsByService(serviceId)
        ]);
        
        setService(serviceResponse.data);
        const apiProjects = projectsResponse.data || [];
        
        // Transform API data to match component structure
        const transformedProjects = apiProjects.map(project => ({
          id: project.id,
          name: project.name,
          description: project.description,
          service: project.service,
          images: project.images && project.images.length > 0 
            ? project.images.map(img => ({
                url: img.image_url,
                alt: img.alt_text || `${project.name} - صورة`
              }))
            : [], // No fallback images for service-specific projects
        }));

        setProjects(transformedProjects);
      } catch (err) {
        console.error('Failed to fetch service projects:', err);
        setError('فشل في تحميل مشاريع الخدمة');
      } finally {
        setLoading(false);
      }
    };

    if (serviceId) {
      fetchServiceAndProjects();
    }
  }, [serviceId]);

  if (loading) {
    return (
      <section className="bg-white min-h-screen" dir="rtl">
        {/* Header Section */}
        <div className="bg-gray-900 py-32 text-center px-4">
          <div className="h-8 bg-gray-700 rounded mb-4 w-1/3 mx-auto animate-pulse"></div>
          <div className="h-4 bg-gray-700 rounded w-2/3 mx-auto animate-pulse"></div>
        </div>

        {/* Loading Projects */}
        <div className="max-w-7xl mx-auto px-4 py-12">
          <div className="grid grid-cols-1 gap-12">
            {[1, 2, 3].map((i) => (
              <div key={i} className="p-6 rounded-2xl">
                <div className="h-8 bg-gray-300 rounded mb-2 w-1/3 animate-pulse"></div>
                <div className="h-4 bg-gray-300 rounded mb-6 w-2/3 animate-pulse"></div>
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                  {[1, 2, 3].map((j) => (
                    <div key={j} className="h-56 bg-gray-300 rounded-xl animate-pulse"></div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    );
  }

  if (error) {
    return (
      <section className="bg-white min-h-screen flex items-center justify-center" dir="rtl">
        <div className="text-center">
          <div className="text-red-500 mb-4">
            <svg className="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h2 className="text-2xl font-bold text-gray-900 mb-4">حدث خطأ</h2>
          <p className="text-gray-600 mb-6">{error}</p>
          <Link
            to="/services"
            className="inline-block px-6 py-3 bg-future text-white rounded-lg hover:bg-opacity-90 transition-colors"
          >
            العودة إلى الخدمات
          </Link>
        </div>
      </section>
    );
  }

  return (
    <section className="bg-white min-h-screen" dir="rtl">
      {/* Header Section */}
      <div className="bg-gray-900 py-32 text-center px-4">
        <motion.div
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
        >
          {/* Breadcrumb */}
          <div className="mb-6">
            <Link 
              to="/services" 
              className="text-gray-300 hover:text-white transition-colors text-sm"
            >
              الخدمات
            </Link>
            <span className="text-gray-500 mx-2">←</span>
            <span className="text-white text-sm">{service?.title}</span>
          </div>

          <h1 className="text-3xl md:text-4xl font-bold text-white mb-4">
            مشاريع {service?.title}
          </h1>
          <p className="text-gray-300 mt-2 max-w-2xl mx-auto text-lg">
            {service?.description}
          </p>
          
          {projects.length > 0 && (
            <p className="text-gray-400 mt-4 text-sm">
              {projects.length} مشروع متاح
            </p>
          )}
        </motion.div>
      </div>

      {/* Projects Content */}
      <div className="max-w-7xl mx-auto px-4 py-12">
        {projects.length > 0 ? (
          <motion.div
            className="grid grid-cols-1 gap-12"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.1 }}
            variants={containerVariants}
          >
            {projects.map((project, index) => (
              <motion.div
                key={project.id}
                variants={itemVariants}
                className="p-6 rounded-2xl hover:shadow-xl transition-shadow"
              >
                <h3 className="text-2xl font-bold text-gray-800 mb-2">
                  {project.name}
                </h3>
                <p className="text-gray-600 mb-6">{project.description}</p>

                {/* Image Grid */}
                {project.images.length > 0 ? (
                  <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    {project.images.map((image, i) => (
                      <motion.div
                        key={i}
                        variants={itemVariants}
                        className="overflow-hidden rounded-xl group"
                      >
                        <ImageLoader 
                          src={image.url} 
                          alt={image.alt} 
                        />
                      </motion.div>
                    ))}
                  </div>
                ) : (
                  <div className="text-center py-8 bg-gray-50 rounded-xl">
                    <div className="text-gray-400 mb-2">
                      <svg className="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                    <p className="text-gray-500 text-sm">لا توجد صور متاحة لهذا المشروع</p>
                  </div>
                )}
              </motion.div>
            ))}
          </motion.div>
        ) : (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="text-center py-16"
          >
            <div className="text-gray-400 mb-6">
              <svg className="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <h3 className="text-2xl font-bold text-gray-900 mb-4">لا توجد مشاريع</h3>
            <p className="text-gray-600 mb-8 max-w-md mx-auto">
              لم يتم العثور على مشاريع لخدمة {service?.title} حالياً. تحقق مرة أخرى لاحقاً.
            </p>
            <Link
              to="/services"
              className="inline-block px-6 py-3 bg-future text-white rounded-lg hover:bg-opacity-90 transition-colors"
            >
              العودة إلى الخدمات
            </Link>
          </motion.div>
        )}
      </div>

      {/* Back to Services Button */}
      <div className="max-w-7xl mx-auto px-4 pb-12">
        <div className="text-center">
          <Link
            to="/services"
            className="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
          >
            <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة إلى جميع الخدمات
          </Link>
        </div>
      </div>
    </section>
  );
}

export default ServiceProjects; 