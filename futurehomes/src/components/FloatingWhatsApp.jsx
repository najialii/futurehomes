import React, { useState } from 'react';
import { FaWhatsapp, FaTimes } from 'react-icons/fa';

const FloatingWhatsApp = () => {
  const [isOpen, setIsOpen] = useState(false);

  const whatsappNumbers = [
    {
      number: "966590007681",
      label: "واتساب 59",
      message: "مرحباً، أريد الاستفسار عن خدماتكم"
    },
    {
      number: "FWVXMVP7OTTQA1",
      label: "واتساب 555",
      link: "https://wa.me/message/FWVXMVP7OTTQA1?src=qr"
    }
  ];

  const handleWhatsAppClick = (contact) => {
    if (contact.link) {
      window.open(contact.link, '_blank');
    } else {
      const message = encodeURIComponent(contact.message);
      window.open(`https://wa.me/${contact.number}?text=${message}`, '_blank');
    }
  };

  return (
    <div className="fixed bottom-6 left-6 z-50">
      {isOpen && (
        <div className="mb-4 bg-white rounded-lg shadow-lg p-4 min-w-[200px]">
          <div className="text-right mb-3">
            <h3 className="font-semibold text-gray-800 font-elmassri">تواصل معنا</h3>
          </div>
          {whatsappNumbers.map((contact, index) => (
            <button
              key={index}
              onClick={() => handleWhatsAppClick(contact)}
              className="w-full text-right p-2 hover:bg-gray-100 rounded flex items-center justify-end space-x-2 space-x-reverse font-elmassri"
            >
              <span className="text-gray-700">{contact.label}</span>
              <FaWhatsapp className="text-green-500" size={20} />
            </button>
          ))}
        </div>
      )}

      <button
        onClick={() => setIsOpen(!isOpen)}
        className={`bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110 ${
          isOpen ? 'rotate-45' : ''
        }`}
        aria-label="WhatsApp"
      >
        {isOpen ? <FaTimes size={24} /> : <FaWhatsapp size={24} />}
      </button>
    </div>
  );
};

export default FloatingWhatsApp;