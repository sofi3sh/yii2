import React from 'react';

import Dropdown from './Dropdown';
import withData from '../../hocs/withData';
import CONFIG from '../../config/app';
import ProductImage from '../ProductImage';

const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;
const connectionTypeKey = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_CONNECTION_TYPE;
const typeFlange = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_FLANGE;
const typeUnderWelding = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_UNDER_WELDING;

const renderImages = connectionTypeSelected => (
  <div className="row">
    <div className="col-md-2"></div>
    <div className="col-md-10">
      {typeFlange === connectionTypeSelected && (
        <ProductImage fileName={CONFIG.IMAGES.HYDRAULIC_FLANGE} />
      )}
      {typeUnderWelding === connectionTypeSelected && (
        <ProductImage fileName={CONFIG.IMAGES.HYDRAULIC_UNDER_WELDING} />
      )}
    </div>
  </div>
);

const HydraulicConnectionTypeDropdown = ({
  products: {
    [internalDrainageKey]: {
      parentOptions: { [connectionTypeKey]: connectionType }
    }
  },
  mapOptionsToDropdownProps,
  getTranslation,
  [CONFIG.PRODUCT_OPTIONS.HYDRAULIC_CONNECTION_TYPE]: connectionTypeSelected
}) => {
  return (
    <React.Fragment>
      <Dropdown
        label={getTranslation(connectionType)}
        name={connectionTypeKey}
        options={mapOptionsToDropdownProps({
          data: connectionType.childrenOptions,
          dropdownValue: 'option_key'
        })}
      />
      {renderImages(connectionTypeSelected)}
    </React.Fragment>
  );
};

HydraulicConnectionTypeDropdown.defaultProps = {
  products: []
};

export default withData(['products'])(HydraulicConnectionTypeDropdown);
