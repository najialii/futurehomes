import React from "react";
import { motion } from "framer-motion";

const externalProjects = [
  {
    name: "  مشروع حي الملك سلمان " ,
    description: "تنفيذ وتصميم مشروع خارجي مميز يجمع بين الحداثة والعملية.",
    images: [
      "/projects/ksulman1.jpg",
      "../public/projects/ksulman2.JPG",
      "../public/projects/ksulman3.JPG",
      "../public/projects/ksulman4.JPG",
    ],
  },
  {
    name: " مشروع التعاون ",
    description:
      "مشروع فيلا خارجية بتصميم فريد يبرز جمال الواجهات والمساحات الخارجية.",
    images: [
      "../public/projects/t3awn1.JPG",
      "../public/projects/t3awn2.JPG",
      "../public/projects/t3awn3.JPG",
      "../public/projects/t3awn4.jpg",
    ],
  },
  {
    name: "مشروع منتجع",
    description:
      "تصميم وتنفيذ مشروع خارجي راقٍ يتوافق مع أحدث المعايير الهندسية.",
    images: [
      "../public/projects/resort1.JPG",
      "../public/projects/resort2.jpg",
      "../public/projects/resort3.JPG",
      "../public/projects/resort4.PNG",
    ],
  },
];

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

function Projects() {
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
          نفخر بتنفيذ مجموعة متميزة من المشاريع التي تعكس خبرتنا الممتدة لأكثر من
          15 عاماً.
        </motion.p>
      </div>

      {/* Projects */}
      <motion.div
        className="max-w-7xl mx-auto px-4 grid grid-cols-1 gap-12 py-12"
        initial="hidden"
        whileInView="visible"
        viewport={{ once: true, amount: 0.1 }}
        variants={containerVariants}
      >
        {externalProjects.map((project, index) => (
          <motion.div
            key={index}
            variants={itemVariants}
            className=" p-6 rounded-2xl  hover:shadow-xl transition-shadow"
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
                  className="overflow-hidden rounded-xl  group"
                >
                  <img
                    src={image}
                    alt={`${project.name} - صورة ${i + 1}`}
                    className="w-full h-56 object-cover transform group-hover:scale-105 transition-transform duration-500"
                    loading="lazy"
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
