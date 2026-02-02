import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import ApiService from "./services/api";

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

// Star rating component
const StarRating = ({ rating }) => {
  return (
    <div className="flex justify-center mb-4">
      {[1, 2, 3, 4, 5].map((star) => (
        <svg
          key={star}
          className={`w-5 h-5 ${
            star <= rating ? 'text-yellow-400' : 'text-gray-300'
          }`}
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
      ))}
    </div>
  );
};

// Testimonial card component
const TestimonialCard = ({ testimonial }) => (
  <motion.div
    variants={itemVariants}
    className="bg-white p-8 rounded-lg shadow-md text-center"
  >
    {testimonial.client_photo_path && (
      <div className="flex justify-center mb-4">
        <img
          src={testimonial.client_photo_path}
          alt={testimonial.client_name}
          className="w-16 h-16 rounded-full object-cover"
        />
      </div>
    )}
    
    <StarRating rating={testimonial.rating} />
    
    <p className="text-gray-600 mb-4 italic">"{testimonial.feedback}"</p>
    
    <h4 className="text-lg font-semibold text-gray-900">
      {testimonial.client_name}
    </h4>
  </motion.div>
);

function Testimonials() {
  const [testimonials, setTestimonials] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Fallback static data
  const fallbackTestimonials = [
    {
      client_name: "أحمد محمد السعيد",
      feedback: "شركة Future Homes تجاوزت توقعاتنا بكثير. الاهتمام بالتفاصيل وجودة العمل كانت استثنائية. أنصح بها بشدة.",
      rating: 5,
    },
    {
      client_name: "فاطمة عبدالله الزهراني",
      feedback: "فريق محترف، التسليم في الوقت المحدد، والنتائج رائعة. أنصح بهم بشدة!",
      rating: 5,
    },
    {
      client_name: "خالد عبدالعزيز النمر",
      feedback: "تواصل ممتاز طوال فترة المشروع. منزلنا الجديد هو كل ما حلمنا به وأكثر.",
      rating: 4,
    },
  ];

  useEffect(() => {
    const fetchTestimonials = async () => {
      try {
        setLoading(true);
        const response = await ApiService.getTestimonials();
        const apiTestimonials = response.data || [];
        
        // Filter only approved testimonials
        const approvedTestimonials = apiTestimonials.filter(
          testimonial => testimonial.status === 'approved'
        );

        setTestimonials(approvedTestimonials.length > 0 ? approvedTestimonials : fallbackTestimonials);
      } catch (err) {
        console.error('Failed to fetch testimonials:', err);
        setError('فشل في تحميل التقييمات');
        setTestimonials(fallbackTestimonials);
      } finally {
        setLoading(false);
      }
    };

    fetchTestimonials();
  }, []);

  if (loading) {
    return (
      <section className="bg-gray-100 py-16 font-elmassri" dir="rtl">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">آراء عملائنا</h2>
            <p className="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">جاري التحميل...</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {[1, 2, 3].map((i) => (
              <div key={i} className="bg-white p-8 rounded-lg shadow-md animate-pulse">
                <div className="flex justify-center mb-4">
                  <div className="w-16 h-16 bg-gray-300 rounded-full"></div>
                </div>
                <div className="flex justify-center mb-4">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <div key={star} className="w-5 h-5 bg-gray-300 rounded mr-1"></div>
                  ))}
                </div>
                <div className="h-4 bg-gray-300 rounded mb-2"></div>
                <div className="h-4 bg-gray-300 rounded mb-2"></div>
                <div className="h-4 bg-gray-300 rounded mb-4 w-3/4 mx-auto"></div>
                <div className="h-6 bg-gray-300 rounded w-1/2 mx-auto"></div>
              </div>
            ))}
          </div>
        </div>
      </section>
    );
  }

  return (
    <section className="bg-gray-100 py-16 font-elmassri" dir="rtl">
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
            آراء عملائنا
          </motion.h2>
          <motion.p
            variants={itemVariants}
            className="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto"
          >
            نفخر بثقة عملائنا وتقييماتهم الإيجابية لخدماتنا المتميزة.
          </motion.p>
        </motion.div>

        {/* Error Message */}
        {error && (
          <div className="text-center mb-8">
            <p className="text-red-600">{error}</p>
          </div>
        )}

        {/* Testimonials Grid */}
        <motion.div
          className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.3 }}
          variants={containerVariants}
        >
          {testimonials.map((testimonial, index) => (
            <TestimonialCard
              key={testimonial.id || index}
              testimonial={testimonial}
            />
          ))}
        </motion.div>
      </div>
    </section>
  );
}

export default Testimonials;