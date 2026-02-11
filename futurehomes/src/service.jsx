
import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import { useNavigate } from "react-router-dom";
import ApiService from "./services/api";


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

const getServiceIcon = (title) => {
  const icons = {
    'التصميم': (
      <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2m4 0V3a1 1 0 011-1h2a1 1 0 011 1v1m-4 0h6m-4 6v8m-4-4h8m-2-4h2a1 1 0 001-1v-2a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z" />
      </svg>
    ),
    'البناء والإنشاء': (
      <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path d="M12 14l9-5-9-5-9 5 9 5z" />
        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.05v-5.05z" />
        <path d="M12 14l-6.16-3.422a12.083 12.083 0 01-.665 6.479A11.952 11.952 0 0012 20.05v-5.05z" />
        <path d="M12 14L9 6l3-1 3 1" />
      </svg>
    ),
    'التشطيب': (
      <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
      </svg>
    ),
    'الترميم': (
      <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
        <path strokeLinecap="round" strokeLinejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
      </svg>
    ),
  };
  
  return icons[title] || (
    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
      <path strokeLinecap="round" strokeLinejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
    </svg>
  );
};

const ServiceCard = ({ service, onServiceClick }) => (
  <motion.div
    variants={itemVariants}
    className="bg-gray-100 p-8 rounded-lg shadow-md text-center transform transition-transform hover:scale-105 cursor-pointer"
    onClick={() => onServiceClick(service)}
  >
    <div className="flex justify-center mb-4">
      <div className="p-4 bg-future text-white rounded-full">
        {service.icon_path ? (
          <img src={service.icon_path} alt={service.title} className="h-6 w-6" />
        ) : (
          getServiceIcon(service.title)
        )}
      </div>
    </div>
    <h3 className="text-xl font-bold text-gray-900 mb-2">{service.title}</h3>
    <p className="text-gray-600 leading-relaxed">{service.description}</p>
    <div className="mt-4">
      <span className="text-future font-semibold text-sm">اضغط لعرض المشاريع</span>
    </div>
  </motion.div>
);

function Services({ showHero = true }) {
  const navigate = useNavigate();
  const [services, setServices] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchServices = async () => {
      try {
        setLoading(true);
        const response = await ApiService.getServices();
        setServices(response.data || []);
      } catch (err) {
        console.error('Failed to fetch services:', err);
        setError('فشل في تحميل الخدمات');
        // Fallback to static data if API fails
        setServices([
          {
            id: 1,
            title: "التصميم",
            description: "نقدم حلولاً تصميمية مبتكرة ومتكاملة، من المخططات المعمارية إلى التصميمات الداخلية، لتحقيق رؤيتك الفردية.",
          },
          {
            id: 2,
            title: "البناء والإنشاء",
            description: "نلتزم بمعايير الجودة العالمية في تنفيذ المشاريع الإنشائية، بدءاً من الأساسات القوية وصولاً إلى الهيكل الكامل للمبنى.",
          },
          {
            id: 3,
            title: "التشطيب",
            description: "نقدم خدمات تشطيب نهائية تضفي لمسة من الأناقة والاحترافية، مع الاهتمام بأدق التفاصيل لضمان رضا العميل التام.",
          },
          {
            id: 4,
            title: "الترميم",
            description: "نقوم بأعمال الترميم وإعادة التأهيل للمباني القديمة بطرق احترافية، لإعادة الحياة إلى المساحات مع الحفاظ على قيمتها الأصلية.",
          },
        ]);
      } finally {
        setLoading(false);
      }
    };

    fetchServices();
  }, []);

  const handleServiceClick = (service) => {
    navigate(`/services/${service.id}/projects`);
  };

  if (loading) {
    return (
      <div className={showHero ? "bg-gray-100 font-elmassri" : "bg-white font-elmassri"} dir="rtl">
        {showHero && (
          <div className="relative h-[50vh] flex items-center justify-center bg-gray-900 text-white">
            <div className="absolute inset-0 bg-cover bg-center opacity-40"></div>
            <div className="relative text-center px-4">
              <h1 className="text-3xl sm:text-5xl md:text-6xl font-bold leading-tight">خدماتنا</h1>
            </div>
          </div>
        )}
        <section id="services" className={showHero ? "bg-white py-16" : "bg-white py-16"}>
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">{showHero ? 'ما نقدمه' : 'خدماتنا'}</h2>
            <p className="text-lg text-gray-600 max-w-2xl mx-auto">جاري التحميل...</p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {[1, 2, 3, 4].map((i) => (
              <div key={i} className="bg-gray-100 p-8 rounded-lg shadow-md animate-pulse">
                <div className="flex justify-center mb-4">
                  <div className="p-4 bg-gray-300 rounded-full h-14 w-14"></div>
                </div>
                <div className="h-6 bg-gray-300 rounded mb-2"></div>
                <div className="h-4 bg-gray-300 rounded mb-1"></div>
                <div className="h-4 bg-gray-300 rounded mb-1"></div>
                <div className="h-4 bg-gray-300 rounded w-3/4"></div>
              </div>
            ))}
          </div>
        </div>
      </section>
      </div>
    );
  }

  return (
    <div className={showHero ? "bg-gray-100 font-elmassri" : "bg-white font-elmassri"} dir="rtl">
      {showHero && (
        <div className="relative h-[50vh] flex items-center justify-center bg-gray-900 text-white">
          <div className="absolute inset-0 bg-cover bg-center opacity-40"></div>
          <div className="relative text-center px-4">
            <motion.h1
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8 }}
              className="text-3xl sm:text-5xl md:text-6xl font-bold leading-tight"
            >
              خدماتنا
            </motion.h1>
            <motion.p
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.2 }}
              className="text-xl md:text-2xl mt-4"
            >
              نقدم مجموعة متكاملة من الحلول الهندسية والمعمارية
            </motion.p>
          </div>
        </div>
      )}

      <section id="services" className={showHero ? "bg-white py-16" : "bg-white py-16"}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <motion.div
          className="text-center mb-16"
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.3 }}
          variants={containerVariants}
        >
          <motion.h2
            variants={itemVariants}
            className="text-3xl sm:text-4xl font-bold text-gray-900 mb-4"
          >
            {showHero ? 'ما نقدمه' : 'خدماتنا'}
          </motion.h2>
          <motion.p
            variants={itemVariants}
            className="text-lg text-gray-600 max-w-2xl mx-auto"
          >
            {showHero ? 'حلول متكاملة لتنفيذ مشاريعكم بأعلى مستويات الجودة والاحترافية' : 'نقدم مجموعة متكاملة من الحلول الهندسية والمعمارية لتنفيذ مشاريعكم بأعلى مستويات الجودة والاحترافية'}
          </motion.p>
        </motion.div>

        {error && (
          <div className="text-center mb-8">
            <p className="text-red-600">{error}</p>
          </div>
        )}

        <motion.div
          className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8"
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.3 }}
          variants={containerVariants}
        >
          {services.map((service, index) => (
            <ServiceCard
              key={service.id || index}
              service={service}
              onServiceClick={handleServiceClick}
            />
          ))}
        </motion.div>
      </div>
    </section>
    </div>
  );
}

export default Services;