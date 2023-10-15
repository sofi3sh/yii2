import React from 'react';

import Dropdown from './Dropdown';
import withData from '../../hocs/withData';

const ProductsDropdown = ({
  products,
  mapOptionsToDropdownProps,
  isUpdateAction
}) => (
  <Dropdown
    label="Product"
    name="product_key"
    options={mapOptionsToDropdownProps({
      data: products,
      dropdownValue: 'product_key'
    })}
    disabled={isUpdateAction}
  />
);

ProductsDropdown.defaultProps = {
  products: []
};

export default withData(['products'])(ProductsDropdown);
