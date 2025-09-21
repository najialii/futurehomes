import React from "react";

function Partners() {
  return (
    <section className="bg-gradient-to-br from-future to-gray-900 text-white py-16">
      {/* Section Title */}
      <div className="text-center mb-10 px-4">
        <h2 className="text-3xl md:text-4xl font-bold">
          شركاؤنا في النجاح
        </h2>
      </div>

      {/* Logos Grid */}
      <div className="flex flex-wrap justify-center items-center gap-10 px-4">
        <img
          className="w-32 h-auto object-contain transform transition-transform duration-300 hover:scale-110"
          src="/3nood.svg"
          alt="3nood"
        />
        <img
          className="w-32 h-auto object-contain transform transition-transform duration-300 hover:scale-110"
          src="/joodwhite.svg"
          alt="jood"
        />
        <img
          className="w-32 h-auto object-contain transform transition-transform duration-300 hover:scale-110"
          src="/3nood.svg"
          alt="3nood"
        />
        <img
          className="w-32 h-auto object-contain transform transition-transform duration-300 hover:scale-110"
          src="/joodwhite.svg"
          alt="jood"
        />
      </div>
    </section>
  );
}

export default Partners;
