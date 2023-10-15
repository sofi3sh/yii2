import React from 'react';

const BasicButton = ({ href, title, iconClass }) => {
  return (
    <a className="btn btn-primary ml-2" href={href} title={title}>
      <i className={iconClass}></i>
    </a>
  );
};

export default BasicButton;
