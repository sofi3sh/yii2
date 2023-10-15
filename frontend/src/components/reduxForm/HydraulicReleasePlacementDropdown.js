import React from 'react';

import withData from '../../hocs/withData';
import CONFIG from '../../config/app';
import Dropdown from './Dropdown';

const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;
const releasePlacementKey = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_RELEASE_PLACEMENT;

const HydraulicReleasePlacementDropdown = ({
  products: {
    [internalDrainageKey]: {
      parentOptions: { [releasePlacementKey]: releasePlacement }
    }
  },
  mapOptionsToDropdownProps,
  getTranslation
}) => {
  return (
    <Dropdown
      label={getTranslation(releasePlacement)}
      name={releasePlacementKey}
      options={mapOptionsToDropdownProps({
        data: releasePlacement.childrenOptions,
        dropdownValue: 'option_key'
      })}
    />
  );
};

export default withData(['products'])(HydraulicReleasePlacementDropdown);
