import React from "react";
import { motion } from "framer-motion";

// Animation variants
const containerVariants = {
  hidden: { opacity: 0, y: 50 },
  visible: {
    opacity: 1,
    y: 0,
    transition: {
      duration: 0.6,
      staggerChildren: 0.2,
    },
  },
};

const itemVariants = {
  hidden: { opacity: 0, y: 20 },
  visible: { opacity: 1, y: 0 },
};

// Reusable component for a service card
const ServiceCard = ({ title, description, icon }) => (
  <motion.div
    variants={itemVariants}
    className="bg-gray-100 p-8 rounded-lg shadow-md text-center transform transition-transform hover:scale-105"
  >
    <div className="flex justify-center mb-4">
      <div className="p-4 bg-future text-white rounded-full">
        {icon}
      </div>
    </div>
    <h3 className="text-xl font-bold text-gray-900 mb-2">{title}</h3>
    <p className="text-gray-600 leading-relaxed">{description}</p>
  </motion.div>
);

function Services() {
  const services = [
    {
      title: "التصميم",
      description: "نقدم حلولاً تصميمية مبتكرة ومتكاملة، من المخططات المعمارية إلى التصميمات الداخلية، لتحقيق رؤيتك الفردية.",
      icon: (
        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
          <path strokeLinecap="round" strokeLinejoin="round" d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2m4 0V3a1 1 0 011-1h2a1 1 0 011 1v1m-4 0h6m-4 6v8m-4-4h8m-2-4h2a1 1 0 001-1v-2a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z" />
        </svg>
      ),
    },
    {
      title: "البناء والإنشاء",
      description: "نلتزم بمعايير الجودة العالمية في تنفيذ المشاريع الإنشائية، بدءاً من الأساسات القوية وصولاً إلى الهيكل الكامل للمبنى.",
      icon: (
        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
          <path d="M12 14l9-5-9-5-9 5 9 5z" />
          <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.05v-5.05z" />
          <path d="M12 14l-6.16-3.422a12.083 12.083 0 01-.665 6.479A11.952 11.952 0 0012 20.05v-5.05z" />
          <path d="M12 14L9 6l3-1 3 1" />
        </svg>
      ),
    },
    {
      title: "التشطيب",
      description: "نقدم خدمات تشطيب نهائية تضفي لمسة من الأناقة والاحترافية، مع الاهتمام بأدق التفاصيل لضمان رضا العميل التام.",
      icon: (
        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
          <path strokeLinecap="round" strokeLinejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
      ),
    },
    {
      title: "الترميم",
      description: "نقوم بأعمال الترميم وإعادة التأهيل للمباني القديمة بطرق احترافية، لإعادة الحياة إلى المساحات مع الحفاظ على قيمتها الأصلية.",
      icon: (
        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
          <path strokeLinecap="round" strokeLinejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
        </svg>
      ),
    },
  ];

  return (
    <section id="services" className="bg-white py-16 font-elmassri" dir="rtl">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Section Header */}
        <motion.div
          className="text-center mb-16"
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.3 }}
          variants={containerVariants}
        >
          <motion.h2
            variants={itemVariants}
            className="text-4xl sm:text-5xl font-bold text-gray-900 mb-4"
          >
            خدماتنا
          </motion.h2>
          <motion.p
            variants={itemVariants}
            className="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto"
          >
            نقدم مجموعة متكاملة من الحلول الهندسية والمعمارية لتنفيذ مشاريعكم بأعلى مستويات الجودة والاحترافية.
          </motion.p>
        </motion.div>

        {/* Services Grid */}
        <motion.div
          className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8"
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.3 }}
          variants={containerVariants}
        >
          {services.map((service, index) => (
            <ServiceCard
              key={index}
              title={service.title}
              description={service.description}
              icon={service.icon}
            />
          ))}
        </motion.div>
      </div>
    </section>
  );
}

export default Services;