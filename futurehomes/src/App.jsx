import { useEffect } from "react";
import Navbar from "./navbar";
import Hero from "./hero";
import Services from "./service";
import Stats from "./stats";
import Projects from "./projects";
import Contact from "./contact";
import Testimonials from "./testimonials";
import ServiceProjects from "./service-projects";
import { Routes, Route, useLocation } from 'react-router-dom';
import './index.css';
import Footer from "./footer";
import Partners from "./partners";
import { motion } from "framer-motion";
import { Link } from "react-router-dom";
import HomeProjectsSection from "./hmeproject";
import About from "./about";
import Designs from "./designs";
const containerVariants = {
  hidden: { opacity: 0, y: 50 },
  visible: {
    opacity: 1,
    y: 0,
    transition: {
      duration: 0.6,
      staggerChildren: 0.3,
    },
  },
};

const itemVariants = {
  hidden: { opacity: 0, y: 20 },
  visible: { opacity: 1, y: 0 },
};
const Home = () => (
  <>
    <Hero />
    <Services showHero={false} />
    <Stats />
    <HomeProjectsSection />
    <Testimonials />
    <Partners />
    <section id="cta" className="bg-white py-20 text-future font-elmassri" dir="rtl">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <motion.div
          variants={containerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.5 }}
        >
          {/* Main Heading */}
          <motion.h2
            variants={itemVariants}
            className="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-4 leading-tight"
          >
            نحن مستعدون لبناء أحلامك
          </motion.h2>

          {/* Subheading/Description */}
          <motion.p
            variants={itemVariants}
            className="text-lg sm:text-xl md:text-2xl mb-8 max-w-3xl mx-auto"
          >
            ابدأ رحلتك معنا اليوم لتحويل أفكارك إلى واقع ملموس.
          </motion.p>

          {/* Call to Action Button */}
          <motion.div variants={itemVariants}>
            <Link
              to="/contact"
              className="inline-block px-8 py-3 bg-gradient-to-r from-future to-gray-900 text-white font-semibold rounded-full shadow-md hover:shadow-xl transition-all transform hover:scale-105"
              >
              تواصل معنا الآن
            </Link>
          </motion.div>
        </motion.div>
      </div>
    </section>
  </>
);

function App() {
  const location = useLocation();

  useEffect(() => {
    window.scrollTo(0, 0);
  }, [location.pathname]);

  useEffect(() => {
    const handleContextMenu = (e) => {
      e.preventDefault();
      return false;
    };

    const handleKeyDown = (e) => {
      if (
        e.keyCode === 123 ||
        (e.ctrlKey && e.shiftKey && e.keyCode === 73) ||
        (e.ctrlKey && e.shiftKey && e.keyCode === 74) ||
        (e.ctrlKey && e.keyCode === 85) ||
        (e.ctrlKey && e.keyCode === 83) ||
        (e.ctrlKey && e.shiftKey && e.keyCode === 67) ||
        (e.ctrlKey && e.keyCode === 67) ||
        (e.ctrlKey && e.keyCode === 86) ||
        (e.ctrlKey && e.keyCode === 88) ||
        (e.ctrlKey && e.keyCode === 65) ||
        (e.ctrlKey && e.keyCode === 80) ||
        (e.metaKey && e.keyCode === 67) ||
        (e.metaKey && e.keyCode === 86) ||
        (e.metaKey && e.keyCode === 88) ||
        (e.metaKey && e.keyCode === 65) ||
        (e.metaKey && e.keyCode === 80)
      ) {
        e.preventDefault();
        return false;
      }
    };

    const handleDragStart = (e) => {
      e.preventDefault();
      return false;
    };

    const handleSelectStart = (e) => {
      e.preventDefault();
      return false;
    };

    document.addEventListener('contextmenu', handleContextMenu);
    document.addEventListener('keydown', handleKeyDown);
    document.addEventListener('dragstart', handleDragStart);
    document.addEventListener('selectstart', handleSelectStart);

    const devToolsCheck = setInterval(() => {
      const threshold = 160;
      if (
        window.outerWidth - window.innerWidth > threshold ||
        window.outerHeight - window.innerHeight > threshold
      ) {
        console.clear();
      }
    }, 1000);

    return () => {
      document.removeEventListener('contextmenu', handleContextMenu);
      document.removeEventListener('keydown', handleKeyDown);
      document.removeEventListener('dragstart', handleDragStart);
      document.removeEventListener('selectstart', handleSelectStart);
      clearInterval(devToolsCheck);
    };
  }, []);

  return (
    <div className="font-elmassri bg-gray-50 text-gray-900">
      <Navbar />

      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/about" element={<About />} />
        <Route path="/projects" element={<Projects />} />
        <Route path="/contact" element={<Contact />} /> 
        <Route path="/services" element={<Services />} /> 
        <Route path="/services/:serviceId/projects" element={<ServiceProjects />} />
        <Route path="/design" element={<Designs />} /> 
      </Routes>

      <Footer />
    </div>
  );
}

export default App;