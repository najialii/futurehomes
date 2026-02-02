import React from "react";
import { motion } from "framer-motion";

function Hero() {
  return (
    <section
      id="home"
      className="relative h-screen flex items-center justify-center text-white overflow-hidden"
    >
      <video
        className="absolute inset-0 w-full h-full object-cover z-0"
        src={"/Promo (1).mp4"} 
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
          <span className="block mt-2">نضع خبرة تزيد عن 15 عاماً بين يديك</span>
        </h1>
        <p className="text-lg md:text-xl mb-8 font-light">
          من التصميم إلى التشطيب، ننفذ مشاريعك باحترافية تامة.
        </p>
  
        <motion.a
          whileHover={{ scale: 1.05, backgroundColor: "#E5E7EB" }}
          whileTap={{ scale: 0.95 }}
          href="#projects"
          className="inline-block px-8 py-3 bg-gradient-to-r from-future to-gray-900 text-white font-semibold rounded-full shadow-md hover:shadow-xl transition-all transform hover:scale-105"
          >
          اكتشف مشاريعنا
        </motion.a>
      </motion.div>
    </section>
  );
}

export default Hero;