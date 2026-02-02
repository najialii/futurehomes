import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
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

// --- Image Loader Component ---
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
      {/* Skeleton/Placeholder Box (Shows when imageLoaded is false and no error) */}
      {!imageLoaded && !imageError && (
        <div className="absolute inset-0 bg-gray-300 animate-pulse rounded-xl flex items-center justify-center">
          <span className="text-gray-500 text-sm">جاري التحميل...</span>
        </div>
      )}

      {/* Error state */}
      {imageError && (
        <div className="absolute inset-0 bg-red-100 rounded-xl flex items-center justify-center">
          <span className="text-red-500 text-sm">فشل في تحميل الصورة</span>
        </div>
      )}

      {/* Actual Image */}
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

function Projects() {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Fallback static data
  const fallbackProjects = [
    {
      name: "مشروع حي الملك سلمان",
      description: "تنفيذ وتصميم مشروع خارجي مميز يجمع بين الحداثة والعملية.",
      images: [
        { url: "/projects/ksulman1.jpg", alt: "مشروع حي الملك سلمان - صورة 1" },
        { url: "/projects/ksulman2.JPG", alt: "مشروع حي الملك سلمان - صورة 2" },
        { url: "/projects/ksulman3.JPG", alt: "مشروع حي الملك سلمان - صورة 3" },
        { url: "/projects/ksulman4.JPG", alt: "مشروع حي الملك سلمان - صورة 4" },
      ],
    },
    {
      name: "مشروع التعاون",
      description: "مشروع فيلا خارجية بتصميم فريد يبرز جمال الواجهات والمساحات الخارجية.",
      images: [
        { url: "/projects/t3awn1.JPG", alt: "مشروع التعاون - صورة 1" },
        { url: "/projects/t3awn2.JPG", alt: "مشروع التعاون - صورة 2" },
        { url: "/projects/t3awn3.JPG", alt: "مشروع التعاون - صورة 3" },
        { url: "/projects/t3awn4.jpg", alt: "مشروع التعاون - صورة 4" },
      ],
    },
    {
      name: "مشروع منتجع",
      description: "تصميم وتنفيذ مشروع خارجي راقٍ يتوافق مع أحدث المعايير الهندسية.",
      images: [
        { url: "/projects/resort1.JPG", alt: "مشروع منتجع - صورة 1" },
        { url: "/projects/resort2.jpg", alt: "مشروع منتجع - صورة 2" },
        { url: "/projects/resort3.JPG", alt: "مشروع منتجع - صورة 3" },
        { url: "/projects/resort4.PNG", alt: "مشروع منتجع - صورة 4" },
      ],
    },
  ];

  useEffect(() => {
    const fetchProjects = async () => {
      try {
        setLoading(true);
        const response = await ApiService.getProjects();
        const apiProjects = response.data || [];
        
        // Transform API data to match component structure
        const transformedProjects = apiProjects.map(project => ({
          name: project.name,
          description: project.description,
          images: project.images && project.images.length > 0 
            ? project.images.map(img => ({
                url: img.image_url,
                alt: img.alt_text || `${project.name} - صورة`
              }))
            : [{ url: '/projects/placeholder.jpg', alt: 'صورة افتراضية' }], // Fallback image
        }));

        setProjects(transformedProjects.length > 0 ? transformedProjects : fallbackProjects);
      } catch (err) {
        console.error('Failed to fetch projects:', err);
        setError('فشل في تحميل المشاريع');
        setProjects(fallbackProjects);
      } finally {
        setLoading(false);
      }
    };

    fetchProjects();
  }, []);

  if (loading) {
    return (
      <section id="projects" className="bg-white" dir="rtl">
        {/* Header Section */}
        <div className="bg-gray-900 py-32 text-center px-4">
          <h1 className="text-3xl md:text-4xl font-bold text-white">مشاريعنا السابقة</h1>
          <p className="text-gray-300 mt-2 max-w-2xl mx-auto text-lg">جاري التحميل...</p>
        </div>

        {/* Loading Projects */}
        <div className="max-w-7xl mx-auto px-4 py-12">
          {[1, 2, 3].map((i) => (
            <div key={i} className="p-6 rounded-2xl mb-12">
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
      </section>
    );
  }

  return (
    <section id="projects" className="bg-white" dir="rtl">
      {/* Header Section */}
      <div className="bg-gray-900 py-32 text-center px-4">
        <motion.h1
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="text-3xl md:text-4xl font-bold text-white"
        >
          مشاريعنا السابقة
        </motion.h1>
        <motion.p
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.3, duration: 0.5 }}
          className="text-gray-300 mt-2 max-w-2xl mx-auto text-lg"
        >
          نفخر بتنفيذ مجموعة متميزة من المشاريع التي تعكس خبرتنا الممتدة لأكثر من 15 عاماً.
        </motion.p>
      </div>

      {/* Error Message */}
      {error && (
        <div className="text-center py-8">
          <p className="text-red-600">{error}</p>
        </div>
      )}

      {/* Projects */}
      <motion.div
        className="max-w-7xl mx-auto px-4 grid grid-cols-1 gap-12 py-12"
        initial="hidden"
        whileInView="visible"
        viewport={{ once: true, amount: 0.1 }}
        variants={containerVariants}
      >
        {projects.map((project, index) => (
          <motion.div
            key={index}
            variants={itemVariants}
            className="p-6 rounded-2xl hover:shadow-xl transition-shadow"
          >
            <h3 className="text-2xl font-bold text-gray-800 mb-2">
              {project.name}
            </h3>
            <p className="text-gray-600 mb-6">{project.description}</p>

            {/* Image Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
              {project.images.map((image, i) => (
                <motion.div
                  key={i}
                  variants={itemVariants}
                  className="overflow-hidden rounded-xl group"
                >
                  <ImageLoader 
                    src={typeof image === 'string' ? image : image.url} 
                    alt={typeof image === 'string' ? `${project.name} - صورة ${i + 1}` : image.alt} 
                  />
                </motion.div>
              ))}
            </div>
          </motion.div>
        ))}
      </motion.div>
    </section>
  );
}

export default Projects;