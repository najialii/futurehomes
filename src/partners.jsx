import React from "react";

function Partners() {
  return (
    <section className="bg-gradient-to-br from-future to-gray-900 text-white py-16">
      {/* Section Title */}
      <div className="text-center mb-10 px-4">
        <h2 className="text-3xl md:text-4xl font-bold">شركاؤنا في النجاح</h2>
      </div>

      <div className="grid grid-cols-2 md:grid-cols-4 gap-10 md:gap-16 px-6 place-items-center">
        <img
          className="h-20 sm:h-24 md:h-28 w-auto object-contain transform transition-transform duration-300 hover:scale-110"
          src="/3nood.svg"
          alt="3nood"
        />
        <img
          className="h-16 sm:h-24 md:h-28 w-32 object-contain transform transition-transform duration-300 hover:scale-110"
          src="/joodwhite.svg"
          alt="jood"
        />
        <img
          className="h-20 sm:h-24 md:h-28 w-auto object-contain transform transition-transform duration-300 hover:scale-110"
          src="/clu.png"
          alt="clu"
        />
        <img
          className="h-20 sm:h-24 md:h-28 w-auto object-contain transform transition-transform duration-300 hover:scale-110"
          src="/nobg.png"
          alt="nobg"
        />
      </div>
    </section>
  );
}

export default Partners;
