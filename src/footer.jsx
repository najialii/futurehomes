// components/Footer.jsx
import React from "react";
import { FaInstagram, FaWhatsapp, FaTiktok, FaYoutube, FaSnapchatGhost } from "react-icons/fa";

function Footer() {
  return (
    <footer className="bg-gray-800 text-gray-300 border-t border-gray-700 font-elmassri" dir="rtl">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-y-12 md:gap-x-8">
        
        <div className="flex flex-col items-center md:items-start text-center md:text-right space-y-4">
          <img src="../public/white.svg" alt="Future Homes logo" className="h-20 w-auto mb-2" />
          <p className="text-sm text-gray-400 mt-2">Future Homes Company © 2025</p>
        </div>

        <div className="flex flex-col items-center md:items-start text-center md:text-right">
          <h3 className="text-xl font-semibold mb-4 text-white">مكاتبنا</h3>
          <ul className="space-y-3 text-sm">
            <li>المملكة العربية السعودية، الرياض</li>
            <li>شارع عثمان بن عفان، حي التعاون</li>
          </ul>
        </div>

        <div className="flex flex-col items-center md:items-start text-center md:text-right">
          <h3 className="text-xl font-semibold mb-4 text-white">أبرز الخدمات</h3>
          <ul className="space-y-3 text-sm">
            <li>التصميم والبناء</li>
            <li>الإنشاء والتشطيب</li>
            <li>الترميم الاحترافي</li>
            <li>إدارة المشاريع</li>
          </ul>
        </div>

        <div className="flex flex-col items-center md:items-start text-center md:text-right space-y-4">
          <h3 className="text-xl font-semibold mb-4 text-white">تواصل معنا</h3>
          <div className="flex space-x-4 space-x-reverse justify-center md:justify-start text-future">
            <a href="https://www.instagram.com/futurehomes777?igsh=MTlwdW1jeGp6NHNrMg==" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
              <FaInstagram size={24} className="hover:text-white transition-colors" />
            </a>
            <a href="https://wa.me/966555453228" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
              <FaWhatsapp size={24} className="hover:text-white transition-colors" />
            </a>
            <a href="https://www.tiktok.com/@future33445?_t=ZM-8znZmAMs2XF&_r=1" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
              <FaTiktok size={24} className="hover:text-white transition-colors" />
            </a>
            <a href="https://www.snapchat.com/add/fuc.homes" target="_blank" rel="noopener noreferrer" aria-label="Snapchat">
              <FaSnapchatGhost size={24} className="hover:text-white transition-colors" />
            </a>
            <a href="#" target="_blank" rel="noopener noreferrer" aria-label="Youtube">
              <FaYoutube size={24} className="hover:text-white transition-colors" />
            </a>
          </div>
          <div className="space-y-2 mt-4 text-sm text-gray-400">
            <p dir="ltr">+966 55 545 3228</p>
            <p>sales@fuchomes.com</p>
          </div>
        </div>

      </div>

      <div className="border-t border-gray-700 text-center py-6 px-4 text-xs text-gray-500">
        <p>&copy; 2025 Future Homes. جميع الحقوق محفوظة.</p>
      </div>
    </footer>
  );
}

export default Footer;
