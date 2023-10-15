import React from 'react';

import CONFIG from '../config/app';

const ProductImage = ({ fileName }) => {
  return (
    <img
      src={`${CONFIG.API_BASE_URL}/images/products/${fileName}`}
      style={{ height: '200px', width: '350px' }}
    />
  );
};

export default ProductImage;
