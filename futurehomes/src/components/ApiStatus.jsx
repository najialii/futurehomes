import React, { useState, useEffect } from 'react';
import ApiService from '../services/api';

const ApiStatus = () => {
  const [status, setStatus] = useState('checking');
  const [stats, setStats] = useState(null);

  useEffect(() => {
    const checkApiStatus = async () => {
      try {
        const [servicesResponse, statsResponse, partnersResponse] = await Promise.all([
          ApiService.getServices(),
          ApiService.getStats(),
          ApiService.getPartners()
        ]);

        setStats({
          services: servicesResponse.data?.length || 0,
          stats: statsResponse.data?.length || 0,
          partners: partnersResponse.data?.length || 0,
        });
        setStatus('connected');
      } catch (error) {
        console.error('API connection failed:', error);
        setStatus('disconnected');
      }
    };

    checkApiStatus();
  }, []);

  const getStatusColor = () => {
    switch (status) {
      case 'connected': return 'text-green-600';
      case 'disconnected': return 'text-red-600';
      default: return 'text-yellow-600';
    }
  };

  const getStatusText = () => {
    switch (status) {
      case 'connected': return 'متصل بالخادم';
      case 'disconnected': return 'غير متصل بالخادم';
      default: return 'جاري التحقق...';
    }
  };

  return (
    <div className="fixed bottom-4 right-4 bg-white p-3 rounded-lg shadow-lg border text-sm" dir="rtl">
      <div className={`font-semibold ${getStatusColor()}`}>
        API: {getStatusText()}
      </div>
      {stats && (
        <div className="text-gray-600 text-xs mt-1">
          خدمات: {stats.services} | إحصائيات: {stats.stats} | شركاء: {stats.partners}
        </div>
      )}
    </div>
  );
};

export default ApiStatus;