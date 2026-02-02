import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import ApiService from "./services/api";

function Partners() {
  const [partners, setPartners] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Fallback static data
  const fallbackPartners = [
    { name: "3nood", logo_url: "/3nood.svg" },
    { name: "Jood", logo_url: "/joodwhite.svg" },
    { name: "CLU", logo_url: "/clu.png" },
    { name: "شريك رابع", logo_url: "/nobg.png" },
  ];

  useEffect(() => {
    const fetchPartners = async () => {
      try {
        setLoading(true);
        const response = await ApiService.getPartners();
        const apiPartners = response.data || [];
        
        // Filter only active partners and sort by display_order
        const activePartners = apiPartners
          .filter(partner => partner.is_active)
          .sort((a, b) => (a.display_order || 0) - (b.display_order || 0));

        setPartners(activePartners.length > 0 ? activePartners : fallbackPartners);
      } catch (err) {
        console.error('Failed to fetch partners:', err);
        setError('فشل في تحميل الشركاء');
        setPartners(fallbackPartners);
      } finally {
        setLoading(false);
      }
    };

    fetchPartners();
  }, []);

  if (loading) {
    return (
      <section className="bg-gradient-to-br from-future to-gray-900 text-white py-16">
        <div className="text-center mb-10 px-4">
          <h2 className="text-3xl md:text-4xl font-bold">شركاؤنا في النجاح</h2>
        </div>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-10 md:gap-16 px-6 place-items-center">
          {[1, 2, 3, 4].map((i) => (
            <div key={i} className="h-20 w-32 bg-gray-700 rounded animate-pulse"></div>
          ))}
        </div>
      </section>
    );
  }

  return (
    <section className="bg-gradient-to-br from-future to-gray-900 text-white py-16">
      {/* Section Title */}
      <div className="text-center mb-10 px-4">
        <motion.h2 
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-3xl md:text-4xl font-bold"
        >
          شركاؤنا في النجاح
        </motion.h2>
      </div>

      {/* Error Message */}
      {error && (
        <div className="text-center mb-6">
          <p className="text-red-300 text-sm">{error}</p>
        </div>
      )}

      <motion.div 
        className="grid grid-cols-2 md:grid-cols-4 gap-10 md:gap-16 px-6 place-items-center"
        initial={{ opacity: 0 }}
        whileInView={{ opacity: 1 }}
        viewport={{ once: true }}
        transition={{ duration: 0.8, staggerChildren: 0.1 }}
      >
        {partners.map((partner, index) => (
          <motion.div
            key={partner.id || index}
            initial={{ opacity: 0, scale: 0.8 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: index * 0.1 }}
          >
            <img
              className="h-20 sm:h-24 md:h-28 w-auto object-contain transform transition-transform duration-300 hover:scale-110"
              src={partner.logo_url || partner.logo_path}
              alt={partner.name}
              onError={(e) => {
                // Fallback to a placeholder if image fails to load
                e.target.src = '/nobg.png';
              }}
            />
          </motion.div>
        ))}
      </motion.div>
    </section>
  );
}

export default Partners;
