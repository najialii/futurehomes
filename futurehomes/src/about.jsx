import React from "react";
import { motion } from "framer-motion";

// Define a common animation variant for sections
const sectionVariants = {
  hidden: { opacity: 0, y: 50 },
  visible: {
    opacity: 1,
    y: 0,
    transition: {
      duration: 0.6,
      ease: "easeOut",
      staggerChildren: 0.2, // Animate child elements with a delay
    },
  },
};

// Define an animation variant for individual cards or elements
const cardVariants = {
  hidden: { opacity: 0, y: 20 },
  visible: { opacity: 1, y: 0 },
};

function About() {
  return (
    <div className="bg-gray-100 text-gray-800 font-elmassri" dir="rtl">
      {/* Hero Section */}
      <div className="relative h-[50vh] flex items-center justify-center bg-gray-900 text-white">
        <div className="absolute inset-0 bg-cover bg-center opacity-40" style={{ backgroundImage: "url('/path-to-your-about-us-hero-image.jpg')" }}></div>
        <div className="relative text-center px-4">
          <motion.h1
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
            className="text-3xl sm:text-5xl md:text-6xl font-bold leading-tight"
          >
            من نحن
          </motion.h1>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
            className="text-xl md:text-2xl mt-4"
          >
            نحن هنا لنبني أحلامك
          </motion.p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">

        <motion.div
          className="mb-16 text-center"
          variants={sectionVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.2 }}
        >
          <motion.h2 variants={cardVariants} className="text-3xl font-bold text-gray-900 mb-4">قصتنا</motion.h2>
          <motion.p variants={cardVariants} className="max-w-3xl mx-auto text-lg text-gray-700 leading-relaxed">
            نحن في **Future Homes**، شركة مقاولات سعودية نفخر بخبرة تزيد عن 15 عاماً في تحويل الأفكار المعمارية إلى واقع ملموس. يرتكز عملنا على الإتقان والابتكار، حيث يجمع فريقنا بين نخبة من المهندسين المحترفين وكادر فني متميز لتقديم حلول متكاملة تلبي أعلى معايير الجودة.
          </motion.p>
        </motion.div>
        
        <motion.div
          className="mb-16 grid grid-cols-1 md:grid-cols-2 gap-8 text-center md:text-right"
          variants={sectionVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.2 }}
        >
          <motion.div variants={cardVariants} className="bg-white p-8 rounded-lg shadow-lg">
            <h3 className="text-2xl font-bold text-future mb-3">رؤيتنا</h3>
            <p className="text-gray-700 leading-relaxed">
              أن نصبح من الشركات الرائدة في مجال المقاولات والهندسة المعمارية في المملكة العربية السعودية بما يواكب رؤية المملكة 2030.
            </p>
          </motion.div>
          <motion.div variants={cardVariants} className="bg-white p-8 rounded-lg shadow-lg">
            <h3 className="text-2xl font-bold text-future mb-3">رسالتنا</h3>
            <p className="text-gray-700 leading-relaxed">
              تقديم خدمات عالية الجودة من خلال الالتزام بالإتقان والابتكار في العمل، والسعي لتحقيق جميع تطلعات العملاء.
            </p>
          </motion.div>
        </motion.div>

        <motion.div
          className="mb-16 text-center"
          variants={sectionVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.2 }}
        >
          <motion.h2 variants={cardVariants} className="text-3xl font-bold text-gray-900 mb-8">مبادئنا الأساسية</motion.h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <motion.div variants={cardVariants} className="p-6 bg-white rounded-lg shadow-lg">
              <h4 className="text-xl font-semibold mb-2 text-future">استشارات احترافية</h4>
              <p className="text-gray-600">نقدم استشارات هندسية دقيقة لضمان أعلى مستويات الجودة في جميع مراحل العمل.</p>
            </motion.div>
            <motion.div variants={cardVariants} className="p-6 bg-white rounded-lg shadow-lg">
              <h4 className="text-xl font-semibold mb-2 text-future">كفاءات متميزة</h4>
              <p className="text-gray-600">نسعى دائماً لجذب أفضل الكفاءات وتطويرها، لضمان أن يكون فريقنا الأفضل في المجال.</p>
            </motion.div>
            <motion.div variants={cardVariants} className="p-6 bg-white rounded-lg shadow-lg">
              <h4 className="text-xl font-semibold mb-2 text-future">شراكات موثوقة</h4>
              <p className="text-gray-600">نعقد شراكات مع أفضل الشركات المتميزة في التوريد والتركيب لضمان جودة المواد.</p>
            </motion.div>
            <motion.div variants={cardVariants} className="p-6 bg-white rounded-lg shadow-lg">
              <h4 className="text-xl font-semibold mb-2 text-future">تميز تشغيلي</h4>
              <p className="text-gray-600">نعمل على تحقيق التميز التشغيلي في كافة أعمالنا لضمان سلاسة التنفيذ ورضا العميل.</p>
            </motion.div>
          </div>
        </motion.div>

        <motion.div
          className="flex flex-col justify-center items-center text-center md:text-right"
          variants={sectionVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.2 }}
        >
          <motion.h2 variants={cardVariants} className="text-3xl font-bold text-gray-900 mb-4">فريقنا</motion.h2>
          <motion.p variants={cardVariants} className="max-w-3xl mx-auto md:mx-0 text-lg text-gray-700 leading-relaxed mb-8">
            نؤمن بأن فريقنا هو حجر الزاوية في نجاحنا. يعمل في [اسم شركتك] فريق هندسي محترف وكادر من العمالة الماهرة، يجمعهم الشغف المشترك بالتميز والالتزام بتقديم أفضل ما لديهم في كل مشروع.
          </motion.p>
          <motion.a
            variants={cardVariants}
            href="#contact"
            className="inline-block px-8 py-4 bg-future text-white font-semibold rounded-full shadow-lg transition-transform hover:scale-105"
          >
            نحن مستعدون لبناء أحلامك
          </motion.a>
        </motion.div>
      </div>
    </div>
  );
}

export default About;