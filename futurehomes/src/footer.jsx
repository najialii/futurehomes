import React, { useState, useEffect } from "react";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faInstagram, faWhatsapp, faTiktok, faSnapchat, faYoutube, faFacebook, faTwitter, faLinkedin, faPinterest, faTelegram } from '@fortawesome/free-brands-svg-icons';
import { faMapMarkerAlt } from '@fortawesome/free-solid-svg-icons';
import ApiService from "./services/api";

const iconMap = {
  instagram: faInstagram,
  whatsapp: faWhatsapp,
  tiktok: faTiktok,
  snapchat: faSnapchat,
  youtube: faYoutube,
  facebook: faFacebook,
  twitter: faTwitter,
  linkedin: faLinkedin,
  pinterest: faPinterest,
  telegram: faTelegram,
};

function Footer() {
  const [contactInfo, setContactInfo] = useState({
    phone: "+966 59 000 7681",
    email: "sales@fuchomes.com",
    address: "المملكة العربية السعودية، الرياض، شارع عثمان بن عفان، حي التعاون",
  });
  const [socialLinks, setSocialLinks] = useState([]);

  useEffect(() => {
    const fetchContactInfo = async () => {
      try {
        const data = await ApiService.getContactInfo();
        setContactInfo(data);
      } catch (error) {
        console.log('Using default footer contact info:', error);
      }
    };

    const fetchSocialLinks = async () => {
      try {
        const response = await ApiService.getSocialLinks();
        setSocialLinks(response.data || []);
      } catch (error) {
        console.log('Failed to load social links:', error);
      }
    };

    fetchContactInfo();
    fetchSocialLinks();
  }, []);

  return (
    <footer className="bg-gray-800 text-gray-300 border-t border-gray-700 font-elmassri" dir="rtl">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-y-12 md:gap-x-8">
        
        <div className="flex flex-col items-center md:items-start text-center md:text-right space-y-4">
          <img src="/white.svg" alt="Future Homes logo" className="h-20 w-auto mb-2" />
          <p className="text-sm text-gray-400 mt-2">Future Homes Company © 2025</p>
        </div>

        <div className="flex flex-col items-center md:items-start text-center md:text-right">
          <h3 className="text-xl font-semibold mb-4 text-white">مكاتبنا</h3>
          <ul className="space-y-3 text-sm">
            <li>{contactInfo.address}</li>
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
            {socialLinks.map((link) => {
              const icon = iconMap[link.icon];
              if (!icon) return null;
              
              return (
                <a 
                  key={link.id} 
                  href={link.url} 
                  target="_blank" 
                  rel="noopener noreferrer" 
                  aria-label={link.name}
                >
                  <FontAwesomeIcon icon={icon} size="lg" className="hover:text-white transition-colors" />
                </a>
              );
            })}
          </div>
          <div className="space-y-2 mt-4 text-sm text-gray-400">
            <p dir="ltr">{contactInfo.phone}</p>
            <p>{contactInfo.email}</p>
            <a href="https://maps.app.goo.gl/9UYUioUfX9Cuuhnz5?g_st=awb" target="_blank" rel="noopener noreferrer" className="text-future hover:text-white transition-colors flex items-center space-x-1 space-x-reverse">
              <span>موقع المكتب</span>
              <FontAwesomeIcon icon={faMapMarkerAlt} />
            </a>
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