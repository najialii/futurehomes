import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { Menu, X } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";
import logo from "../public/white.svg"

function Navbar() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [showNavbar, setShowNavbar] = useState(true);
  const [lastScrollY, setLastScrollY] = useState(0);

  const navLinks = [
    { to: "/", label: "الرئيسية" },
    { to: "/about", label: "من نحن" },
    { to: "/projects", label: "المشاريع" },
    { to: "/design", label: "تصاميمنا" },
    { to: "/contact", label: "تواصل معنا" },
  ];

  // Logic to handle scroll-based transparency and show/hide
  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 50);

      if (window.scrollY > lastScrollY && window.scrollY > 80) {
        setShowNavbar(false);
      } else {
        setShowNavbar(true);
      }
      setLastScrollY(window.scrollY);
    };

    window.addEventListener("scroll", handleScroll);
    return () => {
      window.removeEventListener("scroll", handleScroll);
    };
  }, [lastScrollY]);

  const toggleMenu = () => setIsMenuOpen((prev) => !prev);

  // Define the base and conditional class names
  const navClass = `fixed top-0 left-0 w-full z-50 transition-colors duration-300 ${
    isScrolled ? "bg-gray-800 text-gray-300 shadow-md" : "bg-transparent text-white"
  }`;
  
  const linkClass = (baseColor, hoverBg) => `rounded-md px-3 py-2 text-sm font-medium transition ${baseColor} ${hoverBg}`;

  return (
    <AnimatePresence>
      {showNavbar && (
        <motion.nav
          initial={{ y: -80 }}
          animate={{ y: 0 }}
          exit={{ y: -80 }}
          transition={{ duration: 0.3 }}
          className={navClass}
          dir="rtl"
        >
          <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 lg:p-4">
            <div className="relative flex h-16 items-center justify-between">
              {/* Mobile menu button */}
              <div className="absolute inset-y-0 left-0 flex items-center sm:hidden">
                <button
                  onClick={toggleMenu}
                  className={`relative inline-flex items-center justify-center rounded-md p-2 focus:outline-none ${isScrolled ? "text-gray-400 hover:bg-gray-700" : "text-white hover:bg-white/10"}`}
                >
                  {isMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
                </button>
              </div>

              {/* Logo + Desktop Nav */}
              <div className="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                <Link to="/" className="flex shrink-0 items-center">
                  {/* The logo should also change color for visibility */}
                  <img src={logo} alt="Future Homes" className={`h-24 w-auto transition-opacity duration-300 ${isScrolled ? "opacity-100" : "opacity-90"}`} />
                </Link>
              </div>

              {/* Right Section: Desktop Nav */}
              <div className="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                <div className="hidden sm:ml-6 sm:block">
                  <div className="flex space-x-4 space-x-reverse">
                    {navLinks.map((link) => (
                      <Link
                        key={link.to}
                        to={link.to}
                        className={linkClass(
                          isScrolled ? "text-gray-300" : "text-white",
                          isScrolled ? "hover:text-future" : "hover:bg-white/10 hover:text-future"
                        )}
                      >
                        {link.label}
                      </Link>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Mobile Menu */}
          <AnimatePresence>
            {isMenuOpen && (
              <motion.div
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: "auto" }}
                exit={{ opacity: 0, height: 0 }}
                transition={{ duration: 0.2 }}
                className={`sm:hidden ${isScrolled ? "bg-gray-800" : "bg-black/80"}`}
              >
                <div className="space-y-1 px-2 pt-2 pb-3">
                  {navLinks.map((link) => (
                    <Link
                      key={link.to}
                      to={link.to}
                      onClick={() => setIsMenuOpen(false)}
                      className={`block rounded-md px-3 py-2 text-base font-medium ${isScrolled ? "text-gray-300 hover:bg-gray-700 hover:text-future" : "text-gray-300 hover:bg-white/10"}`}
                    >
                      {link.label}
                    </Link>
                  ))}
                </div>
              </motion.div>
            )}
          </AnimatePresence>
        </motion.nav>
      )}
    </AnimatePresence>
  );
}

export default Navbar;