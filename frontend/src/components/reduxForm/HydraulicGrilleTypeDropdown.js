import React from 'react';

import Dropdown from './Dropdown';
import withData from '../../hocs/withData';
import CONFIG from '../../config/app';

const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;
const grilleTypeKey = CONFIG.PRODUCT_OPTIONS.HYDRAULIC_GRILLE_TYPE;

const HydraulicGrilleTypeDropdown = ({
  products: {
    [internalDrainageKey]: {
      parentOptions: { [grilleTypeKey]: grilleType }
    }
  },
  mapOptionsToDropdownProps,
  getTranslation
}) => {
  return (
    <Dropdown
      label={getTranslation(grilleType)}
      name={grilleTypeKey}
      options={mapOptionsToDropdownProps({
        data: grilleType.childrenOptions,
        dropdownValue: 'option_key'
      })}
    />
  );
};

HydraulicGrilleTypeDropdown.defaultProps = {
  products: []
};

export default withData(['products'])(HydraulicGrilleTypeDropdown);
