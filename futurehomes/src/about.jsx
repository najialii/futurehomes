import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import ApiService from "./services/api";

function About() {
  const [pageData, setPageData] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchPageData = async () => {
      try {
        const data = await ApiService.getPage('about-us');
        setPageData(data.data);
      } catch (error) {
        console.error('Failed to load about page:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchPageData();
  }, []);

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen font-elmassri" dir="rtl">
        <div className="text-xl">جاري التحميل...</div>
      </div>
    );
  }

  return (
    <div className="bg-white text-gray-800 font-elmassri" dir="rtl">
      <div className="relative h-[50vh] flex items-center justify-center bg-gray-900 text-white">
        <div className="absolute inset-0 bg-cover bg-center opacity-40"></div>
        <div className="relative text-center px-4">
          <motion.h1
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
            className="text-3xl sm:text-5xl md:text-6xl font-bold leading-tight"
          >
            {pageData?.title || 'من نحن'}
          </motion.h1>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
            className="text-xl md:text-2xl mt-4"
          >
            نحن هنا لنبني أحلامك
          </motion.p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        {pageData?.content && (
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="cms-content"
            dangerouslySetInnerHTML={{ __html: pageData.content }}
          />
        )}
      </div>
    </div>
  );
}

export default About;
