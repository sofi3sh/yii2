import React from 'react';

import Dropdown from './Dropdown';
import withData from '../../hocs/withData';
import CONFIG from '../../config/app';

const outfallDiametrKey = CONFIG.PRODUCT_OPTIONS.OUTFALL_DIAMETR;
const internalDrainageKey = CONFIG.PRODUCTS.INTERNAL_DRAINAGE;

const OutfallDiametrDropdown = ({
  products: {
    [internalDrainageKey]: {
      parentOptions: { [outfallDiametrKey]: outfallDiametr }
    }
  },
  mapOptionsToDropdownProps,
  getTranslation,
  onChange
}) => {
  return (
    <Dropdown
      label={getTranslation(outfallDiametr)}
      name={outfallDiametrKey}
      options={mapOptionsToDropdownProps({
        data: outfallDiametr.childrenOptions,
        dropdownValue: 'option_key'
      })}
      onChange={onChange}
    />
  );
};

export default withData(['products'])(OutfallDiametrDropdown);
