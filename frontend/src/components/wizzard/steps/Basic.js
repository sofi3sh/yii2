import React, { Component } from 'react';

import CONFIG from '../../../config/app';
import Input from '../../reduxForm/Input';
import Checkbox from '../../reduxForm/Checkbox';
import ClientsDropdown from '../../reduxForm/ClientsDropdown';
import ProductsDropdown from '../../reduxForm/ProductsDropdown';

const productoptionKeys = CONFIG.PRODUCT_OPTIONS;

class Basic extends Component {
  isValidated() {
    return this.props.valid;
  }

  render() {
    return (
      <div>
        <Input label="User" name="user_full_name" readOnly="readonly" />
        <ClientsDropdown />
        <ProductsDropdown {...this.props} />
        <Checkbox
          label="Allow Fragments"
          name={productoptionKeys.ALLOW_FRAGMENTS}
        />
      </div>
    );
  }
}

export default Basic;
