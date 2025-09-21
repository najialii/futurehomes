import React from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";

const homeProjects = [
  { name: "فيلا خاصة 1", type: "خارجي", image: "/projects/ksulman1.jpg" },
  { name: "فيلا خاصة 2", type: "داخلي", image: "/projects/t3awn1.JPG" },
  { name: "فيلا خاصة 3", type: "خارجي", image: "/projects/resort1.JPG" },
  { name: "فيلا خاصة 4", type: "داخلي", image: "/projects/t3awn4.jpg" },
];

// Animation Variants
const containerVariants = {
  hidden: { opacity: 0, y: 50 },
  visible: { opacity: 1, y: 0, transition: { staggerChildren: 0.15 } },
};

const cardVariants = {
  hidden: { opacity: 0, y: 20, scale: 0.95 },
  visible: { opacity: 1, y: 0, scale: 1, transition: { duration: 0.5 } },
};

function HomeProjectsSection() {
  return (
    <section id="home-projects" className="bg-gray-50 py-16 font-elmassri" dir="rtl">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        
        {/* Header */}
        <motion.div
          className="mb-12"
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.3 }}
          variants={containerVariants}
        >
          <motion.h2
            variants={cardVariants}
            className="text-4xl sm:text-5xl font-bold text-gray-900 mb-4"
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

        {/* Projects Grid */}
        <motion.div
          className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12"
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.2 }}
          variants={containerVariants}
        >
          {homeProjects.map((project, index) => (
            <motion.div
              key={index}
              variants={cardVariants}
              className="relative rounded-xl overflow-hidden shadow-lg group cursor-pointer hover:shadow-2xl transition-shadow duration-300"
            >
              <img
                src={project.image}
                alt={project.name}
                className="w-full h-64 sm:h-56 md:h-48 lg:h-56 object-cover transition-transform duration-500 group-hover:scale-105"
              />
              {/* Overlay */}
              <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-black/10 opacity-90 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                <div className="text-right w-full">
                  <h4 className="text-white text-lg font-bold">{project.name}</h4>
                  <p className="text-gray-200 text-sm">{project.type}</p>
                </div>
              </div>
            </motion.div>
          ))}
        </motion.div>

        {/* CTA Button */}
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
