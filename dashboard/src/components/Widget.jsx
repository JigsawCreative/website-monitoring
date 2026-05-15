import React from 'react';
import '../css/Widget.css';

const Widget = ({ title, value, children }) => {
  return (
    <div className="widget">
      <div className="widget__header">{title}</div>
      <div className="widget__value">{value}</div>
      {children && <div className="widget__content">{children}</div>}
    </div>
  );
};

export default Widget;