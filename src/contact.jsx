import React, { useState } from "react";
import { motion } from "framer-motion";
import { FaInstagram, FaWhatsapp, FaTiktok, FaYoutube, FaMapMarkerAlt, FaPhone, FaEnvelope } from "react-icons/fa";

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

// Reusable component for the contact info card
const ContactInfoCard = ({ title, children }) => (
  <div className="bg-white p-8 rounded-lg  text-gray-800">
    <h3 className="text-2xl font-semibold mb-6 text-future">{title}</h3>
    {children}
  </div>
);

function Contact() {
  const [formData, setFormData] = useState({ name: "", email: "", message: "" });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log("Form submitted:", formData);
    alert("تم استلام رسالتك بنجاح! سنتواصل معك قريباً.");
    setFormData({ name: "", email: "", message: "" });
  };

  return (
    <div className="font-elmassri" dir="rtl">
      <section id="contact-info" className="">
        <div className="max-w-7xl mx-auto  ">
          
          <div className="relative h-[50vh] flex items-center justify-center bg-gray-900 text-white">
              <div className="absolute inset-0 bg-cover bg-center opacity-40" style={{ backgroundImage: "url('/path-to-your-about-us-hero-image.jpg')" }}></div>
              <div className="relative text-center px-4">
                <motion.h1
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.8 }}
                  className="text-4xl sm:text-5xl md:text-6xl font-bold leading-tight"
                >
مكتبنا
                </motion.h1>
                <motion.p
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.8, delay: 0.2 }}
                  className="text-xl md:text-2xl mt-4"
                >
              يمكنك زيارتنا في مكتبنا أو التواصل معنا عبر الهاتف والبريد الإلكتروني.
              </motion.p>
              </div>
            </div>

          {/* Contact Content Grid */}
          <motion.div
            className="grid grid-cols-1 lg:grid-cols-2 gap-12"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={containerVariants}
          >

            <motion.div variants={itemVariants}>
              <ContactInfoCard title="معلومات الاتصال">
                <div className="space-y-4 text-gray-600">
                  <div className="flex items-start gap-3">
                    <FaMapMarkerAlt size={20} className="text-future mt-1 flex-shrink-0" />
                    <p>المملكة العربية السعودية - الرياض- شارع عثمان بن عفان - التعاون</p>
                  </div>
                  <div className="flex items-center gap-3">
                    <FaPhone size={20} className="text-future" />
                    <a href="tel:+966555453228" className="hover:text-gray-900 transition-colors">+966 55 545 3228</a>
                  </div>
                  <div className="flex items-center gap-3">
                    <FaEnvelope size={20} className="text-future" />
                    <a href="mailto:sales@fuchomes.com" className="hover:text-gray-900 transition-colors">sales@fuchomes.com</a>
                  </div>
                </div>
                <div className="mt-8">
                  <h4 className="text-lg font-semibold mb-3 text-gray-800">تابعنا على</h4>
                  <div className="flex space-x-4 space-x-reverse text-future">
                    <a href="https://www.instagram.com/futurehomes777" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><FaInstagram size={24} className="hover:text-gray-900 transition-colors" /></a>
                    <a href="https://wa.me/966555453228" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><FaWhatsapp size={24} className="hover:text-gray-900 transition-colors" /></a>
                    <a href="#" aria-label="TikTok"><FaTiktok size={24} className="hover:text-gray-900 transition-colors" /></a>
                    <a href="#" aria-label="YouTube"><FaYoutube size={24} className="hover:text-gray-900 transition-colors" /></a>
                  </div>
                </div>
              </ContactInfoCard>
            </motion.div>

            <motion.div variants={itemVariants}>
              <ContactInfoCard title="موقعنا">
                <div className="w-full h-80 rounded-lg overflow-hidden">
                  <iframe
                    title="Company Location on Google Maps"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3623.504953931109!2d46.70295257545934!3d24.77332304918712!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2efd26a27e0fd3%3A0xc0233e145b410403!2z2YPZhNis2Kcg2KfZhNmD2YTZhdix2YrYqSDYp9mE2YTZhdmK2KkgLdiz2YTZitix!5e0!3m2!1sen!2ssa!4v1663186178873!5m2!1sen!2ssa"
                    width="100%"
                    height="100%"
                    style={{ border: 0 }}
                    allowFullScreen=""
                    loading="lazy"
                    referrerPolicy="no-referrer-when-downgrade"
                  ></iframe>
                </div>
              </ContactInfoCard>
            </motion.div>
          </motion.div>
        </div>
      </section>

      {/* SECTION 2: Contact Form */}
      <section id="contact-form" className="bg-gray-100 py-16">
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
              أرسل لنا رسالة
            </motion.h2>
            <motion.p
              variants={itemVariants}
              className="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto"
            >
              نحن هنا للإجابة على جميع استفساراتكم ومناقشة مشروعكم القادم.
            </motion.p>
          </motion.div>
          
          {/* Form Container */}
          <motion.div
            className="flex justify-center"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={containerVariants}
          >
            <motion.div variants={itemVariants} className="w-full max-w-3xl bg-white p-8 rounded-lg ">
              <form onSubmit={handleSubmit} className="space-y-6">
                <div>
                  <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                  <input
                    type="text"
                    id="name"
                    name="name"
                    value={formData.name}
                    onChange={handleChange}
                    className="w-full px-4 py-3 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-future"
                    required
                  />
                </div>
                <div>
                  <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    className="w-full px-4 py-3 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-future"
                    required
                  />
                </div>
                <div>
                  <label htmlFor="message" className="block text-sm font-medium text-gray-700 mb-1">رسالتك</label>
                  <textarea
                    id="message"
                    name="message"
                    value={formData.message}
                    onChange={handleChange}
                    rows="4"
                    className="w-full px-4 py-3 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-future"
                    required
                  ></textarea>
                </div>
                <button
                  type="submit"
                  className="w-full bg-future text-white font-semibold py-3 px-6 rounded-md transition-transform transform hover:scale-105"
                >
                  إرسال الرسالة
                </button>
              </form>
            </motion.div>
          </motion.div>
        </div>
      </section>
    </div>
  );
}

export default Contact;