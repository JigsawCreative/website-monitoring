import React from 'react';
import '../css/Sidebar.css';

const Sidebar = () => {
  return (
    <aside className="sidebar">
      <div className="sidebar__logo">
        <h2>CBWM</h2>
      </div>
      <nav className="sidebar__nav">
        <ul>
          <li><a href="/">Dashboard</a></li>
          <li><a href="/sites">Sites</a></li>
          <li><a href="/runs">Runs</a></li>
          <li><a href="/settings">Settings</a></li>
        </ul>
      </nav>
    </aside>
  );
};

export default Sidebar;
