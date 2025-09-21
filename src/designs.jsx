// pages/Designs.jsx
import React, { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { X } from "lucide-react";

const designs = [
  { id: 1, image: "/designs/design1.jpeg" },
  { id: 2, image: "/designs/design2.jpeg" },
  { id: 3, image: "/designs/design3.jpeg" },
  { id: 4, image: "/designs/design4.jpeg" },
];

const cardVariants = {
  hidden: { opacity: 0, y: 40 },
  visible: (i) => ({
    opacity: 1,
    y: 0,
    transition: { delay: i * 0.1, duration: 0.5, ease: "easeOut" },
  }),
};

function Designs() {
  const [selected, setSelected] = useState(null);

  return (
    <section dir="rtl" className="bg-gray-100 min-h-screen">
      {/* 🔥 Header Section */}
      <div className="bg-gray-900 py-32 text-center px-4">
        <motion.h1
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="text-3xl md:text-4xl font-bold text-white"
        >
          تصاميمنا
        </motion.h1>
        <motion.p
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.3, duration: 0.5 }}
          className="text-gray-300 mt-2 max-w-2xl mx-auto text-lg"
        >
          استعرض أحدث تصاميمنا المميزة والتي تعكس جودة أعمالنا.
        </motion.p>
      </div>

      {/* ✅ Responsive Grid Gallery */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 py-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        {designs.map((design, index) => (
          <motion.div
            key={design.id}
            custom={index}
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.2 }}
            variants={cardVariants}
            className="rounded-2xl overflow-hidden shadow-md hover:shadow-xl cursor-pointer group relative"
            onClick={() => setSelected(design)}
          >
            <img
              src={design.image}
              alt={`تصميم ${design.id}`}
              className="w-full h-60 object-cover transform group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          </motion.div>
        ))}
      </div>

      {/* 🖼 Modal / Lightbox */}
      <AnimatePresence>
        {selected && (
          <motion.div
            className="fixed inset-0 bg-black/70 flex items-center justify-center z-50 px-4"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={() => setSelected(null)}
          >
            <motion.div
              initial={{ scale: 0.8, opacity: 0 }}
              animate={{ scale: 1, opacity: 1 }}
              exit={{ scale: 0.8, opacity: 0 }}
              className="relative max-w-4xl w-full"
              onClick={(e) => e.stopPropagation()}
            >
              <img
                src={selected.image}
                alt="تصميم"
                className="w-full max-h-[80vh] object-contain rounded-xl shadow-2xl"
              />
              <button
                className="absolute top-3 right-3 bg-black/60 p-2 rounded-full text-white hover:bg-black transition"
                onClick={() => setSelected(null)}
              >
                <X size={24} />
              </button>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </section>
  );
}

export default Designs;
