import React, { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { X, Filter, Tag } from "lucide-react";
import ApiService from "./services/api";

const cardVariants = {
  hidden: { opacity: 0, y: 40 },
  visible: (i) => ({
    opacity: 1,
    y: 0,
    transition: { delay: i * 0.1, duration: 0.5, ease: "easeOut" },
  }),
};

// Image Loader Component
const ImageLoader = ({ src, alt, onClick }) => {
  const [imageLoaded, setImageLoaded] = useState(false);
  const [imageError, setImageError] = useState(false);

  const handleImageLoad = () => {
    setImageLoaded(true);
    setImageError(false);
  };

  const handleImageError = (e) => {
    console.error('Image failed to load:', src, e);
    setImageError(true);
    setImageLoaded(false);
  };

  return (
    <div className="relative w-full h-60 cursor-pointer group" onClick={onClick}>
      {!imageLoaded && !imageError && (
        <div className="absolute inset-0 bg-gray-300 animate-pulse rounded-2xl flex items-center justify-center">
          <span className="text-gray-500 text-sm">جاري التحميل...</span>
        </div>
      )}

      {imageError && (
        <div className="absolute inset-0 bg-red-100 rounded-2xl flex items-center justify-center">
          <span className="text-red-500 text-sm">فشل في تحميل الصورة</span>
        </div>
      )}

      <img
        src={src}
        alt={alt}
        className={`
          w-full h-full object-cover transform 
          group-hover:scale-105 transition-transform duration-500 rounded-2xl
          ${imageLoaded ? 'opacity-100' : 'opacity-0 absolute'}
        `}
        onLoad={handleImageLoad}
        onError={handleImageError}
        loading="lazy"
      />
    </div>
  );
};

function Designs() {
  const [designs, setDesigns] = useState([]);
  const [categories, setCategories] = useState({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [selected, setSelected] = useState(null);
  const [selectedCategory, setSelectedCategory] = useState('all');
  const [showFilters, setShowFilters] = useState(false);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        const [designsResponse, categoriesResponse] = await Promise.all([
          ApiService.getDesigns(),
          ApiService.getDesignCategories()
        ]);
        
        setDesigns(designsResponse.data || []);
        setCategories(categoriesResponse.data || {});
      } catch (err) {
        console.error('Failed to fetch designs:', err);
        setError('فشل في تحميل التصاميم');
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  const filteredDesigns = selectedCategory === 'all' 
    ? designs 
    : designs.filter(design => design.category === selectedCategory);

  if (loading) {
    return (
      <section dir="rtl" className="bg-gray-100 min-h-screen">
        {/* Header Section */}
        <div className="bg-gray-900 py-32 text-center px-4">
          <h1 className="text-3xl md:text-4xl font-bold text-white">تصاميمنا</h1>
          <p className="text-gray-300 mt-2 max-w-2xl mx-auto text-lg">جاري التحميل...</p>
        </div>

        {/* Loading Grid */}
        <div className="max-w-7xl mx-auto px-4 sm:px-6 py-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          {[1, 2, 3, 4, 5, 6, 7, 8].map((i) => (
            <div key={i} className="rounded-2xl overflow-hidden shadow-md animate-pulse">
              <div className="w-full h-60 bg-gray-300"></div>
            </div>
          ))}
        </div>
      </section>
    );
  }

  if (error) {
    return (
      <section dir="rtl" className="bg-gray-100 min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="text-red-500 mb-4">
            <svg className="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h2 className="text-2xl font-bold text-gray-900 mb-4">حدث خطأ</h2>
          <p className="text-gray-600">{error}</p>
        </div>
      </section>
    );
  }

  return (
    <section dir="rtl" className="bg-gray-100 min-h-screen">
      {/* Header Section */}
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
          استعرض أحدث تصاميمنا المميزة والتي تعكس جودة أعمالنا وخبرتنا في مختلف المجالات المعمارية.
        </motion.p>
        
        {designs.length > 0 && (
          <motion.p
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 0.5, duration: 0.5 }}
            className="text-gray-400 mt-4 text-sm"
          >
            {filteredDesigns.length} تصميم متاح
          </motion.p>
        )}
      </div>

      {/* Filters Section */}
      {Object.keys(categories).length > 0 && (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 py-6">
          <div className="flex flex-wrap items-center gap-4">
            <button
              onClick={() => setShowFilters(!showFilters)}
              className="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow"
            >
              <Filter size={16} />
              <span>تصفية حسب الفئة</span>
            </button>
            
            {showFilters && (
              <motion.div
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                className="flex flex-wrap gap-2"
              >
                <button
                  onClick={() => setSelectedCategory('all')}
                  className={`px-4 py-2 rounded-lg text-sm transition-colors ${
                    selectedCategory === 'all'
                      ? 'bg-future text-white'
                      : 'bg-white text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  جميع التصاميم
                </button>
                {Object.entries(categories).map(([key, label]) => (
                  <button
                    key={key}
                    onClick={() => setSelectedCategory(key)}
                    className={`px-4 py-2 rounded-lg text-sm transition-colors ${
                      selectedCategory === key
                        ? 'bg-future text-white'
                        : 'bg-white text-gray-700 hover:bg-gray-50'
                    }`}
                  >
                    {label}
                  </button>
                ))}
              </motion.div>
            )}
          </div>
        </div>
      )}

      {/* Designs Grid */}
      {filteredDesigns.length > 0 ? (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 pb-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          {filteredDesigns.map((design, index) => (
            <motion.div
              key={design.id}
              custom={index}
              initial="hidden"
              whileInView="visible"
              viewport={{ once: true, amount: 0.2 }}
              variants={cardVariants}
              className="rounded-2xl overflow-hidden shadow-md hover:shadow-xl group relative bg-white"
            >
              <ImageLoader
                src={design.image_url}
                alt={design.alt_text || design.title}
                onClick={() => setSelected(design)}
              />
              
              {/* Design Info Overlay */}
              <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                <h3 className="text-white font-semibold text-sm mb-1 line-clamp-2">
                  {design.title}
                </h3>
                <div className="flex items-center gap-2">
                  <span className="text-gray-300 text-xs bg-black/30 px-2 py-1 rounded">
                    {design.category_label}
                  </span>
                  {design.is_featured && (
                    <span className="text-yellow-300 text-xs">⭐ مميز</span>
                  )}
                </div>
              </div>
            </motion.div>
          ))}
        </div>
      ) : (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 pb-12 text-center py-16">
          <div className="text-gray-400 mb-6">
            <svg className="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 className="text-2xl font-bold text-gray-900 mb-4">لا توجد تصاميم</h3>
          <p className="text-gray-600">
            {selectedCategory === 'all' 
              ? 'لم يتم العثور على تصاميم حالياً'
              : `لا توجد تصاميم في فئة ${categories[selectedCategory] || selectedCategory}`
            }
          </p>
        </div>
      )}

      {/* Modal / Lightbox */}
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
              {/* Image with overlay */}
              <div className="relative rounded-xl overflow-hidden">
                <img
                  src={selected.image_url}
                  alt={selected.alt_text || selected.title}
                  className="w-full max-h-[80vh] object-cover"
                />
                
                {/* Close button */}
                <button
                  className="absolute top-4 right-4 bg-black/60 hover:bg-black/80 p-2 rounded-full text-white transition-colors z-10"
                  onClick={() => setSelected(null)}
                >
                  <X size={20} />
                </button>
                
                {/* Text overlay */}
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6">
                  <div className="text-white">
                    {/* Title and category */}
                    <div className="mb-4">
                      <h2 className="text-2xl md:text-3xl font-bold mb-2 leading-tight">
                        {selected.title}
                      </h2>
                      <div className="flex items-center gap-3 mb-3">
                        <span className="bg-future text-white px-3 py-1 rounded-full text-sm font-medium">
                          {selected.category_label}
                        </span>
                        {selected.is_featured && (
                          <span className="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            ⭐ مميز
                          </span>
                        )}
                      </div>
                    </div>
                    
                    {/* Description */}
                    {selected.description && (
                      <p className="text-gray-200 mb-4 leading-relaxed text-sm md:text-base max-w-3xl">
                        {selected.description}
                      </p>
                    )}
                    
                    {/* Tags */}
                    {selected.tags_labels && selected.tags_labels.length > 0 && (
                      <div className="flex items-center gap-2 flex-wrap">
                        <Tag size={16} className="text-gray-300" />
                        {selected.tags_labels.map((tag, index) => (
                          <span 
                            key={index} 
                            className="bg-white/20 backdrop-blur-sm text-white px-2 py-1 rounded text-xs border border-white/30"
                          >
                            {tag}
                          </span>
                        ))}
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </section>
  );
}

export default Designs;
