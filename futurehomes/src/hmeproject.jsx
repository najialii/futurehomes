import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import ApiService from "./services/api";

const containerVariants = {
  hidden: { opacity: 0, y: 50 },
  visible: { opacity: 1, y: 0, transition: { staggerChildren: 0.15 } },
};

const cardVariants = {
  hidden: { opacity: 0, y: 20, scale: 0.95 },
  visible: { opacity: 1, y: 0, scale: 1, transition: { duration: 0.5 } },
};

function HomeProjectsSection() {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchFeaturedProjects = async () => {
      try {
        const response = await ApiService.getFeaturedProjects();
        setProjects(response.data || []);
      } catch (error) {
        console.error('Failed to load featured projects:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchFeaturedProjects();
  }, []);

  if (loading) {
    return (
      <section id="projects" className="bg-gray-50 py-16 font-elmassri" dir="rtl">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">أحدث مشاريعنا</h2>
          <p className="text-lg text-gray-600">جاري التحميل...</p>
        </div>
      </section>
    );
  }

  if (projects.length === 0) {
    return null;
  }

  return (
    <section id="projects" className="bg-gray-50 py-16 font-elmassri" dir="rtl">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        
        <motion.div
          className="mb-12"
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, amount: 0.5 }}
          variants={cardVariants}
        >
          <motion.h2
            variants={cardVariants}
            className="text-3xl sm:text-4xl font-bold text-gray-900 mb-4"
          >
            أحدث مشاريعنا
          </motion.h2>
          <motion.p
            variants={cardVariants}
            className="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto"
          >
            اكتشف بعضاً من أعمالنا الأخيرة التي تعكس خبرتنا وإبداعنا في مجال البناء والتصميم.
          </motion.p>
        </motion.div>

        <motion.div
          className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12"
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.2 }}
          variants={containerVariants}
        >
          {projects.map((project) => (
            <motion.div
              key={project.id}
              variants={cardVariants}
              className="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer hover:shadow-2xl transition-shadow duration-300"
            >
              <div className="watermarked-image">
                <img
                  src={project.images && project.images[0] ? project.images[0].image_url : '/placeholder.jpg'}
                  alt={project.images && project.images[0] ? project.images[0].alt_text : project.name}
                  className="w-full h-64 sm:h-56 md:h-48 lg:h-56 object-cover transition-transform duration-500 group-hover:scale-105"
                />
              </div>
              <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-black/10 opacity-90 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                <div className="text-right w-full">
                  <h4 className="text-white text-lg font-bold">{project.name}</h4>
                  <p className="text-gray-200 text-sm">{project.service?.title || ''}</p>
                </div>
              </div>
            </motion.div>
          ))}
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, amount: 0.5 }}
        >
          <Link
            to="/projects"
            className="inline-block px-8 py-3 bg-gradient-to-r from-future to-gray-900 text-white font-semibold rounded-full shadow-md hover:shadow-xl transition-all transform hover:scale-105"
          >
            شاهد جميع مشاريعنا
          </Link>
        </motion.div>
      </div>
    </section>
  );
}

export default HomeProjectsSection;
