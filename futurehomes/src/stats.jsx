// components/Stats.jsx
import React, { useState, useEffect } from "react";
import CountUp from "react-countup";
import { motion } from "framer-motion";
import ApiService from "./services/api";

function Stats() {
  const [stats, setStats] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Fallback static data
  const fallbackStats = [
    { name: "سنوات الخبرة", number: "15+", icon: "calendar" },
    { name: "المشاريع المنجزة", number: "150+", icon: "building" },
    { name: "عملاء راضون", number: "200+", icon: "users" },
    { name: "مهندسون محترفون", number: "25+", icon: "user-tie" },
  ];

  useEffect(() => {
    const fetchStats = async () => {
      try {
        setLoading(true);
        const response = await ApiService.getStats();
        const apiStats = response.data || [];
        
        // Transform API data to match component structure
        const transformedStats = apiStats.map(stat => ({
          name: stat.name,
          number: stat.number,
          icon: stat.icon,
        }));

        setStats(transformedStats.length > 0 ? transformedStats : fallbackStats);
      } catch (err) {
        console.error('Failed to fetch stats:', err);
        setError('فشل في تحميل الإحصائيات');
        setStats(fallbackStats);
      } finally {
        setLoading(false);
      }
    };

    fetchStats();
  }, []);

  // Extract numeric value from string like "15+" or "150+"
  const extractNumber = (numberStr) => {
    const match = numberStr.match(/\d+/);
    return match ? parseInt(match[0]) : 0;
  };

  // Get suffix from string like "15+" returns "+"
  const getSuffix = (numberStr) => {
    return numberStr.replace(/\d+/, '');
  };

  if (loading) {
    return (
      <section className="py-20 bg-gradient-to-br from-gray-900 to-future text-white text-center">
        <div className="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
          {[1, 2, 3, 4].map((i) => (
            <div key={i} className="flex flex-col items-center">
              <div className="h-12 w-20 bg-gray-700 rounded animate-pulse mb-2"></div>
              <div className="h-6 w-24 bg-gray-700 rounded animate-pulse"></div>
            </div>
          ))}
        </div>
      </section>
    );
  }

  return (
    <section className="py-20 bg-gradient-to-br from-gray-900 to-future text-white text-center">
      <div className="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
        {stats.map((stat, i) => (
          <motion.div
            key={i}
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: i * 0.1 }}
            className="flex flex-col items-center"
          >
            <h3 className="text-4xl font-bold">
              <CountUp end={extractNumber(stat.number)} duration={2.5} />
              {getSuffix(stat.number)}
            </h3>
            <p className="mt-2 text-lg">{stat.name}</p>
          </motion.div>
        ))}
      </div>
      
      {error && (
        <div className="text-center mt-4">
          <p className="text-red-300 text-sm">{error}</p>
        </div>
      )}
    </section>
  );
}

export default Stats;
