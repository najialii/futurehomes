// components/Stats.jsx
import React from "react";
import CountUp from "react-countup";
import { motion } from "framer-motion";

const stats = [
  { value: 13, suffix: "+", label: "سنوات الخبرة" },
  { value: 87, suffix: "+", label: "المشاريع المنجزة" },
  { value: 70, suffix: "+", label: "طاقم العمل" },
  { value: 20, suffix: "+", label: "شركاء النجاح" },
];

function Stats() {
  return (
    <section className="py-20 bg-gradient-to-br from-gray-900 to-future text-white text-center">
      <div className="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
        {stats.map((stat, i) => (
          <motion.div
            key={i}
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: i * 0.1 }}
            className="flex flex-col items-center"
          >
            <h3 className="text-4xl font-bold">
              <CountUp end={stat.value} duration={2.5} />{stat.suffix}
            </h3>
            <p className="mt-2 text-lg">{stat.label}</p>
          </motion.div>
        ))}
      </div>
    </section>
  );
}

export default Stats;
