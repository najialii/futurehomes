import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import { Link } from "react-router-dom";
import ApiService from "./services/api";

function Hero() {
  const [heroData, setHeroData] = useState({
    title: "نضع خبرة تزيد عن 15 عاماً بين يديك",
    subtitle: "من التصميم إلى التشطيب، ننفذ مشاريعك باحترافية تامة.",
    video_url: "/Promo (1).mp4",
    button_text: "اكتشف مشاريعنا",
    button_link: "#projects"
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchHeroData = async () => {
      try {
        const data = await ApiService.getHero();
        setHeroData(data);
      } catch (error) {
        console.log('Using default hero data:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchHeroData();
  }, []);

  if (loading) {
    return (
      <section className="relative h-screen flex items-center justify-center bg-gray-900">
        <div className="text-white text-xl">Loading...</div>
      </section>
    );
  }

  return (
    <section
      id="home"
      className="relative h-screen flex items-center justify-center text-white overflow-hidden"
    >
      <video
        className="absolute inset-0 w-full h-full object-cover z-0"
        src={heroData.video_url} 
        autoPlay
        loop
        muted
        playsInline
      />
      
      <div className="absolute inset-0 bg-black opacity-60 z-10" />

      <motion.div
        initial={{ opacity: 0, y: 40 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 1 }}
        className="text-center px-6 relative z-20 max-w-4xl"
      >
        <h1 className="text-4xl sm:text-5xl md:text-6xl font-bold leading-tight mb-6">
          <span className="block mt-2">{heroData.title}</span>
        </h1>
        <p className="text-lg md:text-xl mb-8 font-light">
          {heroData.subtitle}
        </p>
  
        <Link to="/projects">
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="inline-block px-8 py-3 bg-gradient-to-r from-future to-gray-900 text-white font-semibold rounded-full shadow-md hover:shadow-xl transition-all transform hover:scale-105 cursor-pointer"
          >
            {heroData.button_text}
          </motion.button>
        </Link>
      </motion.div>
    </section>
  );
}

export default Hero;