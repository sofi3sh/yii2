import React from 'react';

import Dropdown from './Dropdown';
import withData from '../../hocs/withData';
import CONFIG from '../../config/app';

const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;
const drainageTypeKey = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_DRAINAGE_TYPE;

const HydraulicDrainageTypeDropdown = ({
  products: {
    [internalDrainageKey]: {
      parentOptions: { [drainageTypeKey]: drainageType }
    }
  },
  mapOptionsToDropdownProps,
  getTranslation
}) => {
  return (
    <Dropdown
      label={getTranslation(drainageType)}
      name={drainageTypeKey}
      options={mapOptionsToDropdownProps({
        data: drainageType.childrenOptions,
        dropdownValue: 'option_key'
      })}
    />
  );
};

HydraulicDrainageTypeDropdown.defaultProps = {
  products: []
};

export default withData(['products'])(HydraulicDrainageTypeDropdown);
